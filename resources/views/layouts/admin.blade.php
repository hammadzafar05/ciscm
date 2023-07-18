<!DOCTYPE html>
<html {!! langMeta() !!} >
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('pageTitle',isset($pageTitle)? $pageTitle:__('default.admin')) - {{ env('APP_NAME') }}</title>

    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif

<!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/summernote/summernote-bs4.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('client/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.css') }}"/>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/admin.css') }}">
    <script src="{{ asset('client/themes/admin/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('client/vendor/jquery-ui-1.11.4/jquery-ui.min.js') }}"></script>
    <link href="{{ asset('client/vendor/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('client/vendor/select2/js/select2.min.js') }}"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script>
        var BASE_URL = '{{ url("/") }}';
    </script>

    <script src="{{ asset('client/app/lib.js') }}"></script>
    @yield('header')

</head>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1" id="content">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @hasSection('search-form')
                @yield('search-form')
            @else
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
            @endif


            <ul class="navbar-nav navbar-right">

                <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        @if(!empty(Auth::user()->picture) && file_exists(Auth::user()->picture))
                            <img alt="image" src="{{ asset(Auth::user()->picture) }}" class="rounded-circle mr-1">
                        @else
                            <img alt="image" src="{{ asset('client/themes/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                        @endif
                        <div class="d-sm-none d-lg-inline-block">@lang('default.hi'), {{ Auth()->user()->name }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-title">@lang('default.account')</div>
                        <a href="@route('admin.account.profile')" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> @lang('default.profile')
                        </a>
                        <a href="@route('admin.account.email')" class="dropdown-item has-icon">
                            <i class="fas fa-envelope"></i> @lang('default.change-email')
                        </a>
                        <a href="@route('admin.account.password')" class="dropdown-item has-icon">
                            <i class="fas fa-unlock"></i> @lang('default.change-password')
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
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="{{ url('/') }}">
                        {{ env('APP_NAME') }}
                    </a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="{{ url('/') }}">

                        {{ substr(env('APP_NAME'),0,2) }}

                    </a>
                </div>
                <ul class="sidebar-menu">
                    @if($partner_sidebar_logo)
                        <li class="menu-header">
                            <center>
                                <img class="rounded img-fluid img-thumbnail"
                                     src="{{ $partner_sidebar_logo }}"
                                     width="120"
                                     onerror="this.style.display='none'">
                            </center>
                            <hr>
                        </li>
                    @endif

                    <li class="menu-header">@lang('default.menu')</li>
                    <li><a href="@route('admin.dashboard')" class="nav-link"><i class="fas fa-fire"></i><span>@lang('default.dashboard')</span></a></li>



                    @if(($view_admin_role == 'Partner') || ($view_admin_role == 'Super Administrator'))
                        <li class="dropdown  {{ Request::segment(2) == 'mailbox' || strpos(Request::segment(2), "mailbox")!==FALSE? 'active':'' }} mailbox-sidebar">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-book"></i>
                                <span>Mailbox</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::segment(2) == "mailbox" || Request::segment(3)=="" || Request::segment(3)=="Inbox"?"active":"" }}">
                                    <a class="nav-link" href="{{ url('/admin/mailbox') }}">
                                        Inbox
                                        @if(count(getUnreadMessages()) > 0)
                                            <div class="pull-right mr-20">
                                                <span class="badge badge-primary pull-right">{{count(getUnreadMessages())}}</span>
                                            </div>
                                        @endif
                                    </a>
                                </li>
                                <li class="{{ Request::segment(2) == "mailbox-create"?"active":"" }}">
                                    <a class="nav-link" href="{{ url('/admin/mailbox-create') }}">
                                        Compose
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if(($view_admin_role == 'Partner'))
                        <li><a href="{{ route('admin.partner.invoice_lists') }}" class="nav-link"><i class="fas fa-fire"></i><span>{{ __lang('invoices') }}</span></a></li>
                    @endif
                    @can('access-group','course')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-book"></i><span>{{ __lang('courses-sessions') }}</span></a>
                            <ul class="dropdown-menu">

                                <li class="dropdown">
                                    <a href="#" class="nav-link has-dropdown"><span>{{ __lang('add-new') }}</span></a>
                                    <ul class="dropdown-menu">
                                        @can('access','add_course')
                                            <li><a class="nav-link" href="@route('admin.session.addcourse')">{{ __lang('online-course') }}</a></li>
                                        @endcan
                                        @can('access','add_session')
                                            <li><a class="nav-link" href="{{ route('admin.student.addsession',['type'=>'s']) }}">{{ __lang('training-session')  }}</a></li>
                                            <li><a class="nav-link" href="{{ route('admin.student.addsession',['type'=>'b']) }}">{{ __lang('training-online') }}</a></li>
                                        @endcan
                                    </ul>
                                </li>

                                <li><a class="nav-link" href="{{ route('admin.student.sessions') }}">{{ __lang('all-courses-session') }} </a></li>
                                <li><a class="nav-link" href="{{ route('admin.session.groups') }}">{{  __lang('manage-categories') }}</a></li>
                                <li><a class="nav-link" href="{{ route('admin.student.invoices') }}">{{ __lang('invoices') }}</a></li>
                                @if($view_admin_role == 'Super Administrator')
                                    <li><a class="nav-link" href="{{ route('admin.student.application_form') }}">Application Form</a></li>
                                    <li><a class="nav-link" href="{{ route('admin.partner.invoices') }}">Partner {{ __lang('invoices') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endcan

                    @can('access-group','student')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span><?= __lang('students') ?></span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_student')
                                    <li><a class="nav-link" href="@route('admin.student.add')">{{ __lang('add-student') }}</a></li>@endcan
                                @can('access','view_students')
                                    <li><a class="nav-link" href="@route('admin.student.index')">{{ __lang('all-students') }}</a></li>@endcan
                                @can('access','view_students')
                                    <li><a class="nav-link" href="@route('admin.student.active')">{{ __lang('active-students') }}</a></li>@endcan
                                @can('access','bulk_enroll')
                                    <li><a class="nav-link" href="@route('admin.student.massenroll')">{{ __lang('bulk-enroll') }}</a></li>@endcan
                                @can('access','export_student')
                                    <li><a class="nav-link" href="@route('admin.student.import')">{{ __lang('import-export') }}</a></li>@endcan
                                @can('access','message_students')
                                    <li><a class="nav-link" href="@route('admin.student.mailsession')">{{ __lang('message-students') }}</a></li>@endcan
                                @can('access','view_students')
                                    <li><a class="nav-link" href="@route('admin.student.code')">{{ __lang('verify-code') }}</a></li>@endcan

                                @if($view_admin_role == 'Super Administrator')
                                    <li><a class="nav-link" href="@route('admin.student.partner.request')">Enrollment Request by Partner</a></li>
                                @endif
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','noticeboard')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-download"></i><span>{{ __lang('noticeboard') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_noticeboard')
                                    <li><a class="nav-link" href="@route('admin.noticeboard.add')">{{ __lang('create-noticeboard') }}</a></li>@endcan
                                @can('access','view_noticeboards')
                                    <li><a class="nav-link" href="@route('admin.noticeboard.index')">{{ __lang('all-noticeboard') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access-group','classes')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-chalkboard-teacher "></i><span>{{ __lang('classes') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_class')
                                    <li><a class="nav-link" href="@route('admin.lesson.add')">{{ __lang('add-class') }}</a></li>@endcan
                                @can('access','view_classes')
                                    <li><a class="nav-link" href="@route('admin.lesson.index')">{{ __lang('all-classes') }}</a></li>@endcan
                                @can('access','view_class_groups')
                                    <li><a class="nav-link" href="@route('admin.lesson.groups')">{{ __lang('manage-class-groups') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','video')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-video"></i><span>{{ __lang('video-library') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_video')
                                    <li><a class="nav-link" href="@route('admin.video.add')">{{ __lang('add-video') }}</a></li>@endcan
                                @can('access','view_videos')
                                    <li><a class="nav-link" href="@route('admin.video.index')">{{ __lang('all-videos') }}</a></li>@endcan
                                @can('access','view_video_space')
                                    <li><a class="nav-link" href="@route('admin.video.disk')">{{ __lang('disk-space-usage') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access-group','attendance')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-table"></i><span>{{ __lang('attendance') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','set_attendance')
                                    <li><a class="nav-link" href="@route('admin.student.attendance')">{{ __lang('attendance') }}</a></li>@endcan
                                @can('access','set_bulk_attendance')
                                    <li><a class="nav-link" href="@route('admin.student.attendancebulk')">{{ __lang('attendance') }} ({{ __lang('bulk') }})</a></li>@endcan
                                @can('access','set_import_attendance')
                                    <li><a class="nav-link" href="@route('admin.student.attendanceimport')">{{ __lang('attendance') }} ({{ __lang('import') }})</a></li>@endcan
                                @can('access','create_certificate_list')
                                    <li><a class="nav-link" href="@route('admin.student.certificatelist')">{{ __lang('certificate-list') }}</a></li>@endcan
                                @can('access','set_attendance_dates')
                                    <li><a class="nav-link" href="@route('admin.student.attendancedate')">{{ __lang('attendance-dates') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','homework')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-edit"></i><span>{{ __lang('homework') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_homework')
                                    <li><a class="nav-link" href="@route('admin.assignment.add')">{{ __lang('add-homework') }}</a></li>@endcan
                                @can('access','view_homework_list')
                                    <li><a class="nav-link" href="@route('admin.assignment.index')">{{ __lang('view-all') }}</a></li>
                                @endcan
                                
                                <li><a class="nav-link" href="{{url('admin/pending-assignment-marks')}}">{{ __lang('pending_assignment_marks') }}</a>
                                </li>

                            </ul>
                        </li>
                    @endcan
                    @can('access-group','partner_mark_management')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-list-alt"></i><span>{{ __lang('mark-management') }}</span></a>
                            <ul class="dropdown-menu">
                                @if(($view_admin_role == 'Partner'))
                                    @can('access','add_partner_mark')
                                        <li><a class="nav-link" href="@route('admin.partnermark.add')">{{ __lang('add') }}</a></li>@endcan
                                @endif

                                @can('access','view_partner_mark')
                                    <li><a class="nav-link" href="@route('admin.partnermark.index')">{{ __lang('view') }}</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcan
                    @can('access-group','revision_notes')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-list-alt"></i><span>{{ __lang('revision-notes') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_note')
                                    <li><a class="nav-link" href="@route('admin.homework.add')">{{ __lang('add-note') }}</a></li>@endcan
                                @can('access','view_notes')
                                    <li><a class="nav-link" href="@route('admin.homework.index')">{{ __lang('view-archive') }}</a></li>@endcan

                            </ul>
                        </li>
                    @endcan
                    @can('access-group','downloads')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-download"></i><span>{{ __lang('downloads') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_download')
                                    <li><a class="nav-link" href="@route('admin.download.add')">{{ __lang('create-download') }}</a></li>@endcan
                                @can('access','view_downloads')
                                    <li><a class="nav-link" href="@route('admin.download.index')">{{ __lang('all-downloads') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','discussions')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-comments"></i><span>{{ __lang('discussions') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','view_discussions')
                                    <li><a class="nav-link" href="@route('admin.discuss.index')">{{ __lang('instructor-chat') }}</a></li>@endcan
                                @can('access','view_forum_topics')
                                    <li><a class="nav-link" href="@route('admin.forum.index')">{{ __lang('student-forum') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','tests')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-check-circle"></i><span>{{ __lang('tests') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','view_tests')
                                    <li><a class="nav-link" href="@route('admin.test.add')">{{ __lang('add-test') }}</a></li>@endcan
                                @can('access','add_test')
                                    <li><a class="nav-link" href="@route('admin.test.index')">{{ __lang('all-tests') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access-group','survey')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-flag-checkered"></i><span>{{ __lang('surveys') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_survey')
                                    <li><a class="nav-link" href="@route('admin.survey.add')">{{ __lang('add-survey') }}</a></li>@endcan
                                @can('access','view_surveys')
                                    <li><a class="nav-link" href="@route('admin.survey.index')">{{ __lang('all-surveys') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','certificates')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-pdf"></i><span>{{ __lang('certificates') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_certificate')
                                    <li><a class="nav-link" href="@route('admin.certificate.add')">{{ __lang('create-certificate') }}</a></li>@endcan
                                @can('access','view_certificates')
                                    <li><a class="nav-link" href="@route('admin.certificate.index')">{{ __lang('manage-certificates') }}</a></li>@endcan
                                @can('access','view_certificates')
                                    <li><a class="nav-link" href="@route('admin.certificate.track')">{{ __lang('track-certificates') }}</a></li>@endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','external_certificates')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-code"></i><span>{{ __lang('external_certificates') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_external_certificate')
                                    <li>
                                        <a class="nav-link"
                                           href="{{ route('admin.external_certificates.create') }}">
                                            {{ __lang('add') }} {{ __lang('external_certificate') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('access','view_external_certificates')
                                    <li>
                                        <a class="nav-link"
                                           href="{{ url('/admin/external_certificates') }}">
                                            {{ __lang('view') }} {{ __lang('external_certificate') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('access','add_external_certificate')
                                    <li>
                                        <a class="nav-link"
                                           href="{{ route('admin.external_certificates.import') }}">
                                            {{ __lang('import') }} {{ __lang('external_certificate') }}
                                        </a>
                                    </li>
                                @endcan    
                                
                                @can('access','verification_ambassador')
                                    <li>
                                        <a class="nav-link"
                                           href="{{url('admin/get_verification_ambassador') }}">
                                             {{ __lang('ambassador') }}
                                        </a>
                                    </li>
                                @endcan      
                                
                                @can('access','verification_partner')
                                    <li>
                                        <a class="nav-link"
                                           href="{{url('admin/get_verification_partner') }}">
                                             {{ __lang('partner') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access-group','reports')
                        <li class="dropdown">
                            <a href="@route('admin.report.index')" class="nav-link"><i class="fas fa-chart-bar"></i><span>{{ __lang('reports') }}</span></a>

                        </li>
                    @endcan
                    @can('access-group','blog')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-pencil-alt"></i><span>{{ __lang('blog') }}</span></a>
                            <ul class="dropdown-menu">

                                @can('access','view_blog')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.blog-posts.index') }}" class="nav-link">@lang('default.manage-posts')</a>
                                    </li>
                                @endcan

                                @can('access','add_blog')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.blog-posts.create') }}" class="nav-link">@lang('default.create-post')</a>
                                    </li>
                                @endcan

                                @can('access','manage_blog_categories')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.blog-categories.index') }}" class="nav-link">@lang('default.manage-categories')</a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    @can('access-group','files')
                        <li class="dropdown">
                            <a onclick="window.open('{{ route('admin.filemanager.home') }}', '{{ __lang('filemanager') }}', 'width=1100, height=530',true);" href="javascript:;"
                               href="#" class="nav-link"><i class="fas fa-file-archive"></i><span>{{ __lang('filemanager') }}</span>
                             </a>

                        </li>
                        
                        <li class="dropdown">
                            <a href="{{url('admin/view-our-delegats')}}" class="nav-link"><i class="fas fa-file-archive"></i><span>Our Delegates</span>
                            </a>
                        </li>
                    @endcan

                    @can('access-group','articles')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-pencil-ruler"></i><span>{{ __lang('articles') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','add_article')
                                    <li><a class="nav-link" href="{{ route('admin.articles.create') }}">{{ __lang('add-articles') }}</a></li>@endcan
                                @can('access','view_articles')
                                    <li><a class="nav-link" href="{{ route('admin.articles.index') }}">{{ __lang('view-articles') }}</a></li>@endcan

                            </ul>
                        </li>
                    @endcan

                    @can('access-group','settings')
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-cogs"></i><span>{{ __lang('settings') }}</span></a>
                            <ul class="dropdown-menu">
                                @can('access','edit_site_settings')
                                    <li><a class="nav-link" href="@route('admin.setting.index')">{{ __lang('site-settings') }}</a></li>@endcan
                                @can('access','edit_registration_field')
                                    <li><a class="nav-link" href="@route('admin.setting.fields')">{{ __lang('custom-student-fields') }}</a></li>@endcan

                                @can('access','edit_site_settings')
                                    <li><a class="nav-link" href="@route('admin.setting.language')">{{ __lang('language') }}</a></li>@endcan
                                @can('access','view_themes')
                                    <li><a class="nav-link" href="@route('admin.templates')">{{ __lang('site-theme') }}</a></li>@endcan
                                @can('access','view_payment_methods')
                                    <li><a class="nav-link" href="@route('admin.payment-gateways')">{{ __lang('payment-methods') }}</a></li>@endcan

                                @can('access','view_coupons')
                                    <li><a class="nav-link" href="@route('admin.payment.coupons')">{{ __lang('coupons') }}</a></li>@endcan
                                @can('access','manage_currencies')
                                    <li><a class="nav-link" href="@route('admin.setting.currencies')">{{ __lang('currencies') }}</a></li>@endcan
                                @can('access','configure_sms_gateways')
                                    <li><a class="nav-link" href="@route('admin.smsgateway.index')">{{ __lang('sms-setup') }}</a></li>@endcan

                                @can('access','view_roles')
                                    <li><a class="nav-link" href="@route('admin.roles.index')">{{ __lang('roles') }}</a></li>@endcan
                                @can('access','view_admins')
                                    <li><a class="nav-link" href="@route('admin.admins.index')">{{ __lang('administrators-instructors') }}</a></li>@endcan
                                @can('access','view_test_grades')
                                    <li><a class="nav-link" href="@route('admin.setting.testgrades')">{{ __lang('grades') }}</a></li>@endcan
                                @can('access','view_widgets')
                                    <li><a class="nav-link" href="@route('admin.widget.index')">{{ __lang('homepage-widgets') }}</a></li>@endcan
                                <li class="dropdown">
                                    <a href="#" class="nav-link has-dropdown"><span>@lang('default.menus')</span></a>
                                    <ul class="dropdown-menu">

                                        <li><a class="nav-link" href="{{ route('admin.menus.header') }}">@lang('default.header-menu')</a></li>

                                        <li><a class="nav-link" href="{{ route('admin.menus.footer') }}">@lang('default.footer-menu')</a></li>

                                    </ul>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="nav-link has-dropdown"><span>{{ __lang('notification-messages') }}</span></a>
                                    <ul class="dropdown-menu">
                                        @can('access','view_email_notifications')
                                            <li><a class="nav-link" href="@route('admin.messages.emails')">{{ __lang('email-notifications') }}</a></li>
                                        @endcan
                                        @can('access','view_sms_notifications')
                                            <li><a class="nav-link" href="@route('admin.messages.sms')">{{ __lang('sms-notifications')  }}</a></li>
                                        @endcan
                                    </ul>
                                </li>


                                @can('access','upgrade_database')
                                    <li><a class="nav-link" href="@route('admin.setting.migrate')">{{ __lang('update') }}</a></li>@endcan


                            </ul>
                        </li>

                        {{--MARUF START--}}
                        @foreach(customWebsiteMenu() as $key => $menu)
                            <li class="@if($menu['children']) dropdown @endif custom-shomvabona-menu">
                                <a class="nav-link @if($menu['children'])  has-dropdown @endif" href="{{ $menu['url'] }}">
                                    <i class="fas fa-circle"></i><span>{{ $menu['label'] }}</span></a>
                                @if($menu['children'])
                                    <ul class="dropdown-menu">
                                        @foreach($menu['children'] as $childMenu)
                                            <li><a class="nav-link" href="{{ route('admin.setting.'.$childMenu['url']) }}">{{ $childMenu['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        {{--MARUF END--}}
                    @endcan


                </ul>

                @if(session()->get('original_user'))
                    <li class="nav-item">
                        <a href="{{ route('impersonate.revert') }}"
                           class="nav-link">
                            <i class="fas fa-backward"></i><span> Back to admin panel</span>
                        </a>
                    </li>
                @endif

                @if(config('app.credits')==TRUE)
                    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                        <a target="_blank" href="https://intermaticsng.com/docs/category/1" class="btn btn-primary btn-lg btn-block btn-icon-split">
                            <i class="fa fa-question-circle"></i> @lang('default.help')
                        </a>
                    </div>
                @endif


            </aside>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    @if(isset($pageTitle))
                        <h1>{{ $pageTitle }}</h1>
                    @endif

                    @hasSection('innerTitle')
                        <h1>@yield('innerTitle')</h1>
                    @endif
                    @hasSection('breadcrumb')
                        <div class="section-header-breadcrumb">

                            @yield('breadcrumb')


                        </div>
                    @endif


                </div>

                <div class="section-body" id="layout_content">


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
                        <div class="alert alert-success alert-dismissible show fade flash_message-3">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {!! clean(Session::get('flash_message')) !!}
                            </div>
                        </div>
                    @endif

                    @if(isset($flash_message))
                        <div class="alert alert-success alert-dismissible show fade flash_message-4">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {!! clean($flash_message) !!}
                            </div>
                        </div>
                    @endif

                    @if(Session::has('flash_error_message'))
                        <div class="alert alert-danger alert-dismissible show fade  flash_error_message-3">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {!! clean(Session::get('flash_error_message')) !!}
                            </div>
                        </div>
                    @endif


                    @yield('content')
                </div>
            </section>

        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __lang('copyright') }} &copy; {{ date('Y') }} <a href="{{ config('app.author_url') }}">{{ config('app.app_author') }}</a>
            </div>
            <div class="footer-right">

            </div>
        </footer>
    </div>
</div>

<!-- General JS Scripts -->

<script src="{{ asset('client/themes/admin/assets/modules/popper.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/moment.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->
<script src="{{ asset('client/themes/admin/assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/chart.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>


<!-- Template JS File -->
<script src="{{ asset('client/themes/admin/assets/js/scripts.js') }}"></script>
<script src="{{ asset('client/themes/admin/assets/js/custom.js') }}"></script>

<div class="modal fade" id="generalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generalModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="genmodalinfo">
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="generalLargeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generalLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="genLargemodalinfo">
            </div>

        </div>
    </div>
</div>

<!-- END SIMPLE MODAL MARKUP -->
<script>
    function openModal(title, url) {
        $('#genmodalinfo').html(' <img  src="{{ asset('img/ajax-loader.gif')  }}');
        $('#generalModalLabel').text(title);
        $('#genmodalinfo').load(url);
        $('#generalModal').modal();
    }

    function openLargeModal(title, url) {
        $('#genLargemodalinfo').html(' <img  src="{{ asset('img/ajax-loader.gif')  }}');
        $('#generalLargeModalLabel').text(title);
        $('#genLargemodalinfo').load(url);
        $('#generalLargeModal').modal();
    }

    function openPopup(url) {
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
        return false;
    }
</script>

@yield('footer')

</body>
</html>
