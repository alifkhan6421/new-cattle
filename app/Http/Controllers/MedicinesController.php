<?php

namespace App\Http\Controllers;

use App\Exports\MedicineExport;
use App\Models\User;
use App\Models\Cattle;
use App\Models\Medicine;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class MedicinesController extends Controller
{
    /**
     * Display a listing of the medicines.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->medicineList($request);
        }

        $medicines = Medicine::with('cattle','doctor')->get();
        $cattle = Cattle::all()->pluck('title','id');
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('medicines.index', compact('medicines', 'cattle', 'doctors'));
    }

    public function getQuery($request)
    {
        $query = Medicine::query()->with('cattle', 'doctor');
        if (!empty($request->identifyStartDate) && !empty($request->identifyEndDate)) {
            $query->whereBetween('identify_date', [
                $request->identifyStartDate . ' 00:00:00',
                $request->identifyEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->nextFollowUpStartDate) && !empty($request->nextFollowUpEndDate)) {
            $query->whereBetween('next_follow_up_date', [
                $request->nextFollowUpStartDate . ' 00:00:00',
                $request->nextFollowUpEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->cattleId)) {
            $query->where('cattle_id', $request->cattleId);
        }

        if (!empty($request->doctorId)) {
            $query->where('doctor_id', $request->doctorId);
        }

        return $query;
    }

    /**
     * Display a json listing for table body.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function medicineList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($medicine) {
                return view('medicines.action', compact('medicine'));
            })
            ->editColumn('cattle', function ($medicine) {
                return optional($medicine->cattle)->title;
            })
            ->editColumn('doctor', function ($medicine) {
                return optional($medicine->doctor)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new MedicineExport($this->getQuery($request)),
            'medicine-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $medicine = Medicine::with('cattle','doctor','creator')->findOrFail($id);
        $view = view('medicines.print_details', compact('medicine'));
        $html = $view->render();

        // (Optional) Setup the paper size and orientation: (landscape, portrait)
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }

    /**
     * Show the form for creating a new medicine.
     *
     * @return View
     */
    public function create()
    {
        $cattle = Cattle::all()->pluck('title','id');
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');
        })->orderBy('name', 'ASC')->pluck('name', 'id');

        return view('medicines.create', compact('cattle','doctors'));
    }

    /**
     * Store a new medicine in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Medicine::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'medicines.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('medicines.index')
                             ->with('success_message', __('message.medicine_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified medicine.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $medicine = Medicine::with('cattle','doctor','creator')->findOrFail($id);
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        $cattle = Cattle::all()->pluck('title','id');
		$doctors = User::whereHas('roles', function ($q) {
		    $q->where('name', 'Doctor');
        })->orderBy('name', 'ASC')->pluck('name', 'id');

        return view('medicines.edit', compact('medicine','cattle','doctors'));
    }

    /**
     * Update the specified medicine in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $medicine = Medicine::findOrFail($id);
            $oldData = $medicine->toArray();
            $medicine->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'medicines.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('medicines.index')
                             ->with('success_message', __('message.medicine_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified medicine from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $oldData = $medicine->toArray();
            $medicine->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'medicines.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('medicines.index')
                             ->with('success_message', __('message.medicine_was_successfully_deleted'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->validate([
            'cattle_id' => 'required',
            'doctor_id' => 'required',
            'identify_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'next_follow_up_date' => 'required|date',
            'special_dose' => 'required',
            'regular_dose' => 'required',
            'comments' => 'required',
        ]);

        $data['special_dose']  = clean($request->special_dose);
        $data['regular_dose']  = clean($request->regular_dose);
        $data['comments']      = clean($request->comments);

        return $data;
    }
}
