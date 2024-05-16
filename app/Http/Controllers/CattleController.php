<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Cattle;
use App\Models\EventLog;
use Illuminate\View\View;
use App\Models\CattleCost;
use App\Models\CattleType;
use App\Helpers\EventHelper;
use App\Models\CattleWeight;
use Illuminate\Http\Request;
use App\Exports\CattleExport;
use App\Helpers\CommonHelper;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CattleController extends Controller
{
    /**
     * Display a listing of the cattle.
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->cattleList($request);
        }

        $cattleObjects = Cattle::with('cattletype', 'cattle')->get();
        $cattleTypes = CattleType::where('status', 'Active')->pluck('title', 'id');
        $parents = Cattle::where('cattle_type_id', config('constants.CATTLE_TYPE_COW'))->pluck('title', 'id');
        return view('cattle.index', compact('cattleObjects', 'cattleTypes', 'parents'));
    }

    public function getQuery($request)
    {
        $query = Cattle::query()->with('cattleType', 'cattle', 'creator');
        if (!empty($request->purchaseStartDate) && !empty($request->purchaseEndDate)) {
            $query->whereBetween('purchase_date', [
                $request->purchaseStartDate . ' 00:00:00',
                $request->purchaseEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->farmEntryStartDate) && !empty($request->farmEntryEndDate)) {
            $query->whereBetween('farm_entry_date', [
                $request->farmEntryStartDate . ' 00:00:00',
                $request->farmEntryEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        if (!empty($request->cattleTypeId)) {
            $query->where('cattle_type_id', $request->cattleTypeId);
        }

        if (!empty($request->parentId)) {
            $query->where('parent_id', $request->parentId);
        }

        if (!empty($request->purchaseSource)) {
            $query->where('purchase_source', 'like', '%' . $request->purchaseSource . '%');
        }

        if (!empty($request->species)) {
            $query->where('species', 'like', '%' . $request->species . '%');
        }

        if (isset($request->purchaseAmount) && !empty($request->opPurchaseAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'purchase_amount',
                $request->opPurchaseAmount,
                $request->purchaseAmount
            );
        }

        if (isset($request->expectedSalePrice) && !empty($request->opExpectedSalePrice)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'expected_sale_price',
                $request->opExpectedSalePrice,
                $request->expectedSalePrice
            );
        }

        if (isset($request->dailyExpense) && !empty($request->opDailyExpense)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'daily_expense',
                $request->opDailyExpense,
                $request->dailyExpense
            );
        }

        return $query;
    }

    /**
     * Display a listing of the cattle.
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function cattleList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addColumn('action', function ($cattle) {
                return view('cattle.action', compact('cattle'));
            })
            ->addColumn('weight', function ($cattle) {
                $cattleWeight = CattleWeight::where('cattle_id', $cattle->id)
                    ->orderBy('date', 'DESC')
                    ->first();
                return optional($cattleWeight)->weight;
            })
            ->addColumn('cost', function ($cattle) {
                return CattleCost::where('cattle_id', $cattle->id)->sum('cost');
            })
            ->editColumn('title', function ($cattle) {
                return '<a href="' . route('cattle.show', $cattle->id) . '">
                            ' . $cattle->title . ' 
                        </a>';
            })
            ->editColumn('cattle_type', function ($cattle) {
                return optional($cattle->cattletype)->title;
            })
            ->rawColumns(['action', 'title'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(new CattleExport($this->getQuery($request)), 'cattle-' . time() . '.xlsx');
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $cattle = Cattle::with('cattletype', 'cattle', 'creator')->findOrFail($id);
        $cattleWeights = CattleWeight::where('cattle_id', $id)
            ->orderBy('date', 'DESC')
            ->get();
        $cattleCosts = CattleCost::where('cattle_id', $id)
            ->orderBy('date', 'DESC')
            ->get();

        $view = view('cattle.print_details', compact('cattle', 'cattleWeights', 'cattleCosts'));
        CommonHelper::generatePdf($view->render(), 'Cattle-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new cattle.
     *
     * @return View
     */
    public function create()
    {
        $cattleTypes = CattleType::where('status', 'Active')->pluck('title', 'id');
        $parents = Cattle::where('cattle_type_id', config('constants.CATTLE_TYPE_COW'))->pluck('title', 'id');
        return view('cattle.create', compact('cattleTypes', 'parents'));
    }

    public function bodyWeight($cattleId)
    {
        $cattle = Cattle::findOrFail($cattleId);
        $cattleWeights = CattleWeight::where('cattle_id', $cattleId)->orderBy('date', 'DESC')->get();
        return view('cattle.modal_body_weights', compact('cattle', 'cattleWeights'));
    }

    public function cost($cattleId)
    {
        $cattle = Cattle::findOrFail($cattleId);
        $cattleCosts = CattleCost::where('cattle_id', $cattleId)->orderBy('date', 'DESC')->get();
        $totalCost = CattleCost::where('cattle_id', $cattleId)->sum('cost');
        return view('cattle.modal_costs', compact('cattle', 'cattleCosts', 'totalCost'));
    }

    /**
     * Store a new body weight in the storage.
     *
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function storeBodyWeight(Request $request)
    {   
        $data = $this->getBodyWeightData($request);
        try {
            CattleWeight::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_weights.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            $html = '';
            if (!empty($request->cattle_id)) {
                $cattleWeights = CattleWeight::where('cattle_id', $request->cattle_id)
                    ->orderBy('date', 'DESC')
                    ->get();

                $html = view('cattle.table_body_weights', compact('cattleWeights'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_body_weight_was_successfully_added')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => $exception->getMessage()];
        }
    }

    public function updateBodyWeight($bodyWeightId, Request $request)
    {
        $data = $this->getBodyWeightData($request);
        try {
            $cattleWeight = CattleWeight::findOrFail($bodyWeightId);
            $oldData = $cattleWeight->toArray();
            $cattleWeight->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.updateBodyWeight',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            $html = '';
            if (!empty($request->cattle_id)) {
                $cattleWeights = CattleWeight::where('cattle_id', $request->cattle_id)
                    ->orderBy('date', 'DESC')
                    ->get();

                $html = view('cattle.table_body_weights', compact('cattleWeights'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_body_weight_was_successfully_updated')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => $exception->getMessage()];
        }
    }

    public function storeCost(Request $request)
    {
        $data = $this->getCostData($request);
        try {
            
            CattleCost::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.storeCost',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            $html = '';
            if (!empty($request->cattle_id)) {
                $totalCost = CattleCost::where('cattle_id', $request->cattle_id)->sum('cost');
                $cattleCosts = CattleCost::where('cattle_id', $request->cattle_id)
                    ->orderBy('date', 'DESC')
                    ->get();

                $html = view('cattle.table_costs', compact('cattleCosts', 'totalCost'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_cost_was_successfully_added')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => $exception->getMessage()];
        }
    }

    public function updateCost($costId, Request $request)
    {
        $data = $this->getCostData($request);
        try {
            $cattleCost = CattleCost::findOrFail($costId);
            $oldData = $cattleCost->toArray();
            $cattleCost->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.updateCost',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            $html = '';
            if (!empty($request->cattle_id)) {
                $totalCost = CattleCost::where('cattle_id', $request->cattle_id)->sum('cost');
                $cattleCosts = CattleCost::where('cattle_id', $request->cattle_id)
                    ->orderBy('date', 'DESC')
                    ->get();

                $html = view('cattle.table_costs', compact('cattleCosts', 'totalCost'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_cost_was_successfully_updated')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => $exception->getMessage()];
        }
    }

    /**
     * Store a new cattle in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Cattle::create($data);
            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('cattle.index')
                ->with('success_message', __('message.cattle_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified cattle.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $cattle = Cattle::with('cattletype', 'cattle', 'creator')->findOrFail($id);
        $cattleWeights = CattleWeight::where('cattle_id', $id)
            ->orderBy('date', 'DESC')
            ->get();
        $cattleCosts = CattleCost::where('cattle_id', $id)
            ->orderBy('date', 'DESC')
            ->get();
        return view('cattle.show', compact('cattle', 'cattleWeights', 'cattleCosts'));
    }

    /**
     * Show the form for editing the specified cattle.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $cattle = Cattle::findOrFail($id);
        $cattleTypes = CattleType::where('status', 'Active')->pluck('title', 'id');
        $parents = Cattle::where('cattle_type_id', config('constants.CATTLE_TYPE_COW'))
            ->where('id', '!=', $id)
            ->pluck('title', 'id');
        return view('cattle.edit', compact('cattle', 'cattleTypes', 'parents'));
    }

    /**
     * Update the specified cattle in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {
            $cattle = Cattle::findOrFail($id);
            $oldData = $cattle->toArray();
            
            if(isset($data['purchase_image'])){
                Storage::delete($cattle->purchase_image);
            }
            
            if(isset($data['latest_image'])){
                Storage::delete($cattle->latest_image);
            }

            $cattle->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('cattle.index')
                ->with('success_message', __('message.cattle_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified cattle from the storage.
     *
     * @param int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $cattle = Cattle::findOrFail($id);
            $oldData = $cattle->toArray();
            //CattleWeight::where('cattle_id', $id)->delete();
            $cattle->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('cattle.index')
                ->with('success_message', __('message.cattle_was_successfully_deleted'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified cattle weight from the storage.
     *
     * @param int $id
     * @return array
     * @throws Throwable
     */
    public function destroyBodyWeight($id)
    {
        try {
            $cattleWeight = CattleWeight::findOrFail($id);
            $cattleId = $cattleWeight->cattle_id;
            $oldData = $cattleWeight->toArray();
            $cattleWeight->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_weights.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            $cattleWeights = CattleWeight::where('cattle_id', $cattleId)
                ->orderBy('date', 'DESC')
                ->get();
            $html = view('cattle.table_body_weights', compact('cattleWeights'))->render();

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_body_weight_was_successfully_deleted')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
        }
    }

    public function destroyCost($id)
    {
        try {
            $cattleCost = CattleCost::findOrFail($id);
            $cattleId = $cattleCost->cattle_id;
            $oldData = $cattleCost->toArray();
            $cattleCost->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'cattle_cost.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            $totalCost = CattleCost::where('cattle_id', $cattleId)->sum('cost');
            $cattleCosts = CattleCost::where('cattle_id', $cattleId)
                ->orderBy('date', 'DESC')
                ->get();
            $html = view('cattle.table_costs', compact('cattleCosts', 'totalCost'))->render();

            return ['status' => 'OK', 'html' => $html, 'message' => __('message.cattle_cost_was_successfully_deleted')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @param int $cattleId
     * @return array
     */
    protected function getData(Request $request, $cattleId = 0)
    {
        $data = $request->validate([
            // 'title' => 'required|string|min:1|max:100|unique:cattle,title,' . $cattleId . ',id,deleted_at,NULL',
            'title' => 'required|string|min:1|max:100|unique:cattle,title,' . $cattleId . ',id,status,Active',
            'cattle_type_id' => 'required',
            'father_insemination' => 'required',
            'status' => 'required',
            'parent_id' => 'nullable',
            'purchase_source' => 'required|string|min:1|max:255',
            'purchase_amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'purchase_date' => 'required|date',
            'farm_entry_date' => 'required|date',
            'purchase_image' => ['nullable', 'file'],
            'latest_image' => ['file'],
            'middleman' => 'nullable|string|min:0|max:11',
            'species' => 'required|string|min:1|max:100',
            'date_of_birth' => 'required|date',
            'teeth' => 'required|numeric|min:-2147483648|max:2147483647',
            'expected_sale_price' => 'required|numeric|min:-2147483648|max:2147483647',
            'daily_expense' => 'required|numeric|min:-2147483648|max:2147483647',
            'birth_type' => 'required',
            'comments' => 'nullable',
        ]);

        $data['title'] = clean($request->title);
        $data['purchase_source'] = clean($request->purchase_source);
        $data['middleman'] = clean($request->middleman);
        $data['species'] = clean($request->species);
        $data['comments'] = clean($request->comments);

        

        if ($request->has('custom_delete_purchase_image')) {
            $data['purchase_image'] = null;
        }
        if ($request->hasFile('purchase_image')) {
            $data['purchase_image'] = $this->moveFile($request->file('purchase_image'));
        }
        if ($request->has('custom_delete_latest_image')) {
            $data['latest_image'] = '';
        }
        if ($request->hasFile('latest_image')) {
            $data['latest_image'] = $this->moveFile($request->file('latest_image'));
        }
        
        return $data;
    }

    /**
     * Get the body weight request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getBodyWeightData(Request $request)
    {
        $data = $request->validate([
            'cattle_id' => 'required',
            'weight' => 'required|numeric|min:-2147483648|max:2147483647',
            'date' => 'required|date_format:m/d/Y',
        ]);

        return $data;
    }

    protected function getCostData(Request $request)
    {
        $data = $request->validate([
            'cattle_id' => 'required',
            'cost' => 'required|numeric|min:-2147483648|max:2147483647',
            'date' => 'required|date_format:m/d/Y',
        ]);

        return $data;
    }

    /**
     * Moves the attached file to the server.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }
        $random_string = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36),0,20);
        $file_name     = $random_string.'.'.$file->getClientOriginalExtension();

        return $file->storeAs('uploads',$file_name);
    }
}
