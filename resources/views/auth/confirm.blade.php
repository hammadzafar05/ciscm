@extends('layouts.auth')
@section('page-title',__('default.email-confirmation'))

@section('content')


    <div class="card card-primary">
     <div class="card-header">
               <h4>@lang('default.email-confirmation')</h4>
    </div>
    <div class="card-body">
        <div class="card-title"><h4>@lang('default.confirm-your-email')</h4></div>

        <p>@lang('default.email-confirmation-msg')</p>
    </div>
    </div>


@endsection
