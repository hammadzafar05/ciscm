@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
@php   $this->headTitle(__lang('Welcome'))  @endphp
<div class="subpage-head has-margin-bottom" style=" padding-top: 0px;">
    <div class="container">
        <h3>{{  __lang('registration-successful')  }}</h3>
    </div>
</div>

<!--breadcrumb-section ends-->
<!--container starts-->
<div class="container" style="background-color: white; min-height: 400px;   padding-bottom:50px; margin-bottom: 10px;   " >
    <!--primary starts-->

    <div class="card-body">

    <p>{{  __lang('registration-successful-msg')  }}</p>
  <p>{{  setting('regis_email_message') }}</p>


    </div>


</div>

<!--container ends-->

@endsection
