<form method="POST"
      action="{!! route('cattle.destroy', $cattle->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('cattle.costs'))
        <button type="button" class="btn btn-xs btn-success btn-cattle-cost" title="{{__('buttonTitle.cattle_cost')}}"
                data-cattle-id="{{ $cattle->id }}" data-cattle-title="{{ $cattle->title }}">
            <i aria-hidden="true" class="fa fa-tags"></i>
        </button>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('cattle.bodyWeight'))
        <button type="button" class="btn btn-xs btn-warning btn-cattle-weight" title="{{__('buttonTitle.cattle_body_weight')}}"
                data-cattle-id="{{ $cattle->id }}" data-cattle-title="{{ $cattle->title }}">
            <i aria-hidden="true" class="fa fa-balance-scale"></i>
        </button>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('cattle.show'))
        <a href="{{ route('cattle.show', $cattle->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_cattle')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('cattle.edit'))
        <a href="{{ route('cattle.edit', $cattle->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_cattle')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('cattle.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_cattle')}}"
                onclick="return confirm('Delete Cattle?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
