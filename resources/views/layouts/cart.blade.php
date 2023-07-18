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
                <div @section('page-class') class="col-12" @show >
                    <div class="login-brand">
                        <a href="{{ url('/') }}">
                            @if(!empty(setting('image_logo')))
                                <img  alt="logo" width="100" class="shadow-light" src="{{ asset(setting('image_logo')) }}" >
                            @else
                                <h1>{{ setting('general_site_name') }}</h1>
                            @endif
                        </a>
                    </div>

                    @include('partials.flash_message')

                    @yield('content')



                    <div class="simple-footer">
                       <div class="mb-4"> <a href="@section('back'){{ back()->getTargetUrl() }}@show" data-toggle="tooltip" data-placement="top" title="{{ __lang('back') }}" class="btn btn-icon btn-success"><i class="fa fa-chevron-left"></i></a> <a data-toggle="tooltip" data-placement="top" title="{{ __lang('home') }}"  href="{{ url('/') }}" class="btn btn-icon btn-primary"><i class="fa fa-home"></i></a>
                          </div>
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

<div class="modal fade" id="generalModal" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generalModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"  id="genmodalinfo">
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="generalLargeModal" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generalLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"  id="genLargemodalinfo">
            </div>

        </div>
    </div>
</div>

<!-- END SIMPLE MODAL MARKUP -->
<script>
    function openModal(title,url){
        $('#genmodalinfo').html(' <img  src="{{ asset('img/ajax-loader.gif')  }}');
        $('#generalModalLabel').text(title);
        $('#genmodalinfo').load(url);
        $('#generalModal').modal();
    }
    function openLargeModal(title,url){
        $('#genLargemodalinfo').html(' <img  src="{{ asset('img/ajax-loader.gif')  }}');
        $('#generalLargeModalLabel').text(title);
        $('#genLargemodalinfo').load(url);
        $('#generalLargeModal').modal();
    }
    function openPopup(url){
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
        return false;
    }
</script>
</body>
</html>
