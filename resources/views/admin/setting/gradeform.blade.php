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
<form class="form" action="{!! selfURL() !!}" method="post">
@csrf
    <div class="form-group">
        {!! formLabel($form->get('grade')) !!}
        {!! formElement($form->get('grade')) !!}
    </div>
    <div class="form-group">
        {!! formLabel($form->get('min')) !!}
        {!! formElement($form->get('min')) !!}
    </div>
    <div class="form-group">
        {!! formLabel($form->get('max')) !!}
        {!! formElement($form->get('max')) !!}
    </div>

    <button class="btn btn-primary" type="submit">{{__lang('submit')}}</button>
</form>
@endsection
