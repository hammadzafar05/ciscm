@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div class="form-group" style="padding-bottom: 10px">
    <label>{{ __lang('list-type') }}:</label>
    <select id="type" class="form-control" name="type">
        <option value="classes">{{ __lang('specific-classes') }}</option>
        <option value="minimum">{{ __lang('minimum-no-classes') }}</option>
    </select>

</div>

<div class="option classes">
@php foreach($lessons as $row):  @endphp
    <div  class="form-group" style="padding-bottom: 10px">
        <input checked type="checkbox" name="lesson_{{ $row->lesson_id}}" value="{{ $row->lesson_id}}"/> <label for="session_id">{{ __lang('class') }} {{ $row->lesson_id}}: {{ $row->name}} </label>

    </div>
@php endforeach;  @endphp

</div>
<div class="option minimum form-group">
    <label>
        {{ __lang('enter-minimum-no-classes') }}
    </label>
    <input  class="form-control" type="text" name="quantity" placeholder="{{ __lang('digits-only') }}"/>

</div>
@endsection
