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
    {!! __lang('cert-list-help') !!}

</div>
<div class="card">
 <div class="card-header">
     <h4>{{ __lang('download-list') }}</h4>
</div>
<div class="card-body">
    <form enctype="multipart/form-data" class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'certificatelist')) }}">
        @csrf


        <div  id="sessionbox"  class="form-group" style="padding-bottom: 10px">
            <label for="session_id">{{ __lang('session-course') }}</label>
            {{ formElement($select) }}
        </div>
        <div class="form-group"  >
            <label>{{ __lang('search') }}:</label> <input checked type="radio" name="search" value="present"/>{{ __lang('this-course-only') }}
            &nbsp; <input type="radio" name="search" value="all"/>{{ __lang('any-session-course') }}
        </div>

        <div id="lessonbox">




        </div>



        <button class="btn btn-primary" type="submit">{{ __lang('download') }}</button>
    </form>

</div>
</div>

<script type="text/javascript"><!--

    jQuery(function(){

        if($('#sessionbox select').val()!=''){
            loadClasses();
        }

        $(document).on('change','#type',function(){
            showOptions();
        });

        $('#sessionbox select').change(function(){
            loadClasses();
        });



    });

    function loadClasses(){
        var val = $('#sessionbox select').val();
        $('#lessonbox').text('Loading...');
        var url = '{{ basePath()}}/admin/student/certificatelessons/'+val;
        $('#lessonbox').load(url,function(){
            showOptions();
        });
    }


    function showOptions(){
        $('.option').hide();
        var type = $('#type').val();
       $('.'+type).show();
    }
    //--></script>
@endsection
