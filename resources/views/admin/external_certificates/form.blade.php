<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title"
           class="control-label required">@lang('default.name')</label>
    <input required
           class="form-control"
           name="title"
           type="title"
           id="title"
           value="{{ old('name',isset($external_certificate->title) ? $external_certificate->title : '') }}"
           maxlength="191">
    {!! clean( $errors->first('title', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('tracking_number') ? 'has-error' : ''}}">
    <label for="tracking_number"
           class="control-label">@lang('default.certificate_number')</label>
    <input class="form-control"
           name="tracking_number"
           type="text"
           id="tracking_number"
           value="{{ old('tracking_number',isset($external_certificate->tracking_number) ? $external_certificate->tracking_number : '') }}" >
    {!! clean( $errors->first('tracking_number', '<p class="help-block">:message</p>'))  !!}
</div>

<div class="form-group {{ $errors->has('course') ? 'has-error' : ''}}">
    <label for="course"
           class="control-label">@lang('default.program_name')</label>
    <input class="form-control"
           name="course"
           type="text"
           id="course"
           value="{{ old('course',isset($external_certificate->course) ? $external_certificate->course : '') }}" >
    {!! clean( $errors->first('course', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('grade') ? 'has-error' : ''}}">
    <label for="course"
           class="control-label">Grade</label>
    <input class="form-control"
           name="grade"
           type="text"
           id="course"
           value="{{ old('grade',isset($external_certificate->grade) ? $external_certificate->grade : '') }}" >
    {!! clean( $errors->first('grade', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('cgpa') ? 'has-error' : ''}}">
    <label for="course"
           class="control-label">CGPA</label>
    <input class="form-control"
           name="cgpa"
           type="text"
           id="course"
           value="{{ old('cgpa',isset($external_certificate->cgpa) ? $external_certificate->cgpa : '') }}" >
    {!! clean( $errors->first('cgpa', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('passing_year') ? 'has-error' : ''}}">
    <label for="course"
           class="control-label">passing_year</label>
    <input class="form-control"
           name="passing_year"
           type="text"
           id="course"
           value="{{ old('passing_year',isset($external_certificate->passing_year) ? $external_certificate->passing_year : '') }}" >
    {!! clean( $errors->first('passing_year', '<p class="help-block">:message</p>') ) !!}
</div>



<div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
    <label for="country"
           class="control-label">@lang('default.country')</label>
    {{--<input class="form-control"
           name="country"
           type="text"
           id="country"
           value="{{ old('country',isset($external_certificate->country) ? $external_certificate->country : '') }}" >--}}
    <select name="country"
            class="form-control"
            id="country" required >
        <option value="">Select an option</option>
        @foreach ($countries as $country)
        <option value="{{ $country->name }}" {{ ((null !== old('country',@$external_certificate->country)) && old('country',@$external_certificate->country) == $country->name) ? 'selected' : ''}}>{{ $country->name }}</option>
        @endforeach
    </select>

    {!! clean( $errors->first('country', '<p class="help-block">:message</p>'))  !!}
</div>


<div class="form-group {{ $errors->has('issue_date') ? 'has-error' : ''}}">
    <label for="issue_date"
           class="control-label">@lang('default.issue_date')</label>
    <input class="form-control"
           name="issue_date"
           type="text"
           id="issue_date"
           value="{{ old('issue_date',isset($external_certificate->issue_date) ? $external_certificate->issue_date : '') }}" >
    {!! clean( $errors->first('issue_date', '<p class="help-block">:message</p>'))  !!}
</div>

<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled"
           class="control-label">@lang('default.enabled')</label>
    <select name="enabled"
            class="form-control" id="enabled" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$external_certificate->enabled)) && old('article',@$external_certificate->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean($errors->first('enabled', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('default.update') : __('default.create') }}">
</div>
