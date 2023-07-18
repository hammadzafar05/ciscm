<!DOCTYPE html>
<html {!!  langMeta() !!}>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('page-title') - {{ setting('general_site_name') }}</title>

    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/components.css') }}">
    {!! setting('general_header_scripts') !!}
    @yield('header')
</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-2">
            <div class="row">
                <div @section('page-class') class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4" @show >
                    <div class="login-brand">
                        <a href="{{ url('/') }}">
                            @if(!empty(setting('image_logo')))
                                <img  alt="logo" width="100"  src="{{ asset(setting('image_logo')) }}" >
                        @else
                                <h1>{{ setting('general_site_name') }}</h1>
                        @endif
                        </a>
                    </div>

                    @include('partials.flash_message')

                    @yield('content')



                    <div class="simple-footer">
                        {{ __lang('copyright') }} &copy; {{ date('Y') }}  {{ setting('general_site_name') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- General JS Scripts -->
<script src="{{ asset('client/themes/admin/assets/modules/jquery.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/popper.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/moment.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->

<!-- Page Specific JS File -->

<!-- Template JS File -->
<script src="{{ asset('client/themes/admin/assets/js/scripts.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/js/custom.js') }}"></script>
{!! setting('general_foot_scripts') !!}
@yield('footer')
</body>
</html>
