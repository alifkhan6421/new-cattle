@extends('layouts.app')

@section('content-header')
    <h1>{{__('breed.breed_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('breeds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('breed.breeds')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Breed' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('breeds.destroy', $breed->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('breeds.show'))
                        <a href="{{ route('breeds.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_breed')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('breeds.printDetails'))
                        <a href="{{ route('breeds.printDetails', $breed->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('breeds.create'))
                        <a href="{{ route('breeds.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_breed')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('breeds.edit'))
                        <a href="{{ route('breeds.edit', $breed->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_breed')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('breeds.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_breed')}}"
                                onclick="return confirm('Delete Breed?')">
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
                        <td>{{ optional($breed->cattle)->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('breed.breeding_date')}}</th>
                        <td>{{ $breed->breeding_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('breed.breeding_type')}}</th>
                        <td>{{ $breed->breeding_type }}</td>
                    </tr>
                    <tr>
                        <th>{{__('breed.breeding_status')}}</th>
                        <td>{{ $breed->breeding_status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('breed.expected_birth_date')}}</th>
                        <td>{{ $breed->expected_birth_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.cost')}}</th>
                        <td>{{ $breed->cost }}</td>
                    </tr>
                    <tr>
                        <th>{{__('breed.aI_worker')}}</th>
                        <td>{{ optional($breed->aiWorker)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.comments')}}</th>
                        <td>{{ $breed->comments }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($breed->creator)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $breed->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $breed->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
