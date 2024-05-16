@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">{{__('breed.breed_list')}}</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.cattle')}}</th>
                <th>{{__('breed.breeding_date')}}</th>
                <th>{{__('breed.breeding_type')}}</th>
                <th>{{__('breed.breeding_status')}}</th>
                <th>{{__('breed.expected_birth_date')}}</th>
                <th class="text-right">{{__('commons.cost')}}</th>
                <th>{{__('breed.aI_worker')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->cattle)->title }}</td>
                        <td>{{ $item->breeding_date }}</td>
                        <td>{{ $item->breeding_type }}</td>
                        <td>{{ $item->breeding_status }}</td>
                        <td>{{ $item->expected_birth_date }}</td>
                        <td class="text-right">{{ $item->cost }}</td>
                        <td>{{ optional($item->creator)->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
