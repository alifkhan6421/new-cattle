@extends('layouts.print')

@section('css')
    <style>
        body {
            font-size: 13px !important;
        }
    </style>
@endsection

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Cattle List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.cattle_id')}}</th>
                <th>{{__('commons.cattle_type')}}</th>
                <th>{{__('cattle.father_insemination')}}</th>
                <th class="text-right">{{__('cattle.purchase_amount')}}</th>
                <th>{{__('cattle.purchase_date')}}</th>
                <th>{{__('cattle.farm_entry_date')}}</th>
                <th>{{__('cattle.species')}}</th>
                <th>{{__('commons.dob')}}</th>
                <th class="text-right">{{__('cattle.teeth')}}</th>
                <th class="text-right">{{__('cattle.expected_sale_price')}}</th>
                <th class="text-right">{{__('cattle.daily_expense')}}</th>
                <th>{{__('cattle.birth_type')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ optional($item->cattleType)->title }}</td>
                        <td>{{ $item->father_insemination }}</td>
                        <td class="text-right">{{ $item->purchase_amount }}</td>
                        <td>{{ $item->purchase_date }}</td>
                        <td>{{ $item->farm_entry_date }}</td>
                        <td>{{ $item->species }}</td>
                        <td>{{ $item->date_of_birth }}</td>
                        <td class="text-right">{{ $item->teeth }}</td>
                        <td class="text-right">{{ $item->expected_sale_price }}</td>
                        <td class="text-right">{{ $item->daily_expense }}</td>
                        <td>{{ $item->birth_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
