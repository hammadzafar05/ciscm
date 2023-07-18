<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('page-title')</title>
    <meta name="description" content="@yield('meta-description')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     @if(!empty(setting('image_icon')))
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ tasset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/style') }}">

     @yield('header')
     {!!  setting('general_header_scripts')  !!}
     @if(optionActive('top-bar'))
         <style>
             @if(!empty(toption('top-bar','bg_color')))
                div.header-top{
                 background-color: #{{ toption('top-bar','bg_color') }};
             }
             @endif

                     @if(!empty(toption('top-bar','font_color')))
                .header-area .header-top .header-info-left ul li,.header-area .header-top .header-info-right .header-social li a, .header-area .header-top a, .header-area .header-top button,.header-area .header-top .header-info-right ul li a,.header-area .header-top .header-info-right ul li a i{
                 color: #{{ toption('top-bar','font_color') }};
             }
             @endif

            @if(!empty(toption('top-bar','social_bg_color')))
            .header-area .header-top .header-left-social .header-social{
                 background: #{{ toption('top-bar','social_bg_color') }};
             }
             @endif

         </style>
     @endif


     @if(optionActive('navigation'))
         <style>
             @if(!empty(toption('navigation','bg_color')))
                div.header-bottom{
                 background-color: #{{ toption('navigation','bg_color') }};
             }
             @endif

                     @if(!empty(toption('navigation','font_color')))
                .main-header .main-menu ul li a, .header-area .header-bottom .menu-wrapper .main-menu > nav > ul li > a{
                 color: #{{ toption('navigation','font_color') }};
             }
             @endif

                    @if(!empty(toption('navigation','logo_bg')))
                .header-area .header-bottom .logo{
                 background-color: #{{ toption('navigation','logo_bg') }};
             }
             @endif

         </style>
     @endif
     <style>
         @if(optionActive('footer'))

            @if(!empty(toption('footer','image')))

                    .footer-bg {
             background: url({{ toption('footer','image') }});
         }

         @endif

            @if(!empty(toption('footer','bg_color')))

            .footer-area  {
             background-color: #{{ toption('footer','bg_color') }};
         }

         @endif

            @if(!empty(toption('footer','font_color')))
        .footer-area .footer-tittle ul li a,.footer-area .footer-tittle h4,.footer-area .footer-social a i,.footer-bottom-area .footer-copy-right p,.footer-bottom-area .footer-copy-right p a, .footer-area .footer-top .single-footer-caption .footer-tittle p,.footer-area .footer-top .single-footer-caption .footer-tittle ul li a, .footer-area .footer-bottom .footer-copy-right p {
             color: #{{ toption('footer','font_color') }};
         }
         @endif

        @endif



            @if(optionActive('page-title'))
                @if(!empty(toption('page-title','bg_color')))
                    .slider-area{
             background-color: #{{ toption('page-title','bg_color') }} ;
         }
         @endif

                 @if(!empty(toption('page-title','font_color')))
                    .slider-area .hero-cap h2,  .slider-area a,.slider-area,.slider-area .crumb{
             color: #{{ toption('page-title','font_color') }};
         }
         @endif

         @endif
     </style>



 </head>
<body>

<!--? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                @if(!empty(setting('image_logo')))
                    <img src="{{ asset(setting('image_logo')) }}" >
                @else
                    {{ setting('general_site_name') }}
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->

<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header ">
            @if(optionActive('top-bar'))
            <div class="header-top d-none d-lg-block">
                <!-- Left Social -->
                <div class="header-left-social">
                    <ul class="header-social">
                        @if(!empty(toption('top-bar','social_facebook')))
                            <li><a href="{{ toption('top-bar','social_facebook') }}"><i class="fab fa-facebook-f"></i></a></li>
                        @endif
                        @if(!empty(toption('top-bar','social_twitter')))
                            <li><a href="{{ toption('top-bar','social_twitter') }}"><i class="fab fa-twitter"></i></a></li>
                        @endif
                        @if(!empty(toption('top-bar','social_instagram')))
                            <li><a href="{{ toption('top-bar','social_instagram') }}"><i class="fab fa-instagram"></i></a></li>
                        @endif
                        @if(!empty(toption('top-bar','social_youtube')))
                            <li><a href="{{ toption('top-bar','social_youtube') }}"><i class="fab fa-youtube"></i></a></li>
                        @endif
                        @if(!empty(toption('top-bar','social_linkedin')))
                            <li><a href="{{ toption('top-bar','social_linkedin') }}"><i class="fab fa-linkedin"></i></a></li>
                        @endif
                    </ul>
                </div>
                <div class="container">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    @if(!empty(toption('top-bar','email')))
                                    <li>{{ toption('top-bar','email') }}</li>
                                    @endif
                                        @if(!empty(toption('top-bar','email')))
                                    <li>{{ toption('top-bar','telephone') }}</li>
                                        @endif
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul>
                                    @if(toption('top-bar','cart')==1)
                                    <li><a href="{{ route('cart') }}"><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}@if(getCart()->getTotalItems()>0) ({{ getCart()->getTotalItems() }}) @endif</a></li>
                                    @endif
                                        @guest
                                            <li   ><a href="{{ route('login') }}"><i class="ti-user"></i> @lang('default.login')</a></li>
                                            <li   ><a href="{{ route('register') }}"><i class="ti-lock"></i> {{ __lang('register') }}</a></li>
                                        @else

                                            <li    ><a href="{{ route('home') }}"><i class="fa fa-user-circle"></i> @lang('default.my-account')</a></li>
                                            <li   ><a    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{ route('logout') }}"><i class="fa fa-sign-out-alt"></i> {{ __lang('logout') }}</a></li>

                                        @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="header-bottom header-sticky">
                <!-- Logo -->
                <div class="logo d-none d-lg-block">
                    <a href="{{ url('/') }}">
                        @if(!empty(setting('image_logo')))
                            <img src="{{ asset(setting('image_logo')) }}" >
                        @else
                            {{ setting('general_site_name') }}
                        @endif
                    </a>
                </div>
                <div class="container">
                    <div class="menu-wrapper">
                        <!-- Logo -->
                        <div class="logo logo2 d-block d-lg-none">
                            <a href="{{ url('/') }}">
                                @if(!empty(setting('image_logo')))
                                    <img src="{{ asset(setting('image_logo')) }}" >
                                @else
                                    {{ setting('general_site_name') }}
                                @endif
                            </a>
                        </div>
                        <!-- Main-menu -->
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    @foreach(headerMenu() as $menu)
                                        <li>
                                            <a href="{{ $menu['url'] }}" >{{ $menu['label'] }}</a>
                                            @if($menu['children'])
                                                <ul class="submenu">
                                                    @foreach($menu['children'] as $childMenu)
                                                        <li><a href="{{ $childMenu['url'] }}" >{{ $childMenu['label'] }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                        @if(toption('top-bar','cart')==1)
                                            <li class="d-md-none d-lg-none d-xl-none"   ><a href="{{ route('cart') }}"><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}@if(getCart()->getTotalItems()>0) ({{ getCart()->getTotalItems() }}) @endif</a></li>
                                        @endif
                                        @guest
                                            <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('login') }}"><i class="ti-user"></i> @lang('default.login')</a></li>
                                        <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('register') }}"><i class="ti-lock"></i> {{ __lang('register') }}</a></li>
                                        @else

                                            <li  class="d-md-none d-lg-none d-xl-none"  ><a href="{{ route('home') }}"><i class="fa fa-user-circle"></i> @lang('default.my-account')</a></li>
                                            <li  class="d-md-none d-lg-none d-xl-none"  ><a    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{ route('logout') }}"><i class="fa fa-sign-out-alt"></i> {{ __lang('logout') }}</a></li>

                                        @endif

                                </ul>
                            </nav>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"  class="int_hide  hidden">
                            @csrf
                        </form>
                        @if(toption('navigation','search_form')==1)
                        <!-- Header-btn -->
                        <div class="header-search d-none d-lg-block">
                            <form action="{{ route('courses') }}" class="form-box f-right ">
                                <input type="text" name="filter"  placeholder="{{ __lang('search-courses') }}">
                                <div class="search-icon">
                                    <i class="fas fa-search special-tag"></i>
                                </div>
                            </form>
                        </div>
                            @endif

                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>
<main>


    @hasSection('inline-title')
        <!-- slider Area Start-->
        <div class="slider-area ">
            <!-- Mobile Menu -->
            <div class="single-slider slider-height2 d-flex align-items-center"
                 @if(!empty(toption('page-title','image')))
                 data-background="{{ asset(toption('page-title','image')) }}"
                 @elseif(empty(toption('page-title','bg_color')))
                 data-background="{{ tasset('assets/img/hero/contact_hero.jpg') }}"
                @endif
            >
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center">
                                <h2>@yield('inline-title')</h2>
                                @hasSection('crumb')
                                    <p class="crumb">
                                        <span><a href="@route('homepage')">@lang('default.home')</a></span>
                                        <span>/</span>
                                        @yield('crumb')
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
    @endif

    @include('partials.flash_message')
    @yield('content')

</main>
<footer class="mt-5">
    <!--? Footer Start-->
    <div class="footer-area footer-bg">
        <div class="container">
            <div class="footer-top footer-padding">
                <!-- footer Heading -->
                <div class="footer-heading">
                    <div class="row justify-content-between">
                        <div class="col-xl-6 col-lg-7 col-md-10">
                            <div class="footer-tittle2">
                                <h4>{{ __t('stay-updated') }}</h4>
                            </div>
                            @if(!empty(toption('footer','newsletter-code')))
                                    {!! toption('footer','newsletter-code') !!}
                                @else
                            <!-- Form -->
                            <div class="footer-form mb-50">
                                <div id="mc_embed_signup">
                                    <form target="_blank" action="#" method="get" class="subscribe_form relative mail_part" novalidate="true">
                                        <input type="email" name="EMAIL" id="newsletter-form-email" placeholder=" Email Address " class="placeholder hide-on-focus" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your email address'">
                                        <div class="form-icon">
                                            <button type="submit" name="submit" id="newsletter-submit" class="email_icon newsletter-submit button-contactForm">
                                                {{ __t('subscribe-now') }}
                                            </button>
                                        </div>
                                        <div class="mt-10 info"></div>
                                    </form>
                                </div>
                            </div>
                            @endif

                        </div>
                        <div class="col-xl-5 col-lg-5">
                            <div class="footer-tittle2">
                                <h4>{{ __t('get-social') }}</h4>
                            </div>
                            <!-- Footer Social -->
                            <div class="footer-social">

                                @if(!empty(toption('footer','social_facebook')))
                                   <a href="{{ toption('footer','social_facebook') }}"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if(!empty(toption('footer','social_twitter')))
                                    <a href="{{ toption('footer','social_twitter') }}"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if(!empty(toption('footer','social_instagram')))
                                    <a href="{{ toption('footer','social_instagram') }}"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if(!empty(toption('footer','social_youtube')))
                                  <a href="{{ toption('footer','social_youtube') }}"><i class="fab fa-youtube"></i></a>
                                @endif
                                @if(!empty(toption('footer','social_linkedin')))
                                   <a href="{{ toption('footer','social_linkedin') }}"><i class="fab fa-linkedin"></i></a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Menu -->
                <div class="row d-flex justify-content-between">

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{ __lang('about-us') }}</h4>
                                 <p>
                                     {{ toption('footer','text') }}
                                 </p>
                            </div>
                        </div>
                    </div>
                    @foreach(footerMenu() as $menu)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{ $menu['label'] }}</h4>
                                <ul>
                                    @foreach($menu['children'] as $childMenu)
                                        <li><a href="{{ $childMenu['url'] }}">{{ $childMenu['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach


                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-12">
                        <div class="footer-copy-right text-center">
                            <p>{!! clean( fullstop(toption('footer','credits')) ) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>
<!-- Scroll Up -->
<div id="back-top" >
    <a title="{{ __t('go-to-top') }}" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<!-- JS here -->

<script src="{{ tasset('assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="{{ tasset('assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ tasset('assets/js/popper.min.js') }}"></script>
<script src="{{ tasset('assets/js/bootstrap.min.js') }}"></script>
<!-- Jquery Mobile Menu -->
<script src="{{ tasset('assets/js/jquery.slicknav.min.js') }}"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="{{ tasset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ tasset('assets/js/slick.min.js') }}"></script>
<!-- One Page, Animated-HeadLin -->
<script src="{{ tasset('assets/js/wow.min.js') }}"></script>
<script src="{{ tasset('assets/js/animated.headline.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.magnific-popup.js') }}"></script>

<!-- Date Picker -->
<script src="{{ tasset('assets/js/gijgo.min.js') }}"></script>
<!-- Nice-select, sticky -->
<script src="{{ tasset('assets/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.sticky.js') }}"></script>

<!-- counter , waypoint -->
<script src="{{ tasset('assets/js/jquery.counterup.min.js') }}"></script>
<script src="{{ tasset('assets/js/waypoints.min.js') }}"></script>

<!-- contact js -->
<script src="{{ tasset('assets/js/contact.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.form.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ tasset('assets/js/mail-script.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.ajaxchimp.min.js') }}"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="{{ tasset('assets/js/plugins.js') }}"></script>
<script src="{{ tasset('assets/js/main.js') }}"></script>

@yield('footer')
{!!  setting('general_foot_scripts')  !!}

</body>


</html>
