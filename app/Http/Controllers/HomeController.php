<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Breed;
use App\Models\Cattle;
use App\Models\CattleType;
use App\Models\Inventory;
use App\Models\Ledger;
use App\Models\Medicine;
use App\Models\Milk;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['page_title'] = 'Page Title';
        $data['page_description'] = '';
        $data['tasks'] = [
            [
                'name' => 'Design New Dashboard',
                'progress' => '87',
                'color' => 'danger'
            ],
            [
                'name' => 'Create Home Page',
                'progress' => '76',
                'color' => 'warning'
            ],
            [
                'name' => 'Some Other Task',
                'progress' => '32',
                'color' => 'success'
            ],
            [
                'name' => 'Start Building Website',
                'progress' => '56',
                'color' => 'info'
            ],
            [
                'name' => 'Develop an Awesome Algorithm',
                'progress' => '10',
                'color' => 'success'
            ]
        ];

        return view('home')->with($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return RedirectResponse | Redirector
     */
    public function dashboard()
    {

        $totalCattle = Cattle::all()->count();
        $totalIncome = Ledger::where('type', 'Income')->sum('amount');
        $totalExpense = Ledger::where('type', 'Expense')->sum('amount');
        $totalInventory = Inventory::all()->count();

        $data['totalCattle'] = CommonHelper::numberFormatIndia($totalCattle);
        $data['totalIncome'] = CommonHelper::numberFormatIndia($totalIncome);
        $data['totalExpense'] = CommonHelper::numberFormatIndia($totalExpense);
        $data['totalInventory'] = CommonHelper::numberFormatIndia($totalInventory);

        // Bar chart data
        $barChartLabel = '';
        $monthlyIncomes = [];
        $monthlyExpenses = [];
        for ($i = 5; $i > -1; $i--) {
            if ($barChartLabel) {
                $barChartLabel .= ',';
            }
            $barChartLabel .= '"' . date('F', strtotime("-$i month")) . '"';

            $month = date('Y-m', strtotime("-$i month"));
            $monthlyIncomes[] = Ledger::where('type', 'Income')
                ->where('date', 'like', '%' . $month . '%')
                ->sum('amount');

            $monthlyExpenses[] = Ledger::where('type', 'Expense')
                ->where('date', 'like', '%' . $month . '%')
                ->sum('amount');
        }

        $data['barChartLabel'] = $barChartLabel;
        $data['monthlyIncomes'] = implode(',', $monthlyIncomes);
        $data['monthlyExpenses'] = implode(',', $monthlyExpenses);

        // Pie chart data
        $cnt = 0;
        $pieChart = [];
        $colors = [
            '#f56954',
            '#00a65a',
            '#f39c12',
            '#00c0ef',
            '#3c8dbc',
            '#d2d6de',
            '#5E85F3',
            '#F5A8C1',
            '#82A69D',
            '#F3CCC5',
            '#BAEFEC',
            '#DEB0DE'
        ];
        $cattleTypes = CattleType::where('status', 'Active')
            ->withCount('cattle')
            ->orderBy('cattle_count', 'DESC')
            ->get();
        foreach ($cattleTypes as $cattleType) {
            $color = $colors[$cnt % 12];
            $cattleType->color = $color;
            $pieChart[] = (object) [
                'value' => $cattleType->cattle_count,
                'color' => $color,
                'highlight' => $color,
                'label' => htmlentities($cattleType->title)
            ];

            $cnt++;
        }
        

        $data['pieChart'] = json_encode($pieChart);
        $data['cattleTypes'] = $cattleTypes;

        $data['cattle'] = Cattle::orderBy('purchase_date', 'DESC')->take(8)->get();

        $dateBefore30 = date('Y-m-d', strtotime('today - 30 days'));
        $data['latestCattle'] = Cattle::where('purchase_date', '>', $dateBefore30)->count();

        // medicine taken cattle params
        $today = date('Y-m-d');
        $medicineTakenCattle = Medicine::where(function ($query) use ($dateBefore30, $today) {
                $query->whereBetween('end_date', [$dateBefore30, $today]);
            })
            ->orWhere(function ($query) use ($dateBefore30, $today) {
                $query->whereBetween('start_date', [$dateBefore30, $today]);
            })
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $dateBefore60 = date('Y-m-d', strtotime('today - 60 days'));
        $medicineTakenCattleBefore = Medicine::where(function ($query) use ($dateBefore60, $dateBefore30) {
                $query->whereBetween('end_date', [$dateBefore60, $dateBefore30]);
            })
            ->orWhere(function ($query) use ($dateBefore60, $dateBefore30) {
                $query->whereBetween('start_date', [$dateBefore60, $dateBefore30]);
            })
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $data['medicineTakenCattle'] = CommonHelper::numberFormatIndia($medicineTakenCattle);
        $data['medicineTakenPercent'] = ceil($medicineTakenCattle / $totalCattle * 100);
        $data['mtUpDownPercent'] = CommonHelper::getComparePercent($medicineTakenCattle, $medicineTakenCattleBefore);

        // Milk giving cattle
        $milkGivingCattle = Milk::whereBetween('date', [$dateBefore30, $today])
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $milkGivingCattleBefore = Milk::whereBetween('date', [$dateBefore60, $dateBefore30])
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $data['milkGivingCattle'] = CommonHelper::numberFormatIndia($milkGivingCattle);
        $data['milkGivingPercent'] = ceil($milkGivingCattle / $totalCattle * 100);
        $data['mgUpDownPercent'] = CommonHelper::getComparePercent($milkGivingCattle, $milkGivingCattleBefore);

        // Expected birth cattle
        $expectedBirthCattle = Breed::whereBetween('expected_birth_date', [$dateBefore30, $today])
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $expectedBirthCattleBefore = Breed::whereBetween('expected_birth_date', [$dateBefore60, $dateBefore30])
            ->get()
            ->groupBy('cattle_id')
            ->count();

        $data['expectedBirthCattle'] = CommonHelper::numberFormatIndia($expectedBirthCattle);
        $data['expectedBirthPercent'] = ceil($expectedBirthCattle / $totalCattle * 100);
        $data['ebUpDownPercent'] = CommonHelper::getComparePercent($expectedBirthCattle, $expectedBirthCattleBefore);

        // Milk amount
        $milkAmount = Milk::whereBetween('date', [$dateBefore30, $today])
            ->get()
            ->sum(function ($row) {
                return $row->morning_amount + $row->noon_amount + $row->after_noon_amount;
            });

        $milkAmountBefore = Milk::whereBetween('date', [$dateBefore60, $dateBefore30])
            ->get()
            ->sum(function ($row) {
                return $row->morning_amount + $row->noon_amount + $row->after_noon_amount;
            });

        $data['milkAmount'] = CommonHelper::numberFormatIndia($milkAmount);
        $data['milkAmountPercent'] = ceil($milkAmount / $totalCattle * 100);
        $data['maUpDownPercent'] = CommonHelper::getComparePercent($milkAmount, $milkAmountBefore);

        return view('dashboard')->with($data);
    }

    public function media()
    {
        return view('media');
    }
}
