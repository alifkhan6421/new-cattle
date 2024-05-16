<form method="POST"
      action="{!! route('feeds.destroy', $feed->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('feeds.show'))
        <a href="{{ route('feeds.show', $feed->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_feed')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('feeds.edit'))
        <a href="{{ route('feeds.edit', $feed->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_feed')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('feeds.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_feed')}}"
                onclick="return confirm('Delete feed?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
