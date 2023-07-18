@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
                 adminUrl(['controller'=>'student','action'=>'sessions'])=>__lang('Courses'),
            adminUrl(['controller'=>'session','action'=>'tests','id'=>$id])=>__lang('Tests'),
            '#'=>$crumbLabel
        ]])
@endsection

@section('content')
    <div class="card">
    <div class="card-body">
        <form class="form" action="{{ selfURL() }}" method="post">
            @csrf
            <div class="form-group">
                {{ formLabel($form->get('test_id')) }}
                {{ formElement($form->get('test_id')) }}
                <p class="help-block">{{ formElementErrors($form->get('test_id')) }}</p>
            </div>


            <div class="form-group">
                {{ formLabel($form->get('opening_date')) }}
                {{ formElement($form->get('opening_date')) }}
                <p class="help-block">{{ formElementErrors($form->get('opening_date')) }}</p>
            </div>



            <div class="form-group">
                {{ formLabel($form->get('closing_date')) }}
                {{ formElement($form->get('closing_date')) }}
                <p class="help-block">{{ formElementErrors($form->get('closing_date')) }}</p>
            </div>




            <div class="form-footer">
                <button type="submit" class="btn btn-block btn-lg btn-primary">{{__lang('save')}}</button>
            </div>
        </form>

    </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.date.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.time.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.css' }}">
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
