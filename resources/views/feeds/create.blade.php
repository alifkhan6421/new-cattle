@extends('layouts.app')

@section('content-header')
    <h1>{{__('feed.create_feed')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('feeds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('feed.feeds')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('feed.create_new_feed')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('feeds.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_feed')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('feeds.store') }}" id="create_feed_form"
              name="create_feed_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('feeds.form', ['feed' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('feed.add_feed')}}</button>
            </div>
        </form>
    </div>

@endsection