<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cattle_id') ? 'has-error' : '' }}">
            <label for="cattle_id">{{__('commons.cattle')}}</label>
            <select class="form-control  select-admin-lte" id="cattle_id" name="cattle_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($cattle as $key => $cattle)
                    <option
                        value="{{ $key }}" {{ old('cattle_id', optional($milk)->cattle_id) == $key ? 'selected' : '' }}>
                        {{ $cattle }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('cattle_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date">{{__('commons.date')}}</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="date" id="date"
                       value="{{ old('date', optional($milk)->date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('morning_amount') ? 'has-error' : '' }}">
            <label for="morning_amount">{{__('milk.morning_amount')}}</label>
            <input class="form-control" name="morning_amount" type="number" id="morning_amount"
                   value="{{ old('morning_amount', optional($milk)->morning_amount) }}" min="-2147483648"
                   max="2147483647" required>
            {!! $errors->first('morning_amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('noon_amount') ? 'has-error' : '' }}">
            <label for="noon_amount">{{__('milk.noon_amount')}}</label>
            <input class="form-control" name="noon_amount" type="number" id="noon_amount"
                   value="{{ old('noon_amount', optional($milk)->noon_amount) }}" min="-2147483648" max="2147483647"
                   required>
            {!! $errors->first('noon_amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('after_noon_amount') ? 'has-error' : '' }}">
            <label for="after_noon_amount">{{__('milk.after_noon_amount')}}</label>
            <input class="form-control" name="after_noon_amount" type="number" id="after_noon_amount"
                   value="{{ old('after_noon_amount', optional($milk)->after_noon_amount) }}" min="-2147483648"
                   max="2147483647" required>
            {!! $errors->first('after_noon_amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3"
                      id="comments">{{ old('comments', optional($milk)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

