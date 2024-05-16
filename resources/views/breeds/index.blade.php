@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('breed.breeds')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('breed.breeds')}}</a></li>
        <li class="active">{{__('commons.listing')}}</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">{{__('commons.filter_box')}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                    <i class="fa fa-expand"></i>
                </button>
                <a href="{{ route('breeds.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                </a>
                @if (App\Helpers\CommonHelper::isCapable('breeds.create'))
                    <a href="{{ route('breeds.create') }}"
                       class="btn btn-sm btn-success"
                       title="{{__('buttonTitle.create_new_breed')}}">
                        <i class="fa fa-plus"></i> {{__('commons.create')}}
                    </a>
                @endif
            </div>
        </div>

        <div class="box-body d-none">
            <div class="row">
                <div class="col-xs-12">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="cattle_id" class="control-label col-lg-2">{{__('commons.cattle')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="cattle_id" id="cattle_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($cattle as $key => $title)
                                        <option value="{{ $key }}">
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="ai_worker_id" class="control-label col-lg-2">{{__('breed.aI_worker')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="ai_worker_id" id="ai_worker_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($aiWorkers as $key => $title)
                                        <option value="{{ $key }}">
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="breeding_date" class="control-label col-lg-2">{{__('breed.breeding_date')}}</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="breeding_date"
                                           type="text"
                                           id="breeding_date">
                                </div>
                            </div>

                            <label for="expected_birth_date" class="control-label col-lg-2">
                                {{__('breed.expected_birth_date')}}
                            </label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="expected_birth_date"
                                           type="text"
                                           id="expected_birth_date">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="breeding_type" class="control-label col-lg-2">{{__('breed.breeding_type')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="breeding_type" id="breeding_type">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    <option value="Artificial Insemination">{{__('breed.artificial_insemination')}}</option>
                                    <option value="Natural Insemination">{{__('breed.natural_insemination')}}</option>
                                </select>
                            </div>

                            <label for="breeding_status" class="control-label col-lg-2">{{__('breed.breeding_status')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="breeding_status" id="breeding_status">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    <option value="Observation">{{__('breed.observation')}}</option>
                                    <option value="Success">{{__('breed.success')}}</option>
                                    <option value="Failed">{{__('breed.failed')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cost" class="control-label col-lg-2">Cost</label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input name="cost" id="cost" class="form-control">
                                    <div class="input-group-btn">
                                        <select name="op_cost" id="op_cost"
                                                class="form-control" aria-label="">
                                            <option value="1">{{__('commons.equal')}}</option>
                                            <option value="2">{{__('commons.&more')}}</option>
                                            <option value="3">{{__('commons.&less')}}</option>
                                            <option value="4">{{__('commons.between')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1 pull-right">
                                <button type="button" id="btnSearch" class="btn btn-primary pull-right">
                                    {{__('commons.go')}}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div class="table-responsive hide-quick-search">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
                                <th>{{__('commons.cattle')}}</th>
                                <th>{{__('breed.breeding_date')}}</th>
                                <th>{{__('breed.breeding_type')}}</th>
                                <th>{{__('breed.breeding_status')}}</th>
                                <th>{{__('breed.expected_birth_date')}}</th>
                                <th class="text-right">{{__('commons.cost')}}</th>
                                <th>{{__('breed.aI_worker')}}</th>
                                <th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

<!-- page script -->
@section('javascript')
    <script>

        $(function () {
            "use strict";
            let breedingDateRangeObj = $('#breeding_date');
            let breedingStartDate = '';
            let breedingEndDate = '';

            let expectedBirthDateRangeObj = $('#expected_birth_date');
            let expectedBirthStartDate = '';
            let expectedBirthEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[5, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'cattle', name: 'cattle'},
                    {data: 'breeding_date', name: 'breeding_date'},
                    {data: 'breeding_type', name: 'breeding_type'},
                    {data: 'breeding_status', name: 'breeding_status'},
                    {data: 'expected_birth_date', name: 'expected_birth_date'},
                    {data: 'cost', name: 'cost', className: "text-right"},
                    {data: 'ai_worker', name: 'ai_worker'},
                    {
                        data: 'action',
                        name: 'action',
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                "pageLength": 10,
                "pagination": true,
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('breeds.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

            let dateRangeOptions = {
                opens: 'left',
                showDropdowns: true,
                linkedCalendars: false,
                autoUpdateInput: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    cancelLabel: 'Clear'
                }
            };

            // daterangepicker for purchase date
            dateRangeOptions.opens = 'right';
            breedingDateRangeObj.daterangepicker(dateRangeOptions);
            breedingDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                breedingStartDate = picker.startDate.format('YYYY-MM-DD');
                breedingEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            breedingDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                breedingStartDate = '';
                breedingEndDate = '';
            });

            // daterangepicker for purchase date
            dateRangeOptions.opens = 'left';
            expectedBirthDateRangeObj.daterangepicker(dateRangeOptions);
            expectedBirthDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                expectedBirthStartDate = picker.startDate.format('YYYY-MM-DD');
                expectedBirthEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            expectedBirthDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                expectedBirthStartDate = '';
                expectedBirthEndDate = '';
            });

            let getUrlQueries = function() {
                let cattleId = $('#cattle_id').val();
                let aiWorkerId = $('#ai_worker_id').val();
                let breedingType = $('#breeding_type').val();
                let breedingStatus = $('#breeding_status').val();
                let cost = $('#cost').val();
                let opCost = $('#op_cost').val();

                return '?breedingStartDate=' + breedingStartDate
                    + '&breedingEndDate=' + breedingEndDate
                    + '&expectedBirthStartDate=' + expectedBirthStartDate
                    + '&expectedBirthEndDate=' + expectedBirthEndDate
                    + '&cattleId=' + cattleId
                    + '&aiWorkerId=' + aiWorkerId
                    + '&breedingType=' + breedingType
                    + '&breedingStatus=' + breedingStatus
                    + '&cost=' + cost
                    + '&opCost=' + opCost;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('breeds.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
