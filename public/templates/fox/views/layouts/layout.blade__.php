<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('page-title')</title>
    <meta name="description" content="@yield('meta-description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@if(!empty(setting('image_icon')))
    <!--====== Favicon Icon ======-->
        <link rel="shortcut icon" href="{{ asset(setting('image_icon')) }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ tasset('css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/animate.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/aos.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/style') }}">
    <link rel="stylesheet" href="{{ tasset('css/fontawesome-all.min.css') }}">


    @yield('header')
    {!!  setting('general_header_scripts')  !!}
    @if(optionActive('top-bar'))
        <style>
            @if(!empty(toption('top-bar','bg_color')))
                div.bg-top{
                background-color: #{{ toption('top-bar','bg_color') }};
            }
            @endif

                 @if(!empty(toption('top-bar','font_color')))
                    .topper .icon a,.topper .icon i{
                        color: #{{ toption('top-bar','font_color') }};
                    }
                @endif



        </style>
    @endif

    @if(optionActive('navigation'))
        <style>
            @if(!empty(toption('navigation','bg_color')))
                .ftco-navbar-light .container, .ftco-navbar-light .navbar-nav > .nav-item .dropdown-menu{
                background-color: #{{ toption('navigation','bg_color') }};
            }
            @endif

                     @if(!empty(toption('navigation','font_color')))
                .ftco-navbar-light .navbar-nav > .nav-item > .nav-link , .ftco-navbar-light .navbar-nav > .nav-item .dropdown-menu a{
                color: #{{ toption('navigation','font_color') }};
            }
            @endif



        </style>
    @endif


    <style>
        @if(optionActive('footer'))


            @if(!empty(toption('footer','bg_color')))

            .ftco-footer  {
            background-color: #{{ toption('footer','bg_color') }};
            }

        @endif

            @if(!empty(toption('footer','font_color')))
                .ftco-footer .ftco-footer-widget h2, .ftco-footer .block-21 .text .heading a, .ftco-footer .block-21 .text .meta > div a, .ftco-footer a,.ftco-footer .block-23 ul li span,.ftco-footer .ftco-footer-widget ul li a span,.ftco-footer p {
                    color: #{{ toption('footer','font_color') }};
                }
            @endif

        @endif-



            @if(optionActive('page-title'))
                @if(!empty(toption('page-title','bg_color')))
                    section.hero-wrap{
                    background-color: #{{ toption('page-title','bg_color') }} ;
                }
                @endif

                 @if(!empty(toption('page-title','font_color')))
                    hero-wrap.hero-wrap-2 .slider-text .bread,.hero-wrap.hero-wrap-2 .slider-text .breadcrumbs span a,.hero-wrap.hero-wrap-2 .slider-text .bread{
                    color: #{{ toption('page-title','font_color') }};
                }
                @endif

        @endif
    </style>

</head>
<body>
<div class="bg-top navbar-light">
    <div class="container">
        <div class="row no-gutters d-flex align-items-center align-items-stretch">
            <div class="col-md-4 col-sm-4 d-flex align-items-center py-4">
                <a class="navbar-brand logo-box" href="{{ url('/') }}">
                    @if(!empty(setting('image_logo')))
                        <img src="{{ asset(setting('image_logo')) }}" >
                    @else
                        {{ setting('general_site_name') }}
                    @endif

                </a>
            </div>
            <div class="col-lg-8 col-sm-8 d-block hide-mobile"  >
                <div class="row d-flex">

                    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4 pt-2 offset-4 mt-3">
                        @guest

                        <div class="text">
                           <span class="icon"><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> @lang('default.login')</a></span>
                        </div>
                        <div class="text">
                            <span class="icon"><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> @lang('default.register')</a></span>
                        </div>
                        @else
                            <div class="text">
                                <span class="icon"><a href="{{ route('home') }}"><i class="fas fa-user-circle"></i> @lang('default.my-account')</a></span>
                            </div>
                            <div class="text">
                                <span class="icon"><a  href="{{ route('logout') }}"
                                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"  ><i class="fa fa-sign-out-alt"></i> @lang('default.logout')</a></span>
                            </div>

                        @endif


                    </div>
                    @if(toption('top-bar','order_button')==1)
                    <div class="col-md topper d-flex align-items-center justify-content-end">
                        <p class="mt-4 pt-2">
                            <a href="{{ route('cart') }}" class="btn rounded  py-2 px-3 btn-primary d-flex align-items-center justify-content-center">
                                <span><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}</span>
                            </a>
                        </p>
                    </div>
                        @endif


                </div>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST"  class="int_hide">
    @csrf
</form>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container d-flex align-items-center px-4">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> {{ __lang('menu') }}
        </button>
        <form action="{{ route('courses') }}" class="searchform order-lg-last hide-mobile">
            <div class="form-group d-flex">
                <input type="text" class="form-control pl-3" name="filter"  placeholder="{{ __lang('search-courses') }}" >
                <button type="submit" placeholder="" class="form-control search"><span class="ion-ios-search"></span></button>
            </div>
        </form>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav mr-auto">

                @foreach(headerMenu() as $key => $menu)
                    <li class="nav-item @if($menu['children']) dropdown @endif">
                        <a class="nav-link @if($menu['children'])  dropdown-toggle @endif" @if($menu['children']) id="navbarDropdown{{ $key }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"   @endif href="{{ $menu['url'] }}" >{{ $menu['label'] }}</a>
                        @if($menu['children'])
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown{{ $key }}">
                                @foreach($menu['children'] as $childMenu)
                                <a class="dropdown-item" href="{{ $childMenu['url'] }}">{{ $childMenu['label'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
                    @if(toption('top-bar','cart')==1)
                        <li class="d-md-none d-lg-none d-xl-none"   ><a href="{{ route('cart') }}"><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}@if(getCart()->getTotalItems()>0) ({{ getCart()->getTotalItems() }}) @endif</a></li>
                    @endif
                    @guest
                        <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> @lang('default.login')</a></li>
                        <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __lang('register') }}</a></li>
                    @else

                        <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('home') }}"><i class="fa fa-user-circle"></i> @lang('default.my-account')</a></li>
                        <li  class="d-md-none d-lg-none d-xl-none"  ><a    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{ route('logout') }}"><i class="fa fa-sign-out-alt"></i> {{ __lang('logout') }}</a></li>

                    @endif

            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->
@hasSection('inline-title')
    <section class="hero-wrap hero-wrap-2"    @if(!empty(toption('page-title','image')))  style="background-image: url('{{ asset(toption('page-title','image')) }}');"  @elseif(empty(toption('page-title','bg_color'))) style="background-image: url('{{ tasset('images/bg_1.jpg') }}');"   @endif   >
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-2 bread">@yield('inline-title')</h1>
                    @hasSection('crumb')
                    <p class="breadcrumbs"><span class="mr-2"><a href="@route('homepage')">@lang('default.home') <i class="ion-ios-arrow-forward"></i></a></span>
                        @yield('crumb')
                    </p>
                        @endif
                </div>
            </div>
        </div>
    </section>

@endif

@include('partials.flash_message')
@yield('content')

<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">{{ __lang('contact-us') }}</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            @if(!empty(toption('footer','address')))
                            <li><span class="icon icon-map-marker"></span><span class="text">{{ toption('footer','address') }}</span></li>
                            @endif

                            @if(!empty(toption('footer','telephone')))
                            <li><a href="#"><span class="icon icon-phone"></span><span class="text">{{ toption('footer','telephone') }}</span></a></li>
                            @endif

                            @if(!empty(toption('footer','email')))
                            <li><a href="mailto:{{ toption('footer','email') }}"><span class="icon icon-envelope"></span><span class="text">{{ toption('footer','email') }}</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">{{ __t('recent-posts') }}</h2>
                    @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(2)->get() as $post)

                    <div class="block-21 mb-4 d-flex">
                        @if(!empty($post->cover_photo))
                        <a class="blog-img mr-4" style="background-image: url({{ asset($post->cover_photo) }});"></a>
                        @endif
                        <div class="text">
                            <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                            <div class="meta">
                                <div><a href="#"><span class="icon-calendar"></span> {{ \Carbon\Carbon::parse($post->publish_date)->format('M d, Y') }}</a></div>
                                @if($post->admin)
                                <div><a @if($post->admin->public == 1)  href="{{ route('instructor',['admin'=>$post->admin_id]) }}" @endif ><span class="icon-person"></span> {{ $post->admin->user->name.' '.$post->admin->user->last_name }}</a></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            @foreach(footerMenu() as $menu)
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5 ml-md-4">
                    <h2 class="ftco-heading-2">{{ $menu['label'] }}</h2>

                    <ul class="list-unstyled">
                        @foreach($menu['children'] as $childMenu)
                        <li><a href="{{ $childMenu['url'] }}"><span class="ion-ios-arrow-round-forward mr-2"></span>{{ $childMenu['label'] }}</a></li>
                        @endforeach
                    </ul>


                </div>
            </div>
            @endforeach


            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">{{ __t('stay-updated') }}</h2>
                    @if(!empty(toption('footer','newsletter-code')))
                        {!! toption('footer','newsletter-code') !!}
                    @else
                    <form action="#" class="subscribe-form">
                        <div class="form-group">
                            <input type="text" class="form-control mb-2 text-center" placeholder="Enter email address">
                            <input type="submit" value="Subscribe" class="form-control submit px-3">
                        </div>
                    </form>
                    @endif
                </div>
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2 mb-0">{{ __t('connect-with-us') }}</h2>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">

                        @if(!empty(toption('footer','social_facebook')))
                            <li class="ftco-animate"><a href="{{ toption('footer','social_facebook') }}"><span class="icon-facebook"></span></a></li>
                        @endif
                        @if(!empty(toption('footer','social_twitter')))
                                <li class="ftco-animate"><a href="{{ toption('footer','social_twitter') }}"><span class="icon-twitter"></span></a></li>
                        @endif
                        @if(!empty(toption('footer','social_instagram')))
                                <li class="ftco-animate"><a href="{{ toption('footer','social_instagram') }}"><span class="icon-instagram"></span></a></li>
                        @endif
                        @if(!empty(toption('footer','social_youtube')))
                                <li class="ftco-animate"><a href="{{ toption('footer','social_youtube') }}"><span class="icon-youtube"></span></a></li>
                        @endif
                        @if(!empty(toption('footer','social_linkedin')))
                                <li class="ftco-animate"><a href="{{ toption('footer','social_linkedin') }}"><span class="icon-linkedin"></span></a></li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">

                <p>{!! clean( fullstop(toption('footer','credits')) ) !!}</p>
            </div>
        </div>
    </div>
</footer>



<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


<script src="{{ tasset('js/jquery.min.js') }}"></script>
<script src="{{ tasset('js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ tasset('js/popper.min.js') }}"></script>
<script src="{{ tasset('js/bootstrap.min.js') }}"></script>
<script src="{{ tasset('js/jquery.easing.1.3.js') }}"></script>
<script src="{{ tasset('js/jquery.waypoints.min.js') }}"></script>
<script src="{{ tasset('js/jquery.stellar.min.js') }}"></script>
<script src="{{ tasset('js/owl.carousel.min.js') }}"></script>
<script src="{{ tasset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ tasset('js/aos.js') }}"></script>
<script src="{{ tasset('js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ tasset('js/scrollax.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&amp;sensor=false"></script>
<script src="{{ tasset('js/google-map.js') }}"></script>
<script src="{{ tasset('js/main.js') }}"></script>
@yield('footer')
{!!  setting('general_foot_scripts')  !!}
</body>

</html>
