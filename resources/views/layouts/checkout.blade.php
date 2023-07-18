@extends('layouts.cart')

@section('page-class')
    class="col-md-6 offset-md-3"
@endsection

@section('back',route('cart'))

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>@yield('page-title')</h4>
            <div class="card-header-action">
                <a title="{{ __lang('your-cart') }}" data-toggle="tooltip" data-placement="top" href="{{ route('cart') }}" class="btn btn-icon btn-primary"><i class="fa fa-cart-plus"></i></a>
            </div>
        </div>
        <div class="card-body">
            @yield('payment-content')
        </div>
    </div>

@endsection
