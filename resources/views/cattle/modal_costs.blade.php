<div class="row">
    <div class="col-md-8 vl">

        <div class="alert alert-success hidden" id="success-msg-box">
            <span class="glyphicon glyphicon-ok"></span>
            <span id="msgText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="table-responsive data-table">
            @include('cattle.table_costs', ['cattleCosts' => $cattleCosts])
        </div>

    </div>

    <div class="col-md-4">
        <form method="POST" id="formCost" action="{{ route('cattle.storeCost') }}">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="date">{{__('commons.date')}}</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control month-picker pull-right" name="date" id="date" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="cost">{{__('commons.cost')}}</label>
                        <input class="form-control" name="cost" type="number" id="cost"
                               min="-2147483648" max="2147483647" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-save pull-right">{{__('cattle.add_cost')}}</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        "use strict";
        $(".month-picker").datepicker( {
            format: "M, yyyy",
            autoclose: true,
            startView: "months",
            minViewMode: "months"
        });

        $(".btn-save").on('click',function () {
            let formCostObj = $('#formCost');
            formCostObj.validate();
            if (formCostObj.valid()) {
                let costId = $(this).data('cost-id');
                let saveUrl = '{{ route('cattle.storeCost') }}';
                let data = {
                    'cattle_id': '{{ $cattle->id }}',
                    'date': moment('01 ' + $('#date').val()).format('MM/DD/YYYY'),
                    'cost': $('#cost').val(),
                    '_token': '{{ csrf_token() }}'
                };

                if (costId) {
                    saveUrl = '{{ route('cattle.updateCost', ':costId') }}';
                    saveUrl = saveUrl.replace(':costId', costId);
                    data["_method"] = "PUT";
                }

                $.ajax({
                    type: "POST",
                    url: saveUrl,
                    data: data,
                    dataType: "html",
                    beforeSend: function () {
                        if (loaderImageHtml) {
                            $('.data-table').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (jsonString) {
                        let jsonObject = JSON.parse(jsonString);
                        if (jsonObject.status === 'OK') {
                            $('#msgText').html(jsonObject.message);
                            $('#success-msg-box')
                                .removeClass('hidden')
                                .fadeIn(1500)
                                .fadeOut(4500);

                            $('.data-table').html(jsonObject.html);
                            $('#formCost').trigger("reset");

                            let btnSave = $('.btn-save');
                            btnSave.html('Add Cost');
                            btnSave.data('cost-id', '');
                        } else {
                            $('#success-msg-box').addClass('hidden');
                        }
                    }
                });
            }
        });
    })
</script>


