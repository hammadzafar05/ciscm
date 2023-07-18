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
<div class="alert alert-info alert-dismissible">
    <a class="close" data-dismiss="alert" href="messages#">×</a>
    <h4 class="alert-heading">{{ __lang('info') }}!</h4>
    <p>
        {{ __lang('attendance-date-help') }}
    </p>

</div>


<div class="card">
 <div class="card-header">
     <h4>{{ __lang('set-dates-help') }}</h4>
</div>
<div class="card-body">
    <form onsubmit="return confirm('{{__lang('set-dates-confirm')}}')" enctype="multipart/form-data" class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'attendancedate')) }}">
        @csrf


        <div id="sessionbox" class="form-group" style="padding-bottom: 10px">
            <label for="session_id">{{ __lang('session-course') }}</label>
            {{ formElement($select) }}
        </div>
        <div id="lessonbox">




        </div>




        <button class="btn btn-primary" type="submit">{{__lang('save')}}</button>
    </form>

</div>
</div>




@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
@endsection


@section('footer')
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script type="text/javascript"><!--

        jQuery(function(){
            jQuery('.date').pickadate({
                format: 'yyyy-mm-dd'
            });

            $('#sessionbox select').change(function(){
                var val = $(this).val();
                $('#lessonbox').text('Loading...');
                var url = '{{ basePath()}}/admin/student/sessionlessons/'+val;
                $('#lessonbox').load(url,function(){
                    jQuery('.date').pickadate({
                        format: 'yyyy-mm-dd'
                    });
                });
            });



        });
        //--></script>
@endsection
