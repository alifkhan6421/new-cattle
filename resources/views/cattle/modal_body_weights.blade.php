<div class="row">
    <div class="col-md-8 vl">

        <div class="alert alert-success hidden" id="success-msg-box">
            <span class="glyphicon glyphicon-ok"></span>
            <span id="bodyWeightMessage"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="table-responsive data-table">
            @include('cattle.table_body_weights', ['cattleWeights' => $cattleWeights])
        </div>

    </div>

    <div class="col-md-4">
        <form method="POST" id="formBodyWeight" action="{{ route('cattle.storeBodyWeight') }}">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="date">{{__('commons.date')}}</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control datepicker pull-right" name="date" id="date"
                                   placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="weight">{{__('commons.wieght_kg')}}</label>
                        <input class="form-control" name="weight" type="number" id="weight"
                               min="-2147483648" max="2147483647" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-save pull-right">{{__('cattle.add_body_weight')}}</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        "use strict";
        $(".btn-save").on('click',function () {
            let formBodyWeightObj = $('#formBodyWeight');
            formBodyWeightObj.validate();
            if (formBodyWeightObj.valid()) {
                let weightId = $(this).data('weight-id');
                let saveUrl = '{{ route('cattle.storeBodyWeight') }}';
                let data = {
                    'cattle_id': '{{ $cattle->id }}',
                    'date': moment($('#date').val()).format('MM/DD/YYYY'),
                    'weight': $('#weight').val(),
                    '_token': '{{ csrf_token() }}'
                };

                if (weightId) {
                    saveUrl = '{{ route('cattle.updateBodyWeight', ':weightId') }}';
                    saveUrl = saveUrl.replace(':weightId', weightId);
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
                            $('#bodyWeightMessage').html(jsonObject.message);
                            $('#success-msg-box')
                                .removeClass('hidden')
                                .fadeIn(1500)
                                .fadeOut(4500);

                            $('.data-table').html(jsonObject.html);
                            $('#formBodyWeight').trigger("reset");

                            let btnSave = $('.btn-save');
                            btnSave.html('Add Body Weight');
                            btnSave.data('weight-id', '');
                        } else {
                            $('#success-msg-box').addClass('hidden');
                        }
                    }
                });
            }
        });
    })
</script>


