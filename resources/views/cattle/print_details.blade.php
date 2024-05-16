@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('cattle.cattle_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.cattle_id')}}</th>
            <td>{{ $cattle->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.status')}}</th>
            <td>{{ $cattle->status }}</td>
        </tr>
        <tr>
            <th>{{__('commons.cattle_type')}}</th>
            <td>{{ optional($cattle->cattleType)->title }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.father_insemination')}}</th>
            <td>{{ $cattle->father_insemination }}</td>
        </tr>
        <tr>
            <th>{{__('commons.parent')}}</th>
            <td>{{ optional($cattle->Cattle)->title }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.purchase_source')}}</th>
            <td>{{ $cattle->purchase_source }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.purchase_amount')}}</th>
            <td>{{ $cattle->purchase_amount }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.purchase_date')}}</th>
            <td>{{ $cattle->purchase_date }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.farm_entry_date')}}</th>
            <td>{{ $cattle->farm_entry_date }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.middleman')}}</th>
            <td>{{ $cattle->middleman }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.species')}}</th>
            <td>{{ $cattle->species }}</td>
        </tr>
        <tr>
            <th>{{__('commons.dob')}}</th>
            <td>{{ $cattle->date_of_birth }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.teeth')}}</th>
            <td>{{ $cattle->teeth }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.expected_sale_price')}}</th>
            <td>{{ $cattle->expected_sale_price }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.daily_expense')}}</th>
            <td>{{ $cattle->daily_expense }}</td>
        </tr>
        <tr>
            <th>{{__('cattle.birth_type')}}</th>
            <td>{{ $cattle->birth_type }}</td>
        </tr>
        <tr>
            <th>Purchase Image</th>
            <td>
                @if (!empty($cattle->purchase_image))
                    <img src="{{ asset('storage/' . $cattle->purchase_image) }}"
                         alt="Purchase Image" class="thumbnail medium"/>
                @endif
            </td>
        </tr>
        <tr>
            <th>Latest Image</th>
            <td>
                @if (!empty($cattle->latest_image))
                <img src="{{ asset('storage/' . $cattle->latest_image) }}"
                     alt="Purchase Image" class="thumbnail medium"/>
                @endif
            </td>
        </tr>
        <tr>
            <th>{{__('cattle.body_weights')}}</th>
            <td>
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('commons.date')}}</th>
                            <th class="text-right">Weight</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cattleWeights as $cattleWeight)
                            <tr>
                                <td>{{ $cattleWeight->date }}</td>
                                <td class="text-right">{{ $cattleWeight->weight }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </td>
        </tr>
        <tr>
            <th>{{__('cattle.body_costs')}}</th>
            <td>
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('commons.date')}}</th>
                            <th class="text-right">{{__('commons.cost')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cattleCosts as $cattleCost)
                            <tr>
                                <td>{{ date('M, Y', strtotime($cattleCost->date)) }}</td>
                                <td class="text-right">{{ $cattleCost->cost }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $cattle->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($cattle->creator)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $cattle->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $cattle->updated_at }}</td>
        </tr>
        </tbody>
    </table>

@endsection
