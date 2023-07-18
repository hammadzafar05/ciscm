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
<div class="container box" style="padding-left: 50px; padding-right: 50px">
    <p>{{  nl2br($bank_instructions) }}</p>
    <a class="btn btn-primary" href="{{ $this->url('shopping-cart/default',['action'=>'clear']) }}">{{  __lang('complete-order')  }}</a>
</div>

@endsection
