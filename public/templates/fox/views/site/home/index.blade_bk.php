@extends(TLAYOUT)
@section('page-title',setting('general_homepage_title'))
@section('meta-description',setting('general_homepage_meta_desc'))

@section('content')


    @if(optionActive('slideshow'))
    <section class="home-slider owl-carousel">

        @for($i=1;$i<=10;$i++)
            @if(!empty(toption('slideshow','file'.$i)))
            @section('header')
                @parent

                <style>

                    @if(!empty(toption('slideshow','heading_font_color'.$i)))

                                            .slhc{{ $i }}{
                        color: #{{ toption('slideshow','heading_font_color'.$i) }} !important;
                    }

                    @endif

                                        @if(!empty(toption('slideshow','text_font_color'.$i)))
                                        .sltx{{ $i }}{
                        color: #{{ toption('slideshow','text_font_color'.$i) }} !important;
                    }
                    @endif

                </style>



            @endsection
        <div class="slider-item"  @if(!empty(toption('slideshow','file'.$i)))  style="background-image:url({{ asset(toption('slideshow','file'.$i)) }});" @endif >
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-start" data-scrollax-parent="true">
                    <div class="col-md-6 ftco-animate">
                        <h1 class="mb-4 @if(!empty(toption('slideshow','heading_font_color'.$i)))  slhc{{ $i }} @endif"    >{{ toption('slideshow','slide_heading'.$i) }}</h1>
                        <p   @if(!empty(toption('slideshow','text_font_color'.$i))) class="sltx{{ $i }}" @endif   >{{ toption('slideshow','slide_text'.$i) }}</p>
                        <p><a href="{{ toption('slideshow','url'.$i) }}" class="btn btn-primary px-4 py-3 mt-3">{{ toption('slideshow','button_text'.$i) }}</a></p>
                    </div>
                </div>
            </div>
        </div>
            @endif
        @endfor

    </section>
    @endif

    @if(optionActive('homepage-services'))
        @php
            $count=0;
        @endphp


    <section class="ftco-services ftco-no-pb">
        <div class="container-wrap">
            <div class="row no-gutters">
                @for($i=1;$i<=4;$i++)
                    @if(!empty(toption('homepage-services','heading'.$i)))
                <div class="col-md-3 d-flex services align-self-stretch py-5 px-4 ftco-animate bg-primary">
                    <div class="media block-6 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="{{ toption('homepage-services','icon'.$i) }}"></span>
                        </div>
                        <div class="media-body p-2 mt-3">
                            <h3 class="heading">{{ toption('homepage-services','heading'.$i) }}</h3>
                            <p>{!! clean(toption('homepage-services','text'.$i)) !!}</p>
                        </div>
                    </div>
                </div>
                    @endif
                @endfor
            </div>
        </div>
    </section>


    @endif

    @if(optionActive('homepage-about'))
    <section class="ftco-section ftco-no-pt ftc-no-pb">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-5 order-md-last wrap-about wrap-about d-flex align-items-stretch">
                    @if(!empty(toption('homepage-about','image')))
                    <div class="img" style="background-image: url({{ asset(toption('homepage-about','image')) }}); border"></div>
                    @endif
                </div>
                <div class="col-md-7 wrap-about py-5 pr-md-4 ftco-animate">
                    <h2 class="mb-4">{{ toption('homepage-about','heading') }}</h2>
                    <p>{!! clean( toption('homepage-about','text') ) !!}</p>
                    <div class="row mt-5">
                        @for($i=1;$i<=6;$i++)
                            @if(!empty(toption('homepage-about','heading'.$i)))
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span class="{{ toption('homepage-about','icon'.$i) }}"></span></div>
                                <div class="text pl-3">
                                    <h3>{{ toption('homepage-about','heading'.$i) }}</h3>
                                    <p>{!! clean(toption('homepage-about','text'.$i)) !!} </p>
                                </div>
                            </div>
                        </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


    @if(optionActive('featured-courses'))
    <section class="ftco-section">
        <div class="container-fluid px-4">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">{{ toption('featured-courses','heading') }}</h2>
                    <p>{{ toption('featured-courses','description') }}</p>
                </div>
            </div>
            <div class="row">
                @php
                    $courses = toption('featured-courses','courses');
                @endphp
                @if(is_array($courses))
                @foreach(toption('featured-courses','courses') as $course)
                    @if(!empty($course) && \App\Course::find($course))
                        @php
                            $course = \App\Course::find($course);
                        @endphp
                <div class="col-md-3 course ftco-animate">
                    @if(!empty($course->picture))
                    <div class="img" style="background-image: url({{ asset($course->picture) }});"></div>
                    @endif
                    <div class="text pt-4">
                        <p class="meta d-flex">
                            <span><i class="fa fa-money-bill"></i>{{ sitePrice($course->fee) }}</span>
                            <span><i class="icon-table mr-2"></i>{{ $course->lessons()->count() }} {{ __lang('classes') }}</span>
                        </p>
                        <h3><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h3>
                        <p>{{ limitLength(strip_tags($course->short_description),100) }}</p>
                        <p><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}" class="btn btn-primary">{{ __lang('details') }}</a></p>
                    </div>
                </div>
                    @endif
                @endforeach
                @endif
            </div>
        </div>
    </section>
    @endif

    @if(optionActive('instructors'))
    <section class="ftco-section bg-light">
        <div class="container-fluid px-4">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">{{ toption('instructors','heading') }}</h2>
                    <p>{{ toption('instructors','description') }}</p>
                </div>
            </div>
            <div class="row">

                @foreach(toption('instructors','instructors') as $admin)
                    @php
                        $admin = \App\Admin::find($admin);
                    @endphp
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="staff">
                        <div class="img-wrap d-flex align-items-stretch">
                            @if(empty($admin->user->picture))
                                <div class="img align-self-stretch" style="background-image: url({{ asset('img/user.png') }});"></div>

                            @else
                                <div class="img align-self-stretch" style="background-image: url({{ asset($admin->user->picture) }});"></div>

                            @endif

                        </div>
                        <div class="text pt-3 text-center">
                            <h3><a  href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h3>

                            <div class="faded">
                                <p>{{ limitLength($admin->about,100) }}</p>
                                <ul class="ftco-social text-center">
                                    @if(!empty($admin->social_facebook))
                                        <li class="ftco-animate"><a href="{{  $admin->social_facebook}}"><span class="icon-facebook"></span></a></li>
                                    @endif

                                    @if(!empty($admin->social_twitter))
                                            <li class="ftco-animate"><a href="{{  $admin->social_twitter }}"><span class="icon-twitter"></span></a></li>
                                    @endif

                                    @if(!empty($admin->social_linkedin))
                                            <li class="ftco-animate"><a href="{{  $admin->social_linkedin }}"><span class="icon-linkedin"></span></a></li>
                                    @endif

                                    @if(!empty($admin->social_instagram))
                                            <li class="ftco-animate"><a href="{{  $admin->social_instagram }}"><span class="icon-instagram"></span></a></li>
                                    @endif

                                    @if(!empty($admin->social_website))
                                            <li class="ftco-animate"><a href="{{  $admin->social_website }}"><span class="icon-globe"></span></a></li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    @if(optionActive('blog'))
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">{{ toption('blog','heading') }}</h2>
                    <p>{{ toption('blog','description') }}</p>
                </div>
            </div>
            <div class="row">

                @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(intval(toption('blog','limit')))->get() as $post)

                <div class="col-md-6 col-lg-4 ftco-animate">
                    <div class="blog-entry">
                        <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}" class="block-20 d-flex align-items-end" @if(!empty($post->cover_photo)) style="background-image: url('{{ asset($post->cover_photo) }}');" @endif >
                            <div class="meta-date text-center p-2">
                                <span class="day">{{  \Carbon\Carbon::parse($post->publish_date)->format('D') }}</span>
                                <span class="mos">{{  \Carbon\Carbon::parse($post->publish_date)->format('M') }}</span>
                                <span class="yr">{{  \Carbon\Carbon::parse($post->publish_date)->format('Y') }}</span>
                            </div>
                        </a>
                        <div class="text bg-white p-4">
                            <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                            <p>{{ limitLength(strip_tags($post->content),100) }}</p>
                            <div class="d-flex align-items-center mt-4">
                                <p class="mb-0"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}" class="btn btn-primary">{{ __lang('read-more') }} <span class="ion-ios-arrow-round-forward"></span></a></p>
                                <p class="ml-auto mb-0">
                                    @if($post->admin)
                                    <a @if($post->admin->public == 1)  href="{{ route('instructor',['admin'=>$post->admin_id]) }}" @endif class="mr-2">{{ $post->admin->user->name.' '.$post->admin->user->last_name }}</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    @if(optionActive('testimonials'))
    <section class="ftco-section testimony-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">{{ toption('testimonials','heading') }}</h2>
                    <p>{{ toption('testimonials','description') }}</p>
                </div>
            </div>
            <div class="row ftco-animate justify-content-center">
                <div class="col-md-12">
                    <div class="carousel-testimony owl-carousel">
                        @for($i=1;$i <= 6; $i++)
                            @if(!empty(toption('testimonials','name'.$i)))

                            <div class="item">
                            <div class="testimony-wrap d-flex">
                                @if(!empty(toption('testimonials','image'.$i)))
                                    <div class="user-img mr-4" style="background-image: url({{ asset(toption('testimonials','image'.$i)) }})"></div>
                                @else
                                    <div class="user-img mr-4" style="background-image: url({{ asset('img/man.jpg') }})"></div>
                                @endif


                                <div class="text ml-2">
                  	<span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                                    <p>{{ toption('testimonials','text'.$i) }}</p>
                                    <p class="name">{{ toption('testimonials','name'.$i) }}</p>
                                    <span class="position">{{ toption('testimonials','role'.$i) }}</span>
                                </div>
                            </div>
                        </div>

                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(optionActive('footer-gallery'))
    <section class="ftco-gallery">
        <div class="container-wrap">
            <div class="row no-gutters">
                @for($i=1;$i <= 4; $i++)
                    @if(!empty(toption('footer-gallery','image'.$i)))
                    <div class="col-md-3 ftco-animate">
                    <a href="{{ toption('footer-gallery','image'.$i) }}" class="gallery image-popup img d-flex align-items-center" style="background-image: url({{ resizeImage(toption('footer-gallery','image'.$i),338,350,url('/')) }});">
                        <div class="icon mb-4 d-flex align-items-center justify-content-center">
                            <span class="icon-image"></span>
                        </div>
                    </a>
                </div>
                    @endif

                @endfor

            </div>
        </div>
    </section>
    @endif




@endsection


