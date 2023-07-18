@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{ __lang('change-language') }}
    </div>
    <div class="card-body">
        <form method="post" action="{{ selfURL() }}">
            @csrf
            <div class="form-group">
                <label for="password1" class="control-label">{{ formLabel($select) }}</label>
                {{ formElement($select) }}   <p class="help-block">{{ formElementErrors($select) }}</p>

            </div>


            <div class="form-footer">
                <button type="submit" class="btn btn-primary">{{ __lang('submit') }}</button>
            </div>

        </form>

    </div>

</div>

@endsection
