<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= __('filemanager') ?></title>
    <base href="{{ url('/') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('client/vendor/filemanager/vendor/jquery-ui.css') }}">
    <!-- Section JavaScript -->
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <!--[if lt IE 9]>
    <script src="{{ asset('client/vendor/filemanager/vendor/jquery-1.12.4.min.js') }}"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="{{ asset('client/vendor/filemanager/vendor/jquery-3.2.1.min.js') }}"></script>
    <!--<![endif]-->
    <script src="{{ asset('client/vendor/filemanager/vendor/jquery-ui.min.js') }}"></script>



@yield('header')


</head>
<body>

@yield('content')

@yield('footer')
</body>
</html>
