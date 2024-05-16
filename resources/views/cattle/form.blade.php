<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">{{__('commons.cattle_id')}}</label>
            <input class="form-control" name="title" type="text" id="title"
                   value="{{ old('title', optional($cattle)->title) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cattle_type_id') ? 'has-error' : '' }}">
            <label for="cattle_type_id">{{__('commons.cattle_type')}}</label>
            <select class="form-control  select-admin-lte" id="cattle_type_id" name="cattle_type_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($cattleTypes as $key => $cattleType)
                    <option
                        value="{{ $key }}" {{ old('cattle_type_id', optional($cattle)->cattle_type_id) == $key ? 'selected' : '' }}>
                        {{ $cattleType }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('cattle_type_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('father_insemination') ? 'has-error' : '' }}">
            <label for="father_insemination">{{__('cattle.father_insemination')}}</label>
            <select class="form-control  select-admin-lte" id="father_insemination" name="father_insemination" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Artificial Insemination' => 'Artificial Insemination', 'Natural Insemination' => 'Natural Insemination'] as $key => $text)
                    <option
                        value="{{ $key }}" {{ old('father_insemination', optional($cattle)->father_insemination) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('father_insemination', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
            <label for="parent_id">{{__('commons.parent')}}</label>
            <select class="form-control  select-admin-lte" id="parent_id" name="parent_id">
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($parents as $key => $cow)
                    <option
                        value="{{ $key }}" {{ old('parent_id', optional($cow)->parent_id) == $key ? 'selected' : '' }}>
                        Cow #{{ $cow }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('purchase_source') ? 'has-error' : '' }}">
            <label for="purchase_source">{{__('cattle.purchase_source')}}</label>
            <input class="form-control" name="purchase_source" type="text" id="purchase_source"
                   value="{{ old('purchase_source', optional($cattle)->purchase_source) }}" minlength="1"
                   maxlength="255" required>
            {!! $errors->first('purchase_source', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('purchase_amount') ? 'has-error' : '' }}">
            <label for="purchase_amount">{{__('cattle.purchase_amount')}}</label>
            <input class="form-control" name="purchase_amount" type="number" id="purchase_amount"
                   value="{{ old('purchase_amount', optional($cattle)->purchase_amount) }}" min="-2147483648"
                   max="2147483647" required>
            {!! $errors->first('purchase_amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('purchase_date') ? 'has-error' : '' }}">
            <label for="purchase_date">{{__('cattle.purchase_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="purchase_date"
                       id="purchase_date" value="{{ old('purchase_date', optional($cattle)->purchase_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('purchase_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('farm_entry_date') ? 'has-error' : '' }}">
            <label for="farm_entry_date">{{__('cattle.farm_entry_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="farm_entry_date" id="farm_entry_date"
                       value="{{ old('farm_entry_date', optional($cattle)->farm_entry_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('farm_entry_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('purchase_image') ? 'has-error' : '' }}">
            <label for="purchase_image">{{__('cattle.purchase_image')}}</label>

            <div class="input-group uploaded-file-group">
                @if(!empty($cattle->purchase_image))
                    <label class="input-group-btn" for="purchase_image_path">
                        <img src="{{ asset('storage/' . $cattle->purchase_image) }}"
                             alt="Purchase Image"
                             class="form-image">
                    </label>
                @endif

                <input type="text" class="form-control uploaded-file-name"
                       value="{{ optional($cattle)->purchase_image }}"
                       id="purchase_image_path"
                       readonly>
                <label class="input-group-btn">
                    <span class="btn btn-warning">
                        {{__('cattle.browse')}} <input type="file" name="purchase_image" id="purchase_image" class="hidden">
                    </span>
                </label>
            </div>

            {!! $errors->first('purchase_image', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('latest_image') ? 'has-error' : '' }}">
            <label for="latest_image">{{__('cattle.latest_image')}}</label>

            <div class="input-group uploaded-file-group">
                @if(!empty($cattle->latest_image))
                    <label class="input-group-btn" for="latest_image_path">
                        <img src="{{ asset('storage/' . $cattle->latest_image) }}"
                             alt="Latest Image"
                             class="form-image">
                    </label>
                @endif

                <input type="text" class="form-control uploaded-file-name"
                       value="{{ optional($cattle)->latest_image }}"
                       id="latest_image_path"
                       readonly>
                <label class="input-group-btn">
                    <span class="btn btn-warning">
                        {{__('cattle.browse')}} <input type="file" name="latest_image" id="latest_image" class="hidden">
                    </span>
                </label>
            </div>

            {!! $errors->first('latest_image', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('middleman') ? 'has-error' : '' }}">
            <label for="middleman">{{__('cattle.middleman')}}</label>
            <input class="form-control" name="middleman" type="text" id="middleman"
                   value="{{ old('middleman', optional($cattle)->middleman) }}" maxlength="11">
            {!! $errors->first('middleman', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('species') ? 'has-error' : '' }}">
            <label for="species">{{__('cattle.species')}}</label>
            <input class="form-control" name="species" type="text" id="species"
                   value="{{ old('species', optional($cattle)->species) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('species', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
            <label for="date_of_birth">{{__('commons.dob')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="date_of_birth" id="date_of_birth"
                       value="{{ old('date_of_birth', optional($cattle)->date_of_birth) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('date_of_birth', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('teeth') ? 'has-error' : '' }}">
            <label for="teeth">{{__('cattle.teeth')}}</label>
            <input class="form-control" name="teeth" type="number" id="teeth"
                   value="{{ old('teeth', optional($cattle)->teeth) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('teeth', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('expected_sale_price') ? 'has-error' : '' }}">
            <label for="expected_sale_price">{{__('cattle.expected_sale_price')}}</label>
            <input class="form-control" name="expected_sale_price" type="number" id="expected_sale_price"
                   value="{{ old('expected_sale_price', optional($cattle)->expected_sale_price) }}" min="-2147483648"
                   max="2147483647" required>
            {!! $errors->first('expected_sale_price', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('daily_expense') ? 'has-error' : '' }}">
            <label for="daily_expense">{{__('cattle.daily_expense')}}</label>
            <input class="form-control" name="daily_expense" type="number" id="daily_expense"
                   value="{{ old('daily_expense', optional($cattle)->daily_expense) }}" min="-2147483648"
                   max="2147483647" required>
            {!! $errors->first('daily_expense', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('birth_type') ? 'has-error' : '' }}">
            <label for="birth_type">{{__('cattle.birth_type')}}</label>
            <select class="form-control  select-admin-lte" id="birth_type" name="birth_type" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Prematured' => 'Prematured', 'Well' => 'Well'] as $key => $text)
                    <option
                        value="{{ $key }}" {{ old('birth_type', optional($cattle)->birth_type) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('birth_type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Active' => 'Active', 'Sold' => 'Sold', 'Died' => 'Died'] as $key => $text)
                    <option
                        value="{{ $key }}" {{ old('status', optional($cattle)->status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3"
                      id="comments">{{ old('comments', optional($cattle)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

