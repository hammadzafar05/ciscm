@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
<form class="form" action="{{ selfURL() }}" method="post">
@csrf
    <div class="form-group">
        {{ formLabel($form->get('course_id')) }}
        {{ formElement($form->get('course_id')) }}
        <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>
    </div>





    <div class="form-footer">
        <button type="submit" class="btn btn-primary">{{__lang('save')}}</button>
    </div>
</form>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
@endsection


@section('footer')
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script>
        $(function(){
            $('.date').pickadate({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
