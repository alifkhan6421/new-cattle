<?php

namespace App\Http\Controllers;

use App\Exports\CattleTypeExport;
use App\Helpers\CommonHelper;
use App\Models\CattleType;
use App\Models\DailyWage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class CattleTypesController extends Controller
{
    /**
     * Display a listing of the cattle types.
     *
     * @return View
     */
    public function index()
    {
        $cattleTypes = CattleType::get();
        return view('cattle_types.index', compact('cattleTypes'));
    }

    public function getQuery()
    {
        return CattleType::query();
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new CattleTypeExport($this->getQuery($request)),
            'cattle-type-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $cattleType = CattleType::findOrFail($id);
        $view = view('cattle_types.print_details', compact('cattleType'));
        CommonHelper::generatePdf($view->render(), 'Cattle-type-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new cattle type.
     *
     * @return View
     */
    public function create()
    {
        return view('cattle_types.create');
    }

    /**
     * Store a new cattle type in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            CattleType::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_types.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('cattle_types.index')
                             ->with('success_message',  __('message.cattle_type_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified cattle type.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $cattleType = CattleType::findOrFail($id);
        return view('cattle_types.show', compact('cattleType'));
    }

    /**
     * Show the form for editing the specified cattle type.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $cattleType = CattleType::findOrFail($id);
        return view('cattle_types.edit', compact('cattleType'));
    }

    /**
     * Update the specified cattle type in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $cattleType = CattleType::findOrFail($id);
            $oldData = $cattleType->toArray();
            $cattleType->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_types.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('cattle_types.index')
                             ->with('success_message', __('message.cattle_type_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified cattle type from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $cattleType = CattleType::findOrFail($id);
            $oldData = $cattleType->toArray();
            $cattleType->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_types.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('cattle_types.index')
                             ->with('success_message', __('message.cattle_type_was_successfully_deleted'));
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
            'title' => 'required|string|min:1|max:50',
            'status' => 'required',

        ]);

        $data['title'] = clean($request->title);

        return $data;
    }
}
