<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cattle_id') ? 'has-error' : '' }}">
            <label for="cattle_id">{{__('commons.cattle')}}</label>
            <select class="form-control  select-admin-lte" id="cattle_id" name="cattle_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($cattle as $key => $cattle)
                    <option
                        value="{{ $key }}" {{ old('cattle_id', optional($breed)->cattle_id) == $key ? 'selected' : '' }}>
                        {{ $cattle }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('cattle_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('breeding_date') ? 'has-error' : '' }}">
            <label for="breeding_date">{{__('breed.breeding_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="breeding_date" id="breeding_date"
                       value="{{ old('breeding_date', optional($breed)->breeding_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('breeding_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('breeding_type') ? 'has-error' : '' }}">
            <label for="breeding_type">{{__('breed.breeding_type')}}</label>
            <select class="form-control  select-admin-lte" id="breeding_type" name="breeding_type" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Artificial Insemination' => 'Artificial Insemination',
'Natural Insemination' => 'Natural Insemination'] as $key => $text)
                    <option
                        value="{{ $key }}" {{ old('breeding_type', optional($breed)->breeding_type) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('breeding_type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('breeding_status') ? 'has-error' : '' }}">
            <label for="breeding_status">{{__('breed.breeding_status')}}</label>
            <select class="form-control  select-admin-lte" id="breeding_status" name="breeding_status" required>
                <option value="">----- {{__('commons.select')}} -----</option>
                @foreach (['Observation' => 'Observation',
'Success' => 'Success',
'Failed' => 'Failed'] as $key => $text)
                    <option
                        value="{{ $key }}" {{ old('breeding_status', optional($breed)->breeding_status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('breeding_status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('expected_birth_date') ? 'has-error' : '' }}">
            <label for="expected_birth_date">{{__('breed.expected_birth_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="expected_birth_date" id="expected_birth_date"
                       value="{{ old('expected_birth_date', optional($breed)->expected_birth_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('expected_birth_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
            <label for="cost">{{__('commons.cost')}}</label>
            <input class="form-control" name="cost" type="number" id="cost"
                   value="{{ old('cost', optional($breed)->cost) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('cost', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ai_worker_id') ? 'has-error' : '' }}">
            <label for="ai_worker_id">{{__('breed.aI_worker')}}</label>
            <select class="form-control  select-admin-lte" id="ai_worker_id" name="ai_worker_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($aiWorkers as $key => $aiWorker)
                    <option
                        value="{{ $key }}" {{ old('ai_worker_id', optional($breed)->ai_worker_id) == $key ? 'selected' : '' }}>
                        {{ $aiWorker }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('ai_worker_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3"
                      id="comments">{{ old('comments', optional($breed)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

