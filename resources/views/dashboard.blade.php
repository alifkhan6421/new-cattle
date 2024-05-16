@extends('layouts.app')

@section('content-header')
    <h1>{{__('info.dashboard')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('info.home')}}</a></li>
        <li class="active">{{__('info.dashboard')}}</li>
    </ol>
@endsection

@section('content')

    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('cattle.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-paw"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_cattle')}}</span>
                        <span class="info-box-number">{{ $totalCattle }}</span>
                    </div>
                </div>
            </a>
        </div>

        @if (App\Helpers\CommonHelper::isCapable('dashboard.reportTotalIncome'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('ledgers.index') }}" class="info-link">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{__('info.total_income')}}</span>
                            <span class="info-box-number">{{ $totalIncome }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

    <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        @if (App\Helpers\CommonHelper::isCapable('dashboard.reportTotalExpense'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('ledgers.index') }}" class="info-link">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{__('info.total_expense')}}</span>
                            <span class="info-box-number">{{ $totalExpense }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('inventories.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-cubes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_inventories')}}</span>
                        <span class="info-box-number">{{ $totalInventory }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row equal padding-bottom-15">
        @if (App\Helpers\CommonHelper::isCapable('dashboard.reportIncomeExpenseChart'))
            <div class="col-md-7">
                <!-- BAR CHART -->
                <div class="box box-success h-100">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('info.monthly_Income_expense_report')}}</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChart" class="h-280"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        @endif

        <div class="col-md-5">
            <div class="box box-default h-100">

                <div class="box-header with-border">
                    <h3 class="box-title">{{__('info.cattle_by_types')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="pieChart" height="260"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                @foreach($cattleTypes as $cattleType)
                                    <li>
                                        <i class="fa fa-circle-o" style="color: {!! $cattleType->color !!}"></i>
                                        {{ $cattleType->title }} ({{ $cattleType->cattle_count }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-7">
            <!-- USERS LIST -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('info.latest_cattle')}}</h3>

                    <div class="box-tools pull-right">
                        @if ($latestCattle)
                            <span class="label label-danger">{{ $latestCattle }} New Cattle</span>
                        @endif
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                        @foreach($cattle as $cow)
                            <li>
                                @if (!empty($cow->latest_image))
                                    <a href="{{ route('cattle.show', $cow->id) }}">
                                        <img src="{{ asset('storage/'. $cow->latest_image) }}"
                                             class="medium-rectangle" alt="Cattle Image">
                                    </a>
                                @else
                                    <a href="{{ route('cattle.show', $cow->id) }}">
                                        <img src="{{ asset('storage/sites/cattle-not-found.png') }}"
                                             class="medium-rectangle" alt="Cattle Image">
                                    </a>
                                @endif
                                <a class="users-list-name" href="{{ route('cattle.show', $cow->id) }}">
                                    {{ $cow->title }}
                                </a>
                                <span class="users-list-date">
                                    {{ App\Helpers\CommonHelper::displayTimeFormat($cow->purchase_date, true) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="box-footer text-center">
                    <a href="{{ route('cattle.index') }}" class="uppercase">{{__('info.view_all_cattle')}}</a>
                </div>
            </div>

        </div>

        <div class="col-md-5">
            <!-- Info Boxes Style 2 -->
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-medkit"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{__('info.total_sick_cattle')}}</span>
                    <span class="info-box-number">{{ $medicineTakenCattle }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $medicineTakenPercent }}%"></div>
                    </div>
                    <span class="progress-description">
                        {{ $mtUpDownPercent }} {{__('info.in_30_days')}}
                    </span>
                </div>
            </div>

            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-tint"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{__('info.total_milk_giving_cattle')}}</span>
                    <span class="info-box-number">{{ $milkGivingCattle }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $milkGivingPercent }}%"></div>
                    </div>
                    <span class="progress-description">
                        {{ $mgUpDownPercent }} {{__('info.in_30_days')}}
                      </span>
                </div>
            </div>

            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-crosshairs"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{__('info.total_expected_birth_cattle')}}</span>
                    <span class="info-box-number">{{ $expectedBirthCattle }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $expectedBirthPercent }}%"></div>
                    </div>
                    <span class="progress-description">
                        {{ $ebUpDownPercent }} {{__('info.in_30_days')}}
                      </span>
                </div>
            </div>

            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-tint"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{__('info.total_amount_of_milks')}}</span>
                    <span class="info-box-number">{{ $milkAmount }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $milkAmountPercent }}%"></div>
                    </div>
                    <span class="progress-description">
                        {{ $maUpDownPercent }} {{__('info.in_30_days')}}
                      </span>
                </div>
            </div>
        </div>

    </div>

@endsection

<!-- page script -->
@section('javascript')
    <script>
        let barChartLabel = [{!! $barChartLabel !!}];
        let monthlyIncomes = [{{ $monthlyIncomes }}];
        let monthlyExpenses = [{{ $monthlyExpenses }}];
        let pieChartData = JSON.parse('{!! $pieChart !!}');
    </script>
    <script src="{{ asset('chart.js/Chart.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
