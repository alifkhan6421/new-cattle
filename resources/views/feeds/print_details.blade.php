@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Feed Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.cattle')}}</th>
            <td>{{ optional($feed->cattle)->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.date')}}</th>
            <td>{{ $feed->date }}</td>
        </tr>
        <tr>
            <th>{{__('feed.morning_amount')}}</th>
            <td>{{ $feed->morning_amount }}</td>
        </tr>
        <tr>
            <th>{{__('feed.noon_amount')}}</th>
            <td>{{ $feed->noon_amount }}</td>
        </tr>
        <tr>
            <th>{{__('feed.after_noon_amount')}}</th>
            <td>{{ $feed->after_noon_amount }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $feed->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($feed->creator)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $feed->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $feed->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
