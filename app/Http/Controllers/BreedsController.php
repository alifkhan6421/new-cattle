<?php

namespace App\Http\Controllers;

use App\Exports\BreedExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Breed;
use App\Models\Cattle;
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

class BreedsController extends Controller
{
    /**
     * Display a listing of the breeds.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->breedList($request);
        }

        $breeds = Breed::with('cattle','aiWorker')->get();
        $cattle = Cattle::all()->pluck('title', 'id');
        $aiWorkers = User::whereHas('roles', function ($q) {
            $q->where('name', 'AI Worker');
        })->orderBy('id', 'ASC')->pluck('name', 'id');
        return view('breeds.index', compact('breeds', 'aiWorkers', 'cattle'));
    }

    public function getQuery($request)
    {
        $query = Breed::query()->with('cattle', 'aiWorker');
        if (!empty($request->breedingStartDate) && !empty($request->breedingEndDate)) {
            $query->whereBetween('breeding_date', [
                $request->breedingStartDate . ' 00:00:00',
                $request->breedingEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->expectedBirthStartDate) && !empty($request->expectedBirthEndDate)) {
            $query->whereBetween('expected_birth_date', [
                $request->expectedBirthStartDate . ' 00:00:00',
                $request->expectedBirthEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->cattleId)) {
            $query->where('cattle_id', $request->cattleId);
        }

        if (!empty($request->aiWorkerId)) {
            $query->where('ai_worker_id', $request->aiWorkerId);
        }

        if (!empty($request->breedingType)) {
            $query->where('breeding_type', $request->breedingType);
        }

        if (!empty($request->breedingStatus)) {
            $query->where('breeding_status', $request->breedingStatus);
        }

        if (isset($request->cost) && !empty($request->opCost)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'cost',
                $request->opCost,
                $request->cost
            );
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
    public function breedList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($breed) {
                return view('breeds.action', compact('breed'));
            })
            ->editColumn('cattle', function ($breed) {
                return optional($breed->cattle)->title;
            })
            ->editColumn('ai_worker', function ($breed) {
                return optional($breed->aiWorker)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new BreedExport($this->getQuery($request)),
            'breeds-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $breed = Breed::with('cattle','aiWorker','creator')->findOrFail($id);
        $view = view('breeds.print_details', compact('breed'));
        CommonHelper::generatePdf($view->render(), 'Breed-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new breed.
     *
     * @return View
     */
    public function create()
    {
        $cattle = Cattle::all()->pluck('title','id');
		$aiWorkers = User::whereHas('roles', function ($q) {
		    $q->where('name', 'AI Worker');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('breeds.create', compact('cattle','aiWorkers'));
    }

    /**
     * Store a new breed in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Breed::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'breeds.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('breeds.index')
                             ->with('success_message', __('message.breed_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified breed.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $breed = Breed::with('cattle','aiWorker','creator')->findOrFail($id);
        return view('breeds.show', compact('breed'));
    }

    /**
     * Show the form for editing the specified breed.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $breed = Breed::findOrFail($id);
        $cattle = Cattle::all()->pluck('title','id');
		$aiWorkers = User::whereHas('roles', function ($q) {
		    $q->where('name', 'AI Worker');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('breeds.edit', compact('breed','cattle','aiWorkers'));
    }

    /**
     * Update the specified breed in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $breed = Breed::findOrFail($id);
            $oldData = $breed->toArray();
            $breed->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'breeds.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('breeds.index')
                             ->with('success_message', __('message.breed_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified breed from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $breed = Breed::findOrFail($id);
            $oldData = $breed->toArray();
            $breed->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'breeds.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('breeds.index')
                             ->with('success_message', __('message.breed_was_successfully_deleted'));
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
            'breeding_date' => 'required|date',
            'breeding_type' => 'required',
            'breeding_status' => 'required',
            'expected_birth_date' => 'required|date',
            'cost' => 'required|numeric|min:-2147483648|max:2147483647',
            'ai_worker_id' => 'required',
            'comments' => 'nullable'
        ]);
        $data['comments'] = clean($request->comments);
        return $data;
    }

}
