@extends('layouts.app')

@section('content-header')
    <h1>{{__('cattle.edit_cattle')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('cattle.index') }}">
                <i class="fa fa-dashboard"></i> {{__('cattle.page_title')}}
            </a>
        </li>
        <li class="active">{{__('cattle.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($cattle->title) ? ucfirst($cattle->title) : 'Cattle' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('cattle.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_cattle')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('cattle.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_cattle')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('cattle.update', $cattle->id) }}"
              id="edit_cattle_form"
              name="edit_cattle_form" accept-charset="UTF-8"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('cattle.form', ['cattle' => $cattle,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection