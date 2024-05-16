@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Milk Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.cattle')}}</th>
            <td>{{ optional($milk->cattle)->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.date')}}</th>
            <td>{{ $milk->date }}</td>
        </tr>
        <tr>
            <th>{{__('milk.morning_amount')}}</th>
            <td>{{ $milk->morning_amount }}</td>
        </tr>
        <tr>
            <th>{{__('milk.noon_amount')}}</th>
            <td>{{ $milk->noon_amount }}</td>
        </tr>
        <tr>
            <th>{{__('milk.after_noon_amount')}}</th>
            <td>{{ $milk->after_noon_amount }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $milk->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($milk->creator)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $milk->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $milk->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
