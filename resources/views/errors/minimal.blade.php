@extends('layouts.auth')
@section('page-title',__lang('error'))
@section('content')

    <div class="page-error pt-0"  >
        <div class="page-inner">
            <h1>@yield('code')</h1>
            <div class="page-description">
                @yield('message')
            </div>
            <div class="page-search">
                <div class="mt-3">
                    <a href="{{ url('/') }}">{{ __lang('home') }}</a>
                </div>
            </div>
        </div>
    </div>


@endsection
