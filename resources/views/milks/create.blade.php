@extends('layouts.app')

@section('content-header')
    <h1>{{__('milk.create_milk')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('milks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('milk.milks')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('milk.create_new_milk')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('milks.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_milk')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('milks.store') }}" id="create_milk_form"
              name="create_milk_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('milks.form', ['milk' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('milk.add_milk')}}</button>
            </div>
        </form>
    </div>

@endsection