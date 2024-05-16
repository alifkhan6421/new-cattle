@if(count($cattleWeights) == 0)
    <div class="panel-body text-center">
        <h4>No Body Weights Available!</h4>
    </div>
@else
    <table id="dataTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{__('commons.sl')}}</th>
            <th>{{__('commons.date')}}</th>
            <th class="text-right">{{__('commons.weight')}}</th>
            <th class="text-center">{{__('commons.action')}}</th>
        </tr>
        </thead>
        <tbody>
        @php($serial = 1)
        @foreach($cattleWeights as $cattleWeight)
            <tr>
                <td>{{ $serial++ }}</td>
                <td>{{ $cattleWeight->date }}</td>
                <td class="text-right">{{ $cattleWeight->weight }}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-primary btn-edit"
                            data-weight-id="{{ $cattleWeight->id }}"
                            data-date="{{ $cattleWeight->date }}"
                            data-weight="{{ $cattleWeight->weight }}"
                            title="Update Cattle Weight">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </button>
                    @if (App\Helpers\CommonHelper::isCapable('cattle_weights.destroy'))
                        <button type="button" class="btn btn-xs btn-danger btn-delete"
                                data-body-weight-id="{{ $cattleWeight->id }}" title="Delete Cattle Weight">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<script>
    $(document).ready(function () {
        "use strict";
        $(".btn-edit").on('click',function () {
            let dateObj = $('#date');
            let weightObj = $('#weight');
            let btnSave = $('.btn-save');
            let weightId = $(this).data('weight-id');
            let date = $(this).data('date');
            let weight = $(this).data('weight');
            dateObj.val(date);
            weightObj.val(weight);
            btnSave.html('Update');
            btnSave.data('weight-id', weightId);
        });

        $(".btn-delete").on('click',function () {
            if(confirm("Delete Cattle Weight?")) {
                let url = '{{ route('cattle.destroyBodyWeight', ':bodyWeightId') }}';
                let bodyWeightId = $(this).data('body-weight-id');
                if (bodyWeightId) {
                    $.ajax({
                        type: "POST",
                        url: url.replace(':bodyWeightId', bodyWeightId),
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
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
                            } else {
                                $('#success-msg-box').addClass('hidden');
                            }
                        }
                    });
                }
            }
        });
    })
</script>
