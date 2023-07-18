@extends('layouts.cart')
@section('page-title',__lang('order-complete'))
@section('page-class')
    class="col-md-6 offset-md-3"
@endsection


@section('content')

    <div class="card">
        <div class="card-header">
            <h4>{{ __lang('order-complete') }}</h4>
            <div class="card-header-action">
                <a title="{{ __lang('home') }}" data-toggle="tooltip" data-placement="top" href="{{ url('/') }}" class="btn btn-icon btn-primary"><i class="fa fa-home"></i></a>
            </div>
        </div>
        <div class="card-body text-center">
            <div class="display-1"><i class="fa fa-check-circle"></i></div>

            <div class="row">
                <div class="col-md-6"><a href="{{ url('/') }}" class="btn btn-success btn-block"><i class="fa fa-home"></i> {{ __lang('home') }}</a></div>
                <div class="col-md-6"><a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-block"><i class="fa fa-user"></i> {{ __lang('dashboard') }}</a></div>
            </div>
        </div>
    </div>

@endsection
