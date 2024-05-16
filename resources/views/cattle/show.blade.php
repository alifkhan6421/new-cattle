@extends('layouts.app')

@section('content-header')
    <h1>{{__('cattle.cattle_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('cattle.index') }}">
                <i class="fa fa-dashboard"></i> {{__('cattle.page_title')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($cattle->title) ? ucfirst($cattle->title) : 'Cattle' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('cattle.destroy', $cattle->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('cattle.index'))
                        <a href="{{ route('cattle.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_cattle')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle.printDetails'))
                        <a href="{{ route('cattle.printDetails', $cattle->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle.create'))
                        <a href="{{ route('cattle.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_cattle')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle.edit'))
                        <a href="{{ route('cattle.edit', $cattle->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_cattle')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('cattle.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_cattle')}}"
                                onclick="return confirm('Delete Cattle?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">

                    <div class="table-responsive">
                        <table class="table table-bordered table-show">
                            <tbody>
                            <tr>
                                <th>{{__('commons.cattle_id')}}</th>
                                <td>{{ $cattle->title }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.status')}}</th>
                                <td>{{ $cattle->status }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.cattle_type')}}</th>
                                <td>{{ optional($cattle->cattleType)->title }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.father_insemination')}}</th>
                                <td>{{ $cattle->father_insemination }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.parent')}}</th>
                                <td>{{ optional($cattle->Cattle)->title }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.purchase_source')}}</th>
                                <td>{{ $cattle->purchase_source }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.purchase_amount')}}</th>
                                <td>{{ $cattle->purchase_amount }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.purchase_date')}}</th>
                                <td>{{ $cattle->purchase_date }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.farm_entry_date')}}</th>
                                <td>{{ $cattle->farm_entry_date }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.body_weights')}}</th>
                                <td>

                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>{{__('commons.date')}}</th>
                                                <th class="text-right">{{__('commons.weight')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cattleWeights as $cattleWeight)
                                                <tr>
                                                    <td>{{ $cattleWeight->date }}</td>
                                                    <td class="text-right">{{ $cattleWeight->weight }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.body_costs')}}</th>
                                <td>
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>{{__('commons.date')}}</th>
                                                <th class="text-right">{{__('commons.cost')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cattleCosts as $cattleCost)
                                                <tr>
                                                    <td>{{ date('M, Y', strtotime($cattleCost->date)) }}</td>
                                                    <td class="text-right">{{ $cattleCost->cost }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="table-responsive">
                        <table class="table table-bordered table-show">
                            <tbody>
                            <tr>
                                <th>{{__('cattle.purchase_image')}}</th>
                                <td>
                                    @if (!empty($cattle->purchase_image))
                                        <img src="{{ asset('storage/' . $cattle->purchase_image) }}"
                                             alt="Purchase Image" class="thumbnail medium"/>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.latest_image')}}</th>
                                <td>
                                    @if (!empty($cattle->latest_image))
                                        <img src="{{ asset('storage/' . $cattle->latest_image) }}"
                                             alt="Latest Image" class="thumbnail medium"/>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.middleman')}}</th>
                                <td>{{ $cattle->middleman }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.species')}}</th>
                                <td>{{ $cattle->species }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.dob')}}</th>
                                <td>{{ $cattle->date_of_birth }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.teeth')}}</th>
                                <td>{{ $cattle->teeth }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.expected_sale_price')}}</th>
                                <td>{{ $cattle->expected_sale_price }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.daily_expense')}}</th>
                                <td>{{ $cattle->daily_expense }}</td>
                            </tr>
                            <tr>
                                <th>{{__('cattle.birth_type')}}</th>
                                <td>{{ $cattle->birth_type }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.comments')}}</th>
                                <td>{{ $cattle->comments }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.created_by')}}</th>
                                <td>{{ optional($cattle->creator)->name }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.created_at')}}</th>
                                <td>{{ $cattle->created_at }}</td>
                            </tr>
                            <tr>
                                <th>{{__('commons.update_at')}}</th>
                                <td>{{ $cattle->updated_at }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
