<form method="POST"
      action="{!! route('milks.destroy', $milk->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('milks.show'))
        <a href="{{ route('milks.show', $milk->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_milk')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('milks.edit'))
        <a href="{{ route('milks.edit', $milk->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_milk')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('milks.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_milk')}}"
                onclick="return confirm('Delete Milk?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
