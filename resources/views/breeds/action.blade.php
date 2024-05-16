<form method="POST"
      action="{!! route('breeds.destroy', $breed->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('breeds.show'))
        <a href="{{ route('breeds.show', $breed->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_breed')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('breeds.edit'))
        <a href="{{ route('breeds.edit', $breed->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_breed')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('breeds.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_breed')}}"
                onclick="return confirm('Delete Breed?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
