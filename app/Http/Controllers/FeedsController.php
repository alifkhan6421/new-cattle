<?php

namespace App\Http\Controllers;

use App\Exports\FeedExport;
use App\Helpers\CommonHelper;
use App\Models\Feed;
use App\Models\Cattle;
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

class FeedsController extends Controller
{
    /**
     * Display a listing of the feeds.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->feedList($request);
        }

        $feeds = Feed::with('cattle','creator')->get();
        $cattle = Cattle::whereHas('cattleType', function ($q) {
            $q->where('title', 'Cow');
        })->orderBy('id', 'DESC')->pluck('title','id');
        return view('feeds.index', compact('feeds', 'cattle'));
    }

    public function getQuery($request)
    {
        $query = Feed::query()->with('cattle', 'creator');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('date', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->cattleId)) {
            $query->where('cattle_id', $request->cattleId);
        }

        if (isset($request->morningAmount) && !empty($request->opMorningAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'morning_amount',
                $request->opMorningAmount,
                $request->morningAmount
            );
        }

        if (isset($request->noonAmount) && !empty($request->opNoonAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'noon_amount',
                $request->opNoonAmount,
                $request->noonAmount
            );
        }

        if (isset($request->afterNoonAmount) && !empty($request->opAfterNoonAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'after_noon_amount',
                $request->opAfterNoonAmount,
                $request->afterNoonAmount
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
    public function feedList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($feed) {
                return view('feeds.action', compact('feed'));
            })
            ->editColumn('cattle', function ($feed) {
                return optional($feed->cattle)->title;
            })
            ->editColumn('created_by', function ($feed) {
                return optional($feed->creator)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new FeedExport($this->getQuery($request)),
            'feed-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $feed = Feed::with('cattle','creator')->findOrFail($id);
        $view = view('feeds.print_details', compact('feed'));
        CommonHelper::generatePdf($view->render(), 'Feed-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new feed.
     *
     * @return View
     */
    public function create()
    {
        $cattle = Cattle::whereHas('cattleType', function ($q) {
            $q->where('title', 'Cow');
        })->orderBy('id', 'DESC')->pluck('title','id');
        return view('feeds.create', compact('cattle'));
    }

    /**
     * Store a new feed in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Feed::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'feeds.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('feeds.index')
                             ->with('success_message', __('message.feed_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified feed.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $feed = Feed::with('cattle','creator')->findOrFail($id);
        return view('feeds.show', compact('feed'));
    }

    /**
     * Show the form for editing the specified feed.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $feed = Feed::findOrFail($id);
        $cattle = Cattle::all()->pluck('title','id');
        return view('feeds.edit', compact('feed','cattle'));
    }

    /**
     * Update the specified feed in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $feed = Feed::findOrFail($id);
            $oldData = $feed->toArray();
            $feed->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'feeds.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('feeds.index')
                             ->with('success_message', __('message.feed_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' =>  $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified feed from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $feed = Feed::findOrFail($id);
            $oldData = $feed->toArray();
            $feed->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'feeds.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('feeds.index')
                             ->with('success_message', __('message.feed_was_successfully_deleted'));
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
            'date' => 'required|date',
            'morning_amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'noon_amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'after_noon_amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'comments' => 'nullable',
        ]);
        $data['comments']  = clean($request->comments);
        return $data;
    }
}
