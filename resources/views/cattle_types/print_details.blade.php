@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('cattle.c_type_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.title')}}</th>
            <td>{{ $cattleType->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.status')}}</th>
            <td>{{ $cattleType->status }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $cattleType->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $cattleType->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
