@if(count($cattleCosts) == 0)
    <div class="panel-body text-center">
        <h4>No Costs Available!</h4>
    </div>
@else
    <table id="dataTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{__('commons.sl')}}</th>
            <th>{{__('commons.date')}}</th>
            <th class="text-right">{{__('commons.cost')}}</th>
            <th class="text-center">{{__('commons.action')}}</th>
        </tr>
        </thead>
        <tbody>
        @php($serial = 1)
        @foreach($cattleCosts as $cattleCost)
            <tr>
                <td>{{ $serial++ }}</td>
                <td>{{ date('M, Y', strtotime($cattleCost->date)) }}</td>
                <td class="text-right">{{ $cattleCost->cost }}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-primary btn-edit"
                            data-cost-id="{{ $cattleCost->id }}"
                            data-date="{{ date('M, Y', strtotime($cattleCost->date)) }}"
                            data-cost="{{ $cattleCost->cost }}"
                            title="Update Cattle Cost">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </button>
                    @if (App\Helpers\CommonHelper::isCapable('cattle.destroyCost'))
                        <button type="button" class="btn btn-xs btn-danger btn-delete"
                                data-cost-id="{{ $cattleCost->id }}" title="Delete Cattle Cost">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2"></th>
            <th class="text-right">{{ $totalCost }}</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
@endif

<script>
    $(document).ready(function () {
        "use strict";
        $(".btn-edit").on('click',function () {
            let dateObj = $('#date');
            let costObj = $('#cost');
            let btnSave = $('.btn-save');
            let costId = $(this).data('cost-id');
            let date = $(this).data('date');
            let cost = $(this).data('cost');
            dateObj.val(date);
            costObj.val(cost);
            btnSave.html('Update');
            btnSave.data('cost-id', costId);
        });

        $(".btn-delete").on('click',function () {
            if(confirm("Delete cattle cost?")) {
                let url = '{{ route('cattle.destroyCost', ':costId') }}';
                let costId = $(this).data('cost-id');
                if (costId) {
                    $.ajax({
                        type: "POST",
                        url: url.replace(':costId', costId),
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
                                $('#msgText').html(jsonObject.message);
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
