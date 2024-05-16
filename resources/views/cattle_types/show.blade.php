@extends('layouts.app')

@section('content-header')
    <h1>{{__('cattle.c_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('cattle_types.index') }}">
                <i class="fa fa-dashboard"></i> {{__('cattle.cattle_types')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($cattleType->title) ? ucfirst($cattleType->title) : 'Cattle Type' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('cattle_types.destroy', $cattleType->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('cattle_types.index'))
                        <a href="{{ route('cattle_types.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_cattle_type')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle_types.printDetails'))
                        <a href="{{ route('cattle_types.printDetails', $cattleType->id) }}"
                           class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle_types.create'))
                        <a href="{{ route('cattle_types.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_cattle_type')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle_types.edit'))
                        <a href="{{ route('cattle_types.edit', $cattleType->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_cattle_type')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle_types.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_cattle_type')}}"
                                onclick="return confirm('Delete Cattle Type?')">
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
            </div>
        </div>
    </div>

@endsection
