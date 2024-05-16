@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
@endsection

@section('content-header')
    <h1>{{__('cattle.page_title')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('cattle.page_title')}}</a></li>
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

    <div class="row">
        <div class="col-xs-12">

            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('commons.filter_box')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                            <i class="fa fa-expand"></i>
                        </button>
                        <a href="{{ route('cattle.index') }}" class="btn btn-info btn-sm" title="{{__('buttonTitle.reset')}}">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('cattle.create'))
                            <a href="{{ route('cattle.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_cattle')}}">
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
                                    <label for="title" class="control-label col-lg-2">{{__('commons.cattle_id')}}</label>
                                    <div class="col-lg-4">
                                        <input class="form-control" name="title" id="title"
                                               minlength="1" maxlength="50">
                                    </div>

                                    <label for="status" class="control-label col-lg-2">{{__('commons.status')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="status" id="status">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            <option value="Active" selected>{{__('commons.status_active')}}</option>
                                            <option value="Sold">{{__('commons.status_sold')}}</option>
                                            <option value="Died">{{__('commons.status_died')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cattle_type_id" class="control-label col-lg-2">{{__('commons.cattle_type')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="cattle_type_id" id="cattle_type_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($cattleTypes as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label for="parent_id" class="control-label col-lg-2">{{__('commons.parent')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="parent_id" id="parent_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($parents as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="purchase_source" class="control-label col-lg-2">{{__('cattle.purchase_source')}}</label>
                                    <div class="col-lg-4">
                                        <input class="form-control" name="purchase_source" id="purchase_source"
                                               minlength="1" maxlength="50">
                                    </div>

                                    <label for="farm_entry_date" class="control-label col-lg-2">{{__('cattle.farm_entry_date')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control date-range-picker" name="farm_entry_date"
                                                   id="farm_entry_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="purchase_amount" class="control-label col-lg-2">{{__('cattle.purchase_amount')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input name="purchase_amount" id="purchase_amount" class="form-control">
                                            <div class="input-group-btn">
                                                <select name="op_purchase_amount" id="op_purchase_amount"
                                                        class="form-control" aria-label="">
                                                    <option value="1">{{__('commons.equal')}}</option>
                                                    <option value="2">{{__('commons.&more')}}</option>
                                                    <option value="3">{{__('commons.&less')}}</option>
                                                    <option value="4">{{__('commons.between')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <label for="expected_sale_price" class="control-label col-lg-2">
                                       {{__('cattle.expected_sale_price')}}
                                    </label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input name="expected_sale_price" id="expected_sale_price"
                                                   class="form-control">
                                            <div class="input-group-btn">
                                                <select name="op_expected_sale_price" id="op_expected_sale_price"
                                                        class="form-control" aria-label="">
                                                    <option value="1">{{__('commons.equal')}}</option>
                                                    <option value="2">{{__('commons.&more')}}</option>
                                                    <option value="3">{{__('commons.&less')}}</option>
                                                    <option value="4">{{__('commons.between')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="daily_expense" class="control-label col-lg-2">{{__('cattle.daily_expense')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input name="daily_expense" id="daily_expense" class="form-control"
                                                   aria-label="...">
                                            <div class="input-group-btn">
                                                <select name="op_daily_expense" id="op_daily_expense"
                                                        class="form-control" aria-label="">
                                                    <option value="1">{{__('commons.equal')}}</option>
                                                    <option value="2">{{__('commons.&more')}}</option>
                                                    <option value="3">{{__('commons.&less')}}</option>
                                                    <option value="4">{{__('commons.between')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <label for="species" class="control-label col-lg-2">{{__('cattle.species')}}</label>
                                    <div class="col-lg-3">
                                        <input class="form-control" name="species" id="species"
                                               minlength="1" maxlength="50">
                                    </div>

                                    <div class="col-lg-1">
                                        <button type="button" id="btnSearch" class="btn btn-primary pull-right">{{__('commons.go')}}
                                        </button>
                                    </div>


                                </div>

                            </form>

                        </div>

                    </div>

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
                                <th>{{__('commons.cattle_id')}}</th>
                                <th>{{__('commons.cattle_type')}}</th>
                                <th class="text-right">{{__('cattle.purchase_amount')}}</th>
                                <th>{{__('cattle.farm_entry_date')}}</th>
                                <th>{{__('cattle.species')}}</th>
                                <th class="text-right">{{__('commons.wieght_kg')}}</th>
                                <th class="text-right">{{__('commons.cost')}}</th>
                                <th class="text-right">{{__('cattle.expected_sale_price')}}</th>
                                <th class="text-right">{{__('cattle.daily_expense')}}</th>
                                <th>{{__('commons.status')}}</th>
                                <th>{{__('commons.update_at')}}</th>
                                <th class="text-center mw-145">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="searchResults" id="modalCattleWeights"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{__('cattle.body_weight')}}</h4>
                </div>
                <div class="modal-body cattle-weight-list"></div>
            </div>
        </div>
    </div>
@endsection

<!-- page script -->
@section('javascript')
    <script>

        $(function () {
            "use strict";
            let farmEntryDateRangeObj = $('#farm_entry_date');
            let farmEntryStartDate = '';
            let farmEntryEndDate = '';
            let dataTableUrl = '';

            let loadBodyWeightModal = function(cattleId, cattleTitle) {
                let url = '{{ route('cattle.bodyWeight', ':cattleId') }}';
                $.ajax({
                    type: "GET",
                    url: url.replace(':cattleId', cattleId),
                    dataType: "html",
                    beforeSend: function () {
                        $('#modalCattleWeights .modal-title').html('Body Weight for #' + cattleTitle);
                        $('#modalCattleWeights').modal('show');
                        if (loaderImageHtml) {
                            $('#modalCattleWeights .modal-body').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (data) {
                        $('#modalCattleWeights .modal-body').html(data);

                        let script = document.createElement('script');
                        script.src = '{{ asset('js/common.js') }}';
                        script.type = 'text/javascript';
                        document.getElementsByTagName('body')[0].appendChild(script);
                    }
                });
            };

            let loadCostModal = function(cattleId, cattleTitle) {
                let url = '{{ route('cattle.costs', ':cattleId') }}';
                $.ajax({
                    type: "GET",
                    url: url.replace(':cattleId', cattleId),
                    dataType: "html",
                    beforeSend: function () {
                        $('#modalCattleWeights .modal-title').html('Cost for #' + cattleTitle);
                        $('#modalCattleWeights').modal('show');
                        if (loaderImageHtml) {
                            $('#modalCattleWeights .modal-body').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (data) {
                        $('#modalCattleWeights .modal-body').html(data);

                        let script = document.createElement('script');
                        script.src = '{{ asset('js/common.js') }}';
                        script.type = 'text/javascript';
                        document.getElementsByTagName('body')[0].appendChild(script);
                    }
                });
            };

            let dataTable = $('#dataTable').DataTable({
                "order": [[10, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'cattle_type', name: 'cattle_type'},
                    {data: 'purchase_amount', name: 'purchase_amount', className: "text-right"},
                    {data: 'farm_entry_date', name: 'farm_entry_date'},
                    {data: 'species', name: 'species'},
                    {data: 'weight', name: 'weight', className: "text-right"},
                    {data: 'cost', name: 'cost', className: "text-right"},
                    {data: 'expected_sale_price', name: 'expected_sale_price', className: "text-right"},
                    {data: 'daily_expense', name: 'daily_expense', className: "text-right"},
                    {data: 'status', name: 'status'},
                    {data: 'updated_at', name: 'updated_at'},
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
                    {"searchable": false, "orderable": false, "targets": 5},
                    {"searchable": false, "orderable": false, "targets": 6}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('cattle.exportXLSX') }}' + dataTableUrl;
                    });
                },
                drawCallback: function () {
                    $('.btn-cattle-weight').on('click',function () {
                        let cattleId = $(this).data('cattle-id');
                        let cattleTitle = $(this).data('cattle-title');
                        if (cattleId) {
                            loadBodyWeightModal(cattleId, cattleTitle);
                        } else {
                            alertify.error('Please write somethings');
                        }
                    });

                    $('.btn-cattle-cost').on('click',function () {
                        let cattleId = $(this).data('cattle-id');
                        let cattleTitle = $(this).data('cattle-title');
                        if (cattleId) {
                            loadCostModal(cattleId, cattleTitle);
                        } else {
                            alertify.error('Please write somethings');
                        }
                    });
                }
            });

            let dateRangeOptions = {
                opens: 'right',
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

            // daterangepicker for farm entry date
            dateRangeOptions.opens = 'left';
            farmEntryDateRangeObj.daterangepicker(dateRangeOptions);
            farmEntryDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                farmEntryStartDate = picker.startDate.format('YYYY-MM-DD');
                farmEntryEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            farmEntryDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                farmEntryStartDate = '';
                farmEntryEndDate = '';
            });

            let getUrlQueries = function() {
                let parentId = $('#parent_id').val();
                let cattleTypeId = $('#cattle_type_id').val();
                let title = $('#title').val();
                let status = $('#status').val();
                let purchaseSource = $('#purchase_source').val();
                let species = $('#species').val();
                let purchaseAmount = $('#purchase_amount').val();
                let opPurchaseAmount = $('#op_purchase_amount').val();
                let expectedSalePrice = $('#expected_sale_price').val();
                let opExpectedSalePrice = $('#op_expected_sale_price').val();
                let dailyExpense = $('#daily_expense').val();
                let opDailyExpense = $('#op_daily_expense').val();

                return '?parentId=' + parentId
                    + '&cattleTypeId=' + cattleTypeId
                    + '&title=' + title
                    + '&status=' + status
                    + '&purchaseSource=' + purchaseSource
                    + '&species=' + species
                    + '&farmEntryStartDate=' + farmEntryStartDate
                    + '&farmEntryEndDate=' + farmEntryEndDate
                    + '&purchaseAmount=' + purchaseAmount
                    + '&opPurchaseAmount=' + opPurchaseAmount
                    + '&expectedSalePrice=' + expectedSalePrice
                    + '&opExpectedSalePrice=' + opExpectedSalePrice
                    + '&dailyExpense=' + dailyExpense
                    + '&opDailyExpense=' + opDailyExpense;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('cattle.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
