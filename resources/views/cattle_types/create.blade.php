@extends('layouts.app')

@section('content-header')
    <h1>{{__('cattle.create_c_type')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('cattle_types.index') }}">
                <i class="fa fa-dashboard"></i> {{__('cattle.cattle_types')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('cattle.create_nc_type')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('cattle_types.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_cattle_type')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('cattle_types.store') }}" id="create_cattle_type_form"
              name="create_cattle_type_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('cattle_types.form', ['cattleType' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('cattle.add_c_type')}}</button>
            </div>
        </form>
    </div>

@endsection