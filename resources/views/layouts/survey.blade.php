<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('pageTitle',isset($pageTitle)? $pageTitle:__('default.my-account')) - {{ setting('general_site_name') }}</title>

    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif

<!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('client/css/admin.css') }}">
    <link href="{{ asset('client/vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
    @yield('header')
</head>

<body class="layout-3">
<div id="app">
    <div class="main-wrapper container">

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="text-center"><h3>{{ $pageTitle }}</h3></div>
                    @yield('content')
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __lang('copyright') }} &copy; {{ date('Y') }}  {{ setting('general_site_name') }}
            </div>
            <div class="footer-right">

            </div>
        </footer>
    </div>
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
<script src="{{ asset('client/vendor/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('client/app/lib.js') }}"></script>
@yield('footer')
</body>
</html>
