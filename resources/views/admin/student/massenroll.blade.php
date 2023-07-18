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
<form onsubmit="return confirm('You are about to enroll students. Continue?')" enctype="multipart/form-data" class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'massenroll')) }}">
@csrf
  <div class="card">
   <div class="card-header">
        {{ __lang('enroll-multiple-to-course') }}
  </div>
  <div class="card-body">
      <p>
{!!  clean(__lang('enroll-multiple-help',['link'=>adminUrl(array('controller'=>'student','action'=>'csvsample'))])) !!}
</p>

<div class="form-group" style="padding-bottom: 10px">
<label for="session_id">{{ __lang('session-course') }}</label>
{{ formElement($select) }}
</div>



<div class="form-group" style="padding-bottom: 10px">
<label for="file">{{ __lang('csv-file') }}</label>
<input required="required" name="file" type="file"/>
</div>

<button class="btn btn-primary btn-block" type="submit">{{ __lang('enroll') }}</button>
</div>
</div>


</form>
@endsection
