@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Breed Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.cattle')}}</th>
            <td>{{ optional($breed->cattle)->title }}</td>
        </tr>
        <tr>
            <th>{{__('breed.breeding_date')}}</th>
            <td>{{ $breed->breeding_date }}</td>
        </tr>
        <tr>
            <th>{{__('breed.breeding_type')}}</th>
            <td>{{ $breed->breeding_type }}</td>
        </tr>
        <tr>
            <th>{{__('breed.breeding_status')}}</th>
            <td>{{ $breed->breeding_status }}</td>
        </tr>
        <tr>
            <th>{{__('breed.expected_birth_date')}}</th>
            <td>{{ $breed->expected_birth_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.cost')}}</th>
            <td>{{ $breed->cost }}</td>
        </tr>
        <tr>
            <th>{{__('breed.aI_worker')}}</th>
            <td>{{ optional($breed->aiWorker)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $breed->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($breed->creator)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $breed->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $breed->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
