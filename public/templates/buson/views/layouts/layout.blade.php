<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('page-title')</title>
    <meta name="description" content="@yield('meta-description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

@if(!empty(setting('image_icon')))
    <!--====== Favicon Icon ======-->
        <link rel="shortcut icon" href="{{ asset(setting('image_icon')) }}" type="image/png">
@endif
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ tasset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ tasset('assets/css/style') }}">
    <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

    @yield('header')
    {!!  setting('general_header_scripts')  !!}

    @if(optionActive('top-bar'))
        <style>
            @if(!empty(toption('top-bar','bg_color')))
                div.top-bg{
                background-color: #{{ toption('top-bar','bg_color') }};
            }
            @endif

                     @if(!empty(toption('top-bar','font_color')))
                .header-area .header-top .header-info-left ul li,.header-area .header-top .header-info-right .header-social li a, .header-area .header-top a, .header-area .header-top button{
                color: #{{ toption('top-bar','font_color') }};
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
                .main-header .main-menu ul li a{
                color: #{{ toption('navigation','font_color') }};
            }
            @endif

        </style>
    @endif
    <style>
        @if(optionActive('footer'))

            @if(!empty(toption('footer','image')))

                    .footer-bg::before {
            background: url({{ toption('footer','image') }});
        }

        @endif
     @if(!empty(toption('footer','image')))

            .footer-area,.footer-bottom-area {
            background-image: url("{{ toption('footer','image') }}");
        }

        @endif
            @if(!empty(toption('footer','bg_color')))

            .footer-area,.footer-bottom-area {
            background-color: #{{ toption('footer','bg_color') }};
            }

        @endif

            @if(!empty(toption('footer','font_color')))
        .footer-area .footer-tittle ul li a,.footer-area .footer-tittle h4,.footer-area .footer-social a i,.footer-bottom-area .footer-copy-right p,.footer-bottom-area .footer-copy-right p a{
            color: #{{ toption('footer','font_color') }};
        }
        @endif

        @endif

            @if(optionActive('contact-form'))
                        @if(!empty(toption('contact-form','bg_color')))

                                                .request-back-area {
                            background-color: #{{ toption('contact-form','bg_color') }};
                        }

                        @endif

                       @if(!empty(toption('contact-form','font_color')))
                          .request-back-area .request-content p, .request-back-area, .request-back-area .request-content h3,.request-back-area,.request-back-area label{
                                            color: #{{ toption('contact-form','font_color') }};
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

<!-- Preloader Start -->
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
            <div class="header-top top-bg d-none_ d-lg-block">
                <div class="container-fluid">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    <li>


                                        @guest

                                            <i class="fas fa-user-circle"></i><a href="{{ route('login') }}">@lang('default.login')</a> |
                                            <a href="{{ route('register') }}">{{ __lang('register') }}</a>

                                        @else

                                            <i class="fa fa-user-circle"></i><a href="{{ route('home') }}">@lang('default.my-account')</a> &nbsp;
                                            &nbsp; &nbsp; <i class="fa fa-sign-out-alt"></i><a  href="{{ route('logout') }}"
                                                                              onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" >@lang('default.logout')</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"  class="int_hide">
                                                @csrf
                                            </form>
                                        @endif
                                    </li>
                                    @if(!empty(toption('top-bar','office_address')))
                                    <li class="hide-mobile"><i class="fas fa-map-marker-alt"></i>{{ toption('top-bar','office_address') }}</li>
                                    @endif

                                    @if(!empty(toption('top-bar','email')))
                                    <li class="hide-mobile"><i class="fas fa-envelope"></i>{{ toption('top-bar','email') }}</li>
                                    @endif

                                </ul>
                            </div>
                            <div class="header-info-right    d-md-none"><a href="{{ route('cart') }}"><i class="fa fa-cart-plus"></i> {{ __lang('cart') }}@if(getCart()->getTotalItems()>0) ({{ getCart()->getTotalItems() }}) @endif</a></div>

                            <div class="header-info-right d-none d-md-block">
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
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="header-bottom  header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-1 col-md-1">
                            <div class="logo">
                                <a href="{{ url('/') }}">
                                    @if(!empty(setting('image_logo')))
                                        <img src="{{ asset(setting('image_logo')) }}" >
                                    @else
                                        {{ setting('general_site_name') }}
                                    @endif

                                </a>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8">
                            <!-- Main-menu -->
                            <div class="main-menu f-right d-none d-lg-block">
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

                                    </ul>
                                </nav>
                            </div>
                        </div>


                        <div class="col-xl-2 col-lg-3 col-md-3">
                            <div class="header-right-btn f-right d-none d-lg-block">
                                <a href="{{ route('cart') }}" style="padding: 18px 22px;    text-align: center;" class="tbtn header-btn"><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}@if(getCart()->getTotalItems()>0) ({{ getCart()->getTotalItems() }}) @endif</a>
                            </div>
                        </div>

                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
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

    <!-- Request Back Start -->
    <div class="request-back-area section-padding30">
        @if(optionActive('contact-form'))
        <div class="container">

            <div class="row d-flex justify-content-between">
                <div class="col-xl-4 col-lg-5 col-md-5">
                    <div class="request-content">
                        <h3>{{ toption('contact-form','heading') }}</h3>
                        <p>{!! clean( toption('contact-form','text') ) !!}</p>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-7">
                    <!-- Contact form Start -->
                    <div class="form-wrapper">
                        <form id="contact-form" action="{{ route('contact.send-mail') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-box  mb-30">
                                        <input required type="text" name="name" placeholder="@lang('default.name')">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-box mb-30">
                                        <input required type="text" name="email" placeholder="@lang('default.email')">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-30">
                                    <div class="form-box">
                                        <textarea rows="5"   name="message" class="form-control int_btxa" required placeholder="@lang('default.message')">{{ old('message') }}</textarea>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-box mb-10">
                                        <label>@lang('default.verification')</label><br/>
                                        <label>
                                            @if(isset($captchaUrl))
                                                <img src="{{ $captchaUrl }}" />
                                            @else
                                                {!! clean(captcha_img()) !!}
                                            @endif
                                        </label>
                                        <input type="text" name="captcha" placeholder="@lang('default.verification-hint')"/>


                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <button type="submit" class="tbtn">{{ __t('send') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>     <!-- Contact form End -->
            </div>

        </div>
        @endif
    </div>
    <!-- Request Back End -->

</main>

<footer>
    <!-- Footer Start-->
    <div class="footer-area footer-padding">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <div class="single-footer-caption mb-50">
                        <div class="single-footer-caption mb-30">
                            <!-- logo -->
                            <div class="footer-logo">
                                <a href="{{ url('/') }}">
                                    @if(!empty(setting('image_logo')))
                                        <img src="{{ asset(setting('image_logo')) }}" >
                                    @else
                                        {{ setting('general_site_name') }}
                                    @endif
                                </a>
                            </div>
                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p>{{ toption('footer','text') }}</p>
                                </div>
                            </div>
                            <!-- social -->
                            <div class="footer-social">

                                @if(!empty(toption('footer','social_facebook')))
                                  <a href="{{ toption('footer','social_facebook') }}"><i class="fab fa-facebook-square"></i></a>
                                @endif
                                @if(!empty(toption('footer','social_twitter')))
                                    <a href="{{ toption('footer','social_twitter') }}"><i class="fab fa-twitter-square"></i></a>
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
                            {!!  toption('footer','newsletter_code')  !!}
                        </div>
                    </div>
                </div>
                @foreach(footerMenu() as $menu)
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>{{ $menu['label'] }}</h4>
                            @if($menu['children'])
                            <ul>
                                @foreach($menu['children'] as $childMenu)
                                    <li><a href="{{ $childMenu['url'] }}">{{ $childMenu['label'] }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>@lang('default.contact-us')</h4>
                            <ul>
                                @if(!empty(setting('general_tel')))
                                <li><a href="#">{{ setting('general_tel') }}</a></li>
                                @endif
                                    @if(!empty(setting('general_contact_email')))

                                <li><a href="mailto:{!! clean( setting('general_contact_email') ) !!}">{!! clean( setting('general_contact_email') ) !!}</a></li>
                                    @endif

                                @if(!empty(setting('general_address')))
                                <li><a href="#">{!! clean(setting('general_address')) !!} </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer-bottom aera -->
    <div class="footer-bottom-area footer-bg">
        <div class="container">
            <div class="footer-border">
                <div class="row d-flex align-items-center">
                    <div class="col-xl-12 ">
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

<!-- JS here -->

<!-- All JS Custom Plugins Link Here here -->
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
<!-- Date Picker -->
<script src="{{ tasset('assets/js/gijgo.min.js') }}"></script>
<!-- One Page, Animated-HeadLin -->
<script src="{{ tasset('assets/js/wow.min.js') }}"></script>
<script src="{{ tasset('assets/js/animated.headline.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.magnific-popup.js') }}"></script>

<!-- Scrollup, nice-select, sticky -->
<script src="{{ tasset('assets/js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ tasset('assets/js/jquery.sticky.js') }}"></script>

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
