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
         <h4>{{ __lang('set-att-multiple') }}</h4>
    </div>
    <div class="card-body">
        <form onsubmit="return confirm('{{__lang('attendance-import-confirm')}}')" enctype="multipart/form-data" class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'attendanceimport')) }}">
            @csrf
            <p>
            <h5>{{ __lang('important-instructions') }}</h5>
            {!!   __lang('attendance-import-help',['link'=>adminUrl(array('controller'=>'student','action'=>'sessions'))]) !!}

            </p>

            <div class="form-group" style="padding-bottom: 10px">
                <label for="session_id">{{ __lang('session-course') }}</label>
                {{ formElement($course_id) }}
            </div>



            <div class="form-group" style="padding-bottom: 10px">
                <label for="file">{{ __lang('csv-file') }}</label>
                <input required="required" name="file" type="file"/>
            </div>

            <button class="btn btn-primary" type="submit">{{ __lang('upload') }}</button>
        </form>

    </div>
    </div>

@endsection
