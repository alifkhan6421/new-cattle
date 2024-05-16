@extends('layouts.app')

@section('content-header')
    <h1>{{__('feed.feed_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('feeds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('feed.feeds')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Feed' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('feeds.destroy', $feed->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('feeds.index'))
                        <a href="{{ route('feeds.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_feed')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('feeds.printDetails'))
                        <a href="{{ route('feeds.printDetails', $feed->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('feeds.create'))
                        <a href="{{ route('feeds.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_feed')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('feeds.edit'))
                        <a href="{{ route('feeds.edit', $feed->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_feed')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('feeds.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_feed')}}"
                                onclick="return confirm('Delete Feed?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
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
                        <th>{{__('feed.morning_amount')}} (Kg)</th>
                        <td>{{ $feed->morning_amount }}</td>
                    </tr>
                    <tr>
                        <th>{{__('feed.noon_amount')}} (Kg)</th>
                        <td>{{ $feed->noon_amount }}</td>
                    </tr>
                    <tr>
                        <th>{{__('feed.after_noon_amount')}} (Kg)</th>
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
            </div>
        </div>
    </div>

@endsection
