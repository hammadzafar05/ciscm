@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
               route('student.student.surveys')=>__lang('surveys'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
    <div class="card">
    <div class="card-body">
        {{  $message  }}
    </div>
    </div>

@endsection
