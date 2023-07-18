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
    {{--<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">--}}

    <link rel="preload"
          href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;display=swap"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'"
    />
    <noscript>
        <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;display=swap"
              rel="stylesheet"
              type="text/css"
        />
    </noscript>

    <link rel="preload"
          href="{{ tasset('fonts/fa-solid-900.woff2') }}"
          as="font"
          crossorigin>
    <link rel="preload"
          href="{{ tasset('fonts/ionicons/fonts/ionicons580c.woff2?v=4.0.0-19') }}"
          as="font"
          crossorigin>
    <link rel="preload"
          href="{{ tasset('https://worldacademy.uk/templates/fox/assets/fonts/icomoon/icomoonccfb.ttf?6tt51o') }}"
          as="font"
          crossorigin>

    <link rel="stylesheet" href="{{ tasset('css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/animate.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/bonwhatsapp.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/aos.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ tasset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ tasset('css/style') }}">
    <link rel="stylesheet" href="{{ tasset('css/fontawesome-all.min.css') }}">
    <script src="https://asset-tidycal.b-cdn.net//js/embed.js"></script>
    <style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Roboto');

        @keyframes pulse {
        	 0% {
        		 transform: scale(1, 1);
        	}
        	 50% {
        		 opacity: 0.3;
        	}
        	 100% {
        		 transform: scale(1.45);
        		 opacity: 0;
        	}
        }
         .pulse {
        	 -webkit-animation-name: pulse;
        	 animation-name: pulse;
        }
         .nav-bottom {
        	 display: flex;
        	 flex-direction: row;
        	 justify-content: flex-end;
        	 align-content: flex-end;
        	 width: auto;
        	 height: auto;
        	 position: fixed;
        	 z-index: 8;
        	 bottom: 0px;
        	 right: 0px;
        	 padding: 5px;
        	 margin: 0px;
        }
         @media (max-width: 360px) {
        	 .nav-bottom {
        		 width: 320px;
        	}
        }
         .whatsapp-button {
        	 display: flex;
        	 justify-content: center;
        	 align-content: center;
        	 width: 60px;
        	 height: 60px;
        	 z-index: 8;
        	 transition: 0.3s;
        	 margin: 10px;
        	 padding: 7px;
        	 border: none;
        	 outline: none;
        	 cursor: pointer;
        	 border-radius: 50%;
        	 background-color: #fff;
        	/* offset-x > | offset-y ^| blur-radius | spread-radius | color */
        	 -webkit-box-shadow: 1px 1px 6px 0px rgba(68, 68, 68, 0.705);
        	 -moz-box-shadow: 1px 1px 6px 0px rgba(68, 68, 68, 0.705);
        	 box-shadow: 1px 1px 6px 0px rgba(68, 68, 68, 0.705);
        }
         .circle-anime {
        	 display: flex;
        	 position: absolute;
        	 justify-content: center;
        	 align-content: center;
        	 width: 60px;
        	 height: 60px;
        	 top: 15px;
        	 right: 15px;
        	 border-radius: 50%;
        	 transition: 0.3s;
        	 background-color: #77bb4a;
        	 animation: pulse 1.2s 4s ease 4;
        }
         .popup-whatsapp {
        	 display: none;
        	 position: absolute;
        	 flex-direction: column;
        	 justify-content: flex-start;
        	 align-items: flex-start;
        	 width: auto;
        	 height: auto;
        	 padding: 10px;
        	 bottom: 85px;
        	 right: 6px;
        	 transition: 0.5s;
        	 border-radius: 10px;
        	 background-color: #fff;
        	/* offset-x > | offset-y ^| blur-radius | spread-radius | color */
        	 -webkit-box-shadow: 2px 1px 6px 0px rgba(68, 68, 68, 0.705);
        	 -moz-box-shadow: 2px 1px 6px 0px rgba(68, 68, 68, 0.705);
        	 box-shadow: 2px 1px 6px 0px rgba(68, 68, 68, 0.705);
        	 animation: slideInRight 0.6s 0s both;
        }
         .popup-whatsapp > div {
        	 margin: 5px;
        }
         @media (max-width: 680px) {
        	 .popup-whatsapp p {
        		 font-size: 0.9em;
        	}
        }
         .popup-whatsapp > .content-whatsapp.-top {
        	 display: flex;
        	 flex-direction: column;
        }
         .popup-whatsapp > .content-whatsapp.-top p {
        	 color: #585858;
        	 font-family: 'Roboto';
        	 font-weight: 400;
        	 font-size: 1em;
        }
         .popup-whatsapp > .content-whatsapp.-bottom {
        	 display: flex;
        	 flex-direction: row;
        }
         .closePopup {
        	 display: flex;
        	 justify-content: center;
        	 align-items: center;
        	 width: 28px;
        	 height: 28px;
        	 margin: 0px 0px 15px 0px;
        	 border-radius: 50%;
        	 border: none;
        	 outline: none;
        	 cursor: pointer;
        	 background-color: #f76060;
        	 -webkit-box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        	 -moz-box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        	 box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        }
         .closePopup:hover {
        	 background-color: #f71d1d;
        	 transition: 0.3s;
        }
         .send-msPopup {
        	 display: flex;
        	 justify-content: center;
        	 align-items: center;
        	 width: 40px;
        	 height: 40px;
        	 border-radius: 50%;
        	 background-color: #fff;
        	 margin: 0px 0px 0px 5px;
        	 border: none;
        	 outline: none;
        	 cursor: pointer;
        	 -webkit-box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        	 -moz-box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        	 box-shadow: 1px 1px 2px 0px rgba(68, 68, 68, 0.705);
        }
         .send-msPopup:hover {
        	 background-color: #f8f8f8;
        	 transition: 0.3s;
        }
         .is-active-whatsapp-popup {
        	 display: flex;
        	 animation: slideInRight 0.6s 0s both;
        }
         input.whats-input[type=text] {
        	 width: 250px;
        	 height: 40px;
        	 box-sizing: border-box;
        	 border: 0px solid #fff;
        	 border-radius: 20px;
        	 font-size: 1em;
        	 background-color: #fff;
        	 padding: 0px 0px 0px 10px;
        	 -webkit-transition: width 0.3s ease-in-out;
        	 transition: width 0.3s ease-in-out;
        	 outline: none;
        	 transition: 0.3s;
        }
         @media (max-width: 420px) {
        	 input.whats-input[type=text] {
        		 width: 225px;
        	}
        }
         input.whats-input {
        	/* Most modern browsers support this now. */
        }
         input.whats-input::placeholder {
        	 color: rgba(68, 68, 68, 0.705);
        	 opacity: 1;
        }
         input.whats-input[type=text]:focus {
        	 background-color: #f8f8f8;
        	 -webkit-transition: width 0.3s ease-in-out;
        	 transition: width 0.3s ease-in-out;
        	 transition: 0.3s;
        }
         .icon-whatsapp-small {
        	 width: 24px;
        	 height: 24px;
        }
         .icon-whatsapp {
        	 width: 45px;
        	 height: 45px;
        }
         .icon-font-color {
        	 color: #fff;
        }
         .icon-font-color--black {
        	 color: #333;
        }

         /*Countdown*/
        .csi-countdowns .csi-inner {
            padding: 0;
            height: 85px;
        }
        .csi-countdown-area {
            width: 100%;
            right: 0px;
            bottom: 0px;
            background-color: rgba(0,0,0,0.5);
            z-index: 2;
            cursor: pointer;
            height: 85px;
        }
        .csi-countdown-area .csi-countdown-area-inner {
            width: 83%;
            margin: 0 auto;
            display: block;
            text-align: right;
            height: 85px;
        }
        .csi-countdown-area .csi-countdown span {
            text-align: center;
            font-family: Oswald, sans-serif;
            font-size: 3.8rem;
            line-height: 3.2rem;
            font-weight: 900;
            display: inline-block;
            margin-bottom: 0;
            margin-right: 3.8rem;
            letter-spacing: .16rem;
            position: relative;
            bottom: -11px;
        }
        .csi-countdown-area .csi-countdown span:last-child {
            margin-right: 0
        }
        .csi-countdown-area .csi-countdown i {
            font-family: Lato, sans-serif;
            font-size: 1.8rem;
            line-height: 1.8rem;
            color: #fff;
            text-transform: uppercase;
            font-weight: 300;
            letter-spacing: normal;
            -ms-transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg);
            transform: rotate(-90deg);
            font-style: normal;
            display: inline-block;
            position: absolute;
            bottom: 5px;
            right: -55px
        }
        .csi-countdown-area .csi-countdown .csi-days {
            color: #fff200
        }
        .csi-countdown-area .csi-countdown .csi-hr {
            color: #ff8a00
        }
        .csi-countdown-area .csi-countdown .csi-min {
            color: #00b9ff
        }
        .csi-countdown-area .csi-countdown .csi-sec {
            color: #8dc63f
        }
        .csi-countdown-area-left .csi-countdown-area-inner {
            text-align: left
        }
        .csi-countdown-area-left .csi-countdown-area-inner .csi-countdown span {
            background: -moz-linear-gradient(top, #1a1f3f 0, #b21e8e 100%);
            background: -webkit-gradient(linear, bottom top, bottom top, color-stop(0, #1a1f3f), color-stop(100%, #b21e8e));
            background: -webkit-linear-gradient(top, #1a1f3f 0, #b21e8e 100%);
            background: -o-linear-gradient(top, #1a1f3f 0, #b21e8e 100%);
            background: -ms-linear-gradient(top, #1a1f3f 0, #b21e8e 100%);
            background: linear-gradient(to top, #1a1f3f 0, #b21e8e 100%);
            padding: 1rem 2rem;
            border-radius: 6px
        }
        .mba{
            font-size: 15px;
        }
        

        .single-course-category_index {
            text-align: center;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            transition: all .4s ease 0s;
            position: relative;
            z-index: 1;
            background-color: #f80101;
        }
        .online_class{
             margin-bottom: 5px;
            padding: 11px 10px;
            border: 2px solid #576299;
            border-radius: 10px;
            height: 76px;
            text-align: center;
            background: #fafd54;
            box-shadow: 20px 20px 50px 10px pink inset;
        }
        
        .course-block .course-content h3 a {
            color: blue !important;
            font-size: 14px !important;
            font-weight: 700;
        }
        .main_trainer .trainer_a{
            margin: 10px 31px;background:#e22a3e !important;position: relative;right: 24px;
        }
        .main_trainer .trainer_a:hover{
            color:black;
        }
        
        
         .main_partner .partner_a{
            margin: 10px 31px;
            background:#0911c4 !important;
            position: relative;
            right: 14px; 
            border:1px solid #0911c4 !important;
        }
        .main_partner .partner_a:hover{
            color:red;
        }
        
        .mba_all{
            position: relative;
            left: -25px;
        }
        
        .rs-partner .partner-item img{
            width:100%;
        }
        #tidycal-embed .tc-logo-icon{
            
            display:none !important;
        }
	</style>

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


    <script type="text/javascript"
            src="https://platform-api.sharethis.com/js/sharethis.js#property=60f3cce5fba7b0001906eed4&product=sticky-share-buttons"
            async="async"></script>

    @if ((Request::ip() != '::1') OR (Request::ip() != '127.0.0.1'))
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '306864873594146');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1"
                 width="1"
                 style="display:none"
                 src="https://www.facebook.com/tr?id=306864873594146&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->

        <script charset="UTF-8" src="//web.webpushs.com/js/push/650f95930efb8c1f94934715b2a43502_1.js" async></script>
    @endif

<meta name="facebook-domain-verification" content="81clp03t9hdkdf6lra28lnptu3ureo" />
</head>
<body>
    

    
</div>
<div class="bg-top navbar-light">
    <div class="container">
        <div class="row no-gutters d-flex align-items-center align-items-stretch">
            <div class="col-md-4 col-sm-4 d-flex align-items-center"><!-- py-4 -->
                <a class="navbar-brand logo-box" href="{{ url('/') }}">
                    @if(!empty(setting('image_logo')))
                        <img src="{{ asset(setting('image_logo')) }}">
                    @else
                        {{ setting('general_site_name') }}
                    @endif
                    
                    
                    <img src="{{ url('public/usermedia/Logo/new_logo.png') }}" width="100" style="width:186px">
                    

                </a>
            </div>
            <div class="col-lg-8 col-sm-8 d-block hide-mobile"  >
                <div class="row d-flex">

                    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4 pt-2 mt-3">{{--offset-2 --}}
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
                            <a href="{{ route('cart') }}" class="btn rounded  py-2 px-3 btn-primary d-flex align-items-center justify-content-center" style="margin-left: 63px;position: relative;left: -72px;">
                                <span><i class="fa fa-cart-plus"></i> {{ __lang('your-cart') }}</span>
                            </a>
                        </p>
                        
                        <p class="mt-4 pt-2 main_trainer">
                            <a target="_blank" href="https://worldacademy.org.uk/form/forms/opportunity-for-trainers-XM.qQg" class="btn rounded  py-2 px-3 btn-primary d-flex align-items-center justify-content-center trainer_a">
                                <span style="font-size:12px"><i class="fa fa-user"></i> {{ __lang('trainer') }}</span>
                            </a>
                        </p>
                        
                        <!--{{url('/verify-partner')}} -->
                        
                          <p class="mt-4 pt-2 main_partner">
                            <a target="_blank" href="https://worldacademy.org.uk/form/forms/become-partner-XM.qQg" class="btn rounded  py-2 px-3 btn-primary d-flex align-items-center justify-content-center partner_a">
                                <span style="font-size:12px"><i class="fa fa-user"></i>Become Partner</span>
                            </a>
                        </p>
                    </div>
                    @endif

                    <div class="col-md topper d-flex align-items-center justify-content-end mba_all">
                       <a target="_blank" href="https://mba-edu.uk/"><img src="{{ url('public/usermedia/Logo/mba-final.png') }}" width="100" style="width:71px;padding:5px 0">
                       <h5 class="mba">MBA/Masters</h5>
                       </a>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST"  class="int_hide">
    @csrf
</form>
<nav class="navbar navbar-expand-lg navbar-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container-fluid d-flex align-items-center px-4">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> {{ __lang('menu') }}
        </button>

        <form action="{{ route('courses') }}" class="searchform order-lg-last hide-mobile">
            <div class="form-group d-flex">
                <!--kabir works serach-->
                <input type="text" class="form-control pl-3" id="searchmenu" onfocus="showSearchResult()" onblur="hideSearchResult()" type="text" name="searchmenu" placeholder="{{ __lang('search-courses') }}" >
               
            </div>
        </form>
        <div id="suggestProduct"></div>
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
@include('pages.bonwhatsappchat')
<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">{{ __lang('contact-us') }}</h2>
                    <div class="block-23 mb-3">
                        <ul> 
                        <!--@if(!empty(toption('footer','address')))-->
                            <!--<li><span class="icon icon-map-marker"></span><span class="text">{{ toption('footer','address') }}</span></li>-->
                            <!--@endif--> 
                            @if(!empty(toption('footer','address')))
                            <li><span class="icon icon-map-marker"></span><span class="text"> <strong style="color:orange">Office 2677A, 182-184 High Street North, East Ham, London E6 2JA. UK
</strong></li>

<li><a href="https://wa.me/+02034324136"><span class="icon icon-phone"></span><span class="text">+02034324136</span></a></li>
                            @endif
                          

                            
                            
                             <li><span class="icon icon-map-marker"></span><span class="text"> <strong>South Asia Office: 5/12, Lalmatia, Dhaka (Office Time: Every Day 10am-6pm BD time )
</strong></li>
                           
                            @if(!empty(toption('footer','telephone')))
                            <li><a href="https://wa.me/{{ toption('footer','telephone') }}"><span class="icon icon-phone"></span><span class="text">{{ toption('footer','telephone') }}</span></a></li>
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
                    <h2 class="ftco-heading-2">{{ __t('recent-posts') }} sdd</h2>
                    @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(2)->get() as $post)

                    <div class="block-21 mb-4 d-flex">
                        @php
                            $image = (new \App\Helper\AppHelper)->imageExits($post->cover_photo);
                        @endphp
                        <a class="blog-img mr-4"
                           style="background-image: url({{ $image }});"></a>
                        <div class="text">
                            <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id]) }}">{{ $post->title }}</a></h3>
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
                {{--<p>{!! clean( fullstop(toption('footer','credits')) ) !!}</p>--}}
                <img src="{{ asset('img/SSLCommerz-Pay.webp') }}"
                     alt="Accepted Card" width="100%">
            </div>
        </div>
    </div>
</footer>



<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

<div class="nav-bottom" style="display: none !important;">
<div class="popup-whatsapp fadeIn">
    <div class="content-whatsapp -top"><button type="button" class="closePopup">
          <i class="fa fa-times"></i>
        </button>
        <p>Hello, need help?</p>
    </div>
    <div class="content-whatsapp -bottom">
      <input class="whats-input" id="whats-in" type="text" Placeholder="Send message..." />
        <button class="send-msPopup" id="send-btn" type="button">
            <i class="fa fa-arrow-alt-circle-right"></i>
        </button>

    </div>
</div>
<button type="button" id="whats-openPopup" class="whatsapp-button">
    <img class="icon-whatsapp" src="https://image.flaticon.com/icons/svg/134/134937.svg">
</button>
<div class="circle-anime"></div>
</div>

    <!-- Modal -->
<div class="modal fade" id="eligibleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="modal-eligible">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Important Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="eligibleView-modal-body">
        
      </div>
    </div>
  </div>
</div>  

<script type="text/javascript">
        function eligibleView(id){
            if(!$('#modal-eligible').hasClass('modal-dialog')){
                $('#modal-eligible').addClass('modal-dialog');
            }
            $('#eligibleView-modal-body').html(null);
            $('#eligibleModal').modal();
            $.get('{{url('/single-important-information/') }}/'+id, function(data){
                $('#eligibleView-modal-body').html(data);
            });
        }
    </script>
        
<script src="{{ tasset('js/jquery.min.js') }}"></script>
<script src="{{ tasset('js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ tasset('js/popper.min.js') }}"></script>
<script src="{{ tasset('js/bootstrap.min.js') }}"></script>
<script src="{{ tasset('js/jquery.easing.1.3.js') }}"></script>
<script src="{{ tasset('js/jquery.waypoints.min.js') }}"></script>
<script src="{{ tasset('js/jquery.stellar.min.js') }}"></script>
<script src="{{ tasset('js/owl.carousel.min.js') }}"></script>
<script src="{{ tasset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.1.0/moment.min.js"></script>
<script src="{{ tasset('js/aos.js') }}"></script>
<script src="{{ tasset('js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ tasset('js/scrollax.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&amp;sensor=false"></script>
<script src="{{ tasset('js/google-map.js') }}"></script>
<script src="{{ tasset('js/main.js') }}"></script>
<script src="{{ tasset('js/bonwhatsapp.js') }}"></script>
<script src="{{ tasset('js/lazysizes.min.js') }}" async></script>
<!-- Counterup -->
<script src="{{ tasset('js/waypoint.js') }}"></script>
<script src="{{ tasset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ tasset('js/countdown.js') }}"></script>

{!!  setting('general_foot_scripts')  !!}

@yield('footer')

  
  <script>
            $("body").on("keyup","#searchmenu",function(){
                var searchData=$("#searchmenu").val();
                //alert(searchData.length);
                        $.ajax({
                        type:'POST',
                        url:"{{url('/find-courses-for-mainsite')}}",
                        data:{"_token": "{{ csrf_token() }}","searchmenu":searchData,},
                        success:function(result){
                             $("#suggestProduct").html(result);
                        }
                         

                    });
                    
                    if(searchData.length > 0){
                        $("#suggestProduct").html(result);
                    }else{
                    $("#suggestProduct").hide(result);
                    window.location.reload(1000);
                }
                
                   

            })
        </script>

<script type="text/javascript">
    function update() {
        $('#clock').html(moment.utc().format('DD-MMM hh:mm:ss A')+' GMT');
    }

    setInterval(update, 1000);



    popupWhatsApp = () => {
      let btnClosePopup = document.querySelector('.closePopup');
      let btnOpenPopup = document.querySelector('.whatsapp-button');
      let popup = document.querySelector('.popup-whatsapp');
      let sendBtn = document.getElementById('send-btn');
    
      btnClosePopup.addEventListener("click",  () => {
        popup.classList.toggle('is-active-whatsapp-popup')
      })
      
      btnOpenPopup.addEventListener("click",  () => {
        popup.classList.toggle('is-active-whatsapp-popup')
         popup.style.animation = "fadeIn .6s 0.0s both";
      })
      
      sendBtn.addEventListener("click", () => {
      let msg = document.getElementById('whats-in').value;
      let relmsg = msg.replace(/ /g,"%20");
        //just change the numbers "1515551234567" for your number. Don't use +001-(555)1234567     
       window.open('https://wa.me/8801792380380?text='+relmsg, '_blank'); 
      
      });
    
      setTimeout(() => {
        popup.classList.toggle('is-active-whatsapp-popup');
      }, 3000);
    }
    
    popupWhatsApp();

    function search_all_courses(){
        var txt = $('#search-criteria').val();
        $('.search-results').hide();
        $('.search-results:contains("'+txt+'")').show();
    }

    function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that runs

            search_all_courses();
        }
    }
    $('.search_all').click(function(){
        search_all_courses();
    });

    var showChar = 100;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";

    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });

    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });
    </script>
</body>

</html>
