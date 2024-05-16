@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Milk List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.cow')}}</th>
                <th>{{__('commons.date')}}</th>
                <th class="text-right">{{__('milk.morning_amount')}}</th>
                <th class="text-right">{{__('milk.noon_amount')}}</th>
                <th class="text-right">{{__('milk.after_noon_amount')}}</th>
                <th>{{__('commons.created_by')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->cattle)->title }}</td>
                        <td>{{ $item->date }}</td>
                        <td class="text-right">{{ $item->morning_amount }}</td>
                        <td class="text-right">{{ $item->noon_amount }}</td>
                        <td class="text-right">{{ $item->after_noon_amount }}</td>
                        <td>{{ optional($item->creator)->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
