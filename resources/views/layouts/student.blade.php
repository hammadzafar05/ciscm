<!DOCTYPE html>
<html {!!  langMeta() !!}>
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

    <link rel="stylesheet" href="{{ tasset('css/bonwhatsapp.css') }}">

    {!! setting('general_header_scripts') !!}
    @yield('header')
</head>

<body class="layout-3">
<div id="app">
    <div class="main-wrapper container">

        @include('pages.bonwhatsappchat')

        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <a href="{{ url('/') }}" class="navbar-brand sidebar-gone-hide">{{ limitLength(setting('general_site_name'),11) }}</a>
            <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
            <div class="nav-collapse">
                <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item active"><a href="{{ url('/') }}" class="nav-link">{{ __lang('home') }}</a></li>
                    @if(setting('menu_show_courses')==1)
                    <li class="nav-item"><a href="{{ route('courses') }}" class="nav-link">{{ __lang('online-courses') }}</a></li>
                    @endif
                    @if(setting('menu_show_sessions')==1)
                    <li class="nav-item"><a href="{{ route('sessions') }}" class="nav-link">{{ __lang('upcoming-sessions') }}</a></li>
                    @endif

                    <li class="nav-item">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="#" class="nav-link">
                                <span id="clock_1" class="clock float-right btn rounded btn-danger">loading ...</span>
                            </a>
                            <a href="#" class="nav-link">
                                <span id="clock_2" class="clock float-right btn rounded btn-warning">loading ...</span>
                            </a>
                        </div>

                    </li>
                </ul>
            </div>
            <form class="form-inline ml-auto" action="{{ route('courses') }}">
                <ul class="navbar-nav">
                    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                </ul>
                <div class="search-element">
                    <input name="filter" class="form-control" type="search" placeholder="{{ __lang('search-courses') }}" aria-label="Search" data-width="250">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>

                </div>
            </form>

            <ul class="navbar-nav navbar-right">
                @if(getCart()->hasItems())
                <li  ><a href="{{ route('cart') }}"   class="nav-link nav-link-lg  beep"><i class="fa fa-cart-plus"></i> {{ getCart()->getTotalItems() }}</a></li>
                @endif
                <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        @if(!empty(Auth::user()->picture) && file_exists(Auth::user()->picture))
                            <img alt="image" src="{{ resizeImage(Auth::user()->picture,50,50,url('/')) }}" class="rounded-circle mr-1">
                        @else
                            <img alt="image" src="{{ asset('client/themes/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                        @endif
                        <div class="d-sm-none d-lg-inline-block">@lang('default.hi'), {{ Auth()->user()->name }}</div></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-title">@lang('default.account')</div>
                        <a href="@route('student.student.profile')" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> @lang('default.profile')
                        </a>
                        <a href="@route('student.student.password')" class="dropdown-item has-icon">
                            <i class="fas fa-unlock"></i> @lang('default.change-password')
                        </a>
                        <a href="@route('student.student.billing')" class="dropdown-item has-icon">
                            <i class="fas fa-home"></i> @lang('default.billing-address')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> @lang('default.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <nav class="navbar navbar-secondary navbar-expand-lg">
            <div class="container">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('student.dashboard') }}"
                           class="nav-link">
                            <i class="fas fa-fire"></i>
                            <span>{{ __lang('dashboard') }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="fas fa-chalkboard-teacher"></i><span>{{ setting('label_my_sessions',__lang('my-courses')) }}</span></a>
                        <ul class="dropdown-menu dropdown-border">
                            <li class="nav-item"><a href="{{ route('student.student.mysessions') }}" class="nav-link">{{ __lang('enrolled-courses') }}</a></li>
                            <li class="nav-item"><a href="{{ route('student.course.bookmarks') }}" class="nav-link">{{ __lang('bookmarks') }}</a></li>
                            @if(setting('menu_show_notes')==1)
                            <li class="nav-item"><a href="{{ route('student.student.notes') }}" class="nav-link">{{ __lang('revision-notes') }}</a></li>
                            @endif
                            <li class="nav-item"><a href="{{ route('student.student.surveys') }}" class="nav-link">{{ __lang('surveys') }}</a></li>
                            <li class="nav-item"><a href="{{ route('student.student.invoices') }}" class="nav-link">{{ __lang('invoices') }}</a></li>
                        </ul>
                    </li>
                    @if(setting('menu_show_certificates')==1 || setting('menu_show_downloads')==1)
                    <li class="nav-item dropdown" style="display: none">
                        <a href="#"
                           data-toggle="dropdown"
                           class="nav-link has-dropdown"><i class="fas fa-download"></i><span>{{ setting('label_downloads',__lang('resources')) }}</span></a>
                        <ul class="dropdown-menu dropdown-border">
                            @if(setting('menu_show_certificates')==1)
                            <li class="nav-item"><a href="{{ route('student.student.certificates') }}" class="nav-link">{{ __lang('certificates') }}</a></li>
                            @endif
                            @if(setting('menu_show_downloads')==1)
                            <li class="nav-item"><a href="{{ route('student.download.index') }}" class="nav-link">{{ __lang('downloads') }}</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(setting('menu_show_certificates')==1)
                        <li class="nav-item dropdown">
                            <a href="{{ route('student.student.certificates') }}"
                               class="nav-link">
                                <i class="fas fa-download"></i>
                                <span>{{ __lang('certificates') }}</span>
                            </a>
                        </li>
                    @endif

                    @if(setting('menu_show_homework')==1)
                    <li class="nav-item dropdown">
                        <a href="#"
                           data-toggle="dropdown"
                           class="nav-link has-dropdown">
                            <i class="fas fa-edit"></i>
                            <span>{{ setting('label_assignment',__lang('Assignment')) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-border">
                            <li class="nav-item">
                                <a href="{{ route('student.assignment.index') }}"
                                   class="nav-link">
                                    {{ __lang('view-all') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student.assignment.submissions') }}"
                                   class="nav-link">
                                    {{ __lang('my-submissions') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(setting('menu_show_discussions')==1)
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="fas fa-comments"></i><span>{{ setting('label_discussion',__lang('discuss')) }}</span></a>
                        <ul class="dropdown-menu dropdown-border">
                            <li class="nav-item"><a href="{{ route('student.student.discussion') }}" class="nav-link">{{ __lang('instructor-chat') }}</a></li>
                            <li class="nav-item"><a href="{{ route('student.forum.index') }}" class="nav-link">{{ __lang('student-forum') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(setting('menu_show_tests')==1)
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="fas fa-check-circle"></i><span>{{ setting('label_take_test',__lang('Exams')) }}</span></a>
                        <ul class="dropdown-menu dropdown-border">
                            <li class="nav-item"><a href="{{ route('student.test.index') }}" class="nav-link">{{ __lang('browse-exams') }}</a></li>
                            <!--<li class="nav-item"><a href="{{ route('student.test.statement') }}" class="nav-link">Result Card</a></li>-->
                        </ul>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('student.student.mysessions_results') }}"
                           class="nav-link">
                            <i class="fas fa-file-pdf"></i><span> Results</span>
                        </a>
                    </li>

                    @if(session()->get('original_user'))
                        <li class="nav-item">
                            <a href="{{ route('impersonate.revert') }}"
                               class="nav-link">
                                <i class="fas fa-backward"></i><span> Back to admin panel</span>
                            </a>
                        </li>
                    @endif
</ul>
</div>
</nav>

<!-- Main Content -->
<div class="main-content">
<section class="section">
@section('title-crumb')
<div class="section-header">
@hasSection('innerTitle')
    {{--MARUF START--}}
    {{--@php
        $course_name = explode(' - Batch ',@$innerTitle);
        $course_name = @$course_name[0];
        dd($course_name);
    @endphp--}}
    <h1>@yield('innerTitle')</h1>
    {{--MARUF END--}}
@endif
<div class="section-header-breadcrumb">
    @yield('breadcrumb')
</div>
</div>
@show

<div class="section-body">
@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))

        <div class="alert alert-{{ $msg }} alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                {!! clean(Session::get('alert-' . $msg)) !!}
            </div>
        </div>
    @endif
@endforeach
@if(Session::has('flash_message'))
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean(Session::get('flash_message')) !!}
        </div>
    </div>
@endif

@if(isset($flash_message))
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean($flash_message) !!}
        </div>
    </div>
@endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.1.0/moment.min.js"></script>
<!-- END SIMPLE MODAL MARKUP -->
<script>
function update() {
$('#clock_1').html('GMT: '+moment.utc().format('DD-MMM hh:mm A'));
}
setInterval(update, 1000);

function update_2() {
$('#clock_2').html(moment().format('DD-MMM hh:mm A'));
}
setInterval(update_2, 1000);

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

<script src="{{ tasset('js/bonwhatsapp.js') }}"></script>

<script src="{{ asset('client/app/lib.js') }}"></script>
{!! setting('general_foot_scripts') !!}
@yield('footer')
</body>
</html>
