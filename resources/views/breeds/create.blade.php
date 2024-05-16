@extends('layouts.app')

@section('content-header')
    <h1>{{__('breed.create_breed')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('breeds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('breed.breeds')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('breed.create_new_breed')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('breeds.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_breed')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('breeds.store') }}" id="create_breed_form"
              name="create_breed_form" accept-charset="UTF-8">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('breeds.form', ['breed' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('breed.add_breed')}}</button>
            </div>
        </form>
    </div>

@endsection

@section('javascript')
    <script>
        $(function () {
            "use strict";
            $('#breeding_date').datepicker().on('changeDate', function (ev) {
                let date2 = $(this).datepicker('getDate', '+1d');
                date2.setDate(date2.getDate() + {{ config("settings.BIRTH_DURATION") }});
                $('#expected_birth_date').datepicker('setDate', date2);
                $(this).datepicker('hide');
            });
        });

    </script>
@endsection
