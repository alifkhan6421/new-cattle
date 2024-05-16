@extends('layouts.app')

@section('content-header')
    <h1>{{__('milk.milk_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('milks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('milk.milks')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Milk' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('milks.destroy', $milk->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('milks.index'))
                        <a href="{{ route('milks.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_milk')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('milks.printDetails'))
                        <a href="{{ route('milks.printDetails', $milk->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('milks.create'))
                        <a href="{{ route('milks.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_milk')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('milks.edit'))
                        <a href="{{ route('milks.edit', $milk->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_milk')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('milks.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_milk')}}"
                                onclick="return confirm('Delete Milk?')">
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
            </div>
        </div>
    </div>

@endsection
