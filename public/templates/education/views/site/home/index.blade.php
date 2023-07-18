@extends(TLAYOUT)
@section('page-title',setting('general_homepage_title'))
@section('meta-description',setting('general_homepage_meta_desc'))

@section('content')
    @if(optionActive('slideshow'))
        @if(!empty(toption('slideshow','slider_background')))
            @section('header')
                @parent
                <style>
                    .slider-height{
                        background-color: #{{ toption('slideshow','slider_background') }};
                    }
                </style>
            @endsection
        @endif

        <?php
        $count=0;
        ?>
    <!-- slider Area Start-->
    <div class="slider-area ">
        <!-- Mobile Menu -->
        <div class="slider-active">
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

                <div class="single-slider slider-height d-flex align-items-center">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-xl-6 col-lg-7 col-md-8">
                                    <div class="hero__caption">
                                        @if(!empty(toption('slideshow','slide_heading'.$i)))
                                        <span data-animation="fadeInLeft"  @if(!empty(toption('slideshow','heading_font_color'.$i))) class="slhc{{ $i }}" @endif  data-delay=".2s">{{ toption('slideshow','slide_heading'.$i) }}</span>
                                        @endif

                                        @if(!empty(toption('slideshow','slide_text'.$i)))
                                        <h1  @if(!empty(toption('slideshow','text_font_color'.$i))) class="sltx{{ $i }}" @endif   data-animation="fadeInLeft" data-delay=".4s">{{ toption('slideshow','slide_text'.$i) }}</h1>
                                        @endif
                                        @if(!empty(toption('slideshow','button_text'.$i)))
                                        <!-- Hero-btn -->
                                        <div class="hero__btn">
                                            <a href="{{ toption('slideshow','url'.$i) }}" class="btn hero-btn"  data-animation="fadeInLeft" data-delay=".8s">{{ toption('slideshow','button_text'.$i) }}</a>
                                        </div>
                                            @endif

                                    </div>
                                </div>
                                @if(!empty(toption('slideshow','file'.$i)))
                                <div class="col-xl-6 col-lg-5">
                                    <div class="hero-man d-none d-lg-block f-right" data-animation="jello" data-delay=".4s">
                                        <img src="{{ asset(toption('slideshow','file'.$i)) }}" alt="">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                    $count++;
                    @endphp
                @endif

            @endfor


        </div>
    </div>
    <!-- slider Area End-->
    @endif


    @if(optionActive('homepage-services'))
        @php
            $count=0;
        @endphp
    <!--? Categories Area Start -->
    <div class="categories-area section-padding30_ pt-5" >
        <div class="container">
            <div class="row justify-content-sm-center">
                <div class="cl-xl-7 col-lg-8 col-md-10">
                    <!-- Section Tittle -->
                    <div class="section-tittle text-center mb-70">
                        <span>{{ toption('homepage-services','sub_heading') }}</span>
                        <h2>{{ toption('homepage-services','main_header') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">


                @for($i=1;$i<=6;$i++)
                    @if(!empty(toption('homepage-services','heading'.$i)))
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-cat mb-50">
                        <div class="cat-icon">
                            <span class="{{ toption('homepage-services','icon'.$i) }}"></span>
                        </div>
                        <div class="cat-cap">
                            <h5><a  href="#">{{ toption('homepage-services','heading'.$i) }}</a></h5>
                            <p>{{ toption('homepage-services','text'.$i) }}</p>
                            <a href="{{ toption('homepage-services','url'.$i) }}" class="read-more1">{{ toption('homepage-services','button_text'.$i) }} ></a>
                        </div>
                    </div>
                </div>
                    @endif
                @endfor


            </div>
        </div>
    </div>
    <!-- Categories Area End -->
    @endif

    @if(optionActive('featured-courses'))
    <!--? Popular Course Start -->
    <div class="popular-course pt-5 ">
        <div class="container">
            <div class="row justify-content-sm-center">
                <div class="cl-xl-7 col-lg-8 col-md-10">
                    <!-- Section Tittle -->
                    <div class="section-tittle text-center mb-70">
                        <span>{{ toption('featured-courses','sub_heading') }}</span>
                        <h2>{{ toption('featured-courses','heading') }}</h2>
                    </div>
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
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <!-- Single course -->
                    <div class="single-course mb-40">
                        @if(!empty($course->picture))
                        <div class="course-img">
                            <img src="{{ asset($course->picture) }}" alt="">
                        </div>
                        @endif

                        <div class="course-caption">
                            <div class="course-cap-top">
                                <h4><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h4>
                            </div>
                            <div class="course-cap-mid d-flex justify-content-between">
                                <p>{{ limitLength(strip_tags($course->short_description),100) }}</p>
                            </div>
                            <div class="course-cap-bottom d-flex justify-content-between">
                                <ul>
                                    <li><a class="link"  href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"><i class="fa fa-info-circle"></i>  {{ __lang('details') }}</a></li>
                                </ul>
                                <span>{{ sitePrice($course->fee) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                    @endif
                @endforeach
                @endif
            </div>
            <!-- Section Button -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="browse-btn2 text-center mt-50">
                        <a href="{{ route('courses') }}" class="btn">{{ __lang('all-courses') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Popular Course End -->
    @endif

    @if(optionActive('instructors'))
    <!--? Team Ara Start -->
    <div class="team-area pt-160 pb-160 section-bg mt-5" data-background="{{ tasset('assets/img/gallery/section_bg02.png') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="cl-xl-7 col-lg-8 col-md-10">
                    <!-- Section Tittle -->
                    <div class="section-tittle section-tittle2 text-center mb-70">
                        <span>{{ toption('instructors','sub_heading') }}</span>
                        <h2>{{ toption('instructors','heading') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach(toption('instructors','instructors') as $admin)
                    @php
                        $admin = \App\Admin::find($admin);
                    @endphp
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-team mb-30">
                        <div class="team-img">
                            @if(empty($admin->user->picture))
                                <img src="{{ asset('img/user.png') }}" alt="">
                            @else
                                <img src="{{ asset($admin->user->picture) }}" alt="">
                        @endif
                            <!-- Blog Social -->
                            <ul class="team-social">
                                @if(!empty($admin->social_facebook))
                                    <li><a  href="{{  $admin->social_facebook}}"><i class="fab fa-facebook-f"></i></a></li>
                                @endif

                                @if(!empty($admin->social_twitter))
                                        <li><a  href="{{  $admin->social_twitter }}"><i class="fab fa-twitter"></i></a></li>
                                @endif

                                @if(!empty($admin->social_linkedin))
                                        <li><a  href="{{  $admin->social_linkedin }}"><i class="fab fa-linkedin"></i></a></li>
                                @endif

                                @if(!empty($admin->social_instagram))
                                        <li><a  href="{{  $admin->social_instagram }}"><i class="fab fa-instagram"></i></a></li>
                                @endif

                                @if(!empty($admin->social_website))
                                        <li><a  href="{{  $admin->social_website }}"><i class="fas fa-globe"></i></a></li>
                                @endif
                            </ul>
                        </div>
                        <div class="team-caption">
                            <h3><a  href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h3>
                            <p>{{ limitLength($admin->about,100) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Section Button -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="browse-btn2 text-center mt-70">
                        <a href="{{ route('instructors') }}" class="btn white-btn">{{ __lang('all-instructors') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team Ara End -->
    @endif

    @if(optionActive('homepage-about'))
    <!--? About Law Start-->
    <div class="about-area section-padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12">
                    <div class="about-caption mb-50">
                        <!-- Section Tittle -->
                        <div class="section-tittle mb-35">
                            <span>{{ toption('homepage-about','sub_heading') }}</span>
                            <h2>{{ toption('homepage-about','heading') }}</h2>
                        </div>
                        <p>{!! clean(toption('homepage-about','text')) !!}</p>

                        <a href="{{ toption('homepage-about','button-url') }}" class="btn">{{ toption('homepage-about','button-text') }}</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <!-- about-img -->
                    <div class="about-img ">
                        @if(!empty(toption('homepage-about','image_1')))
                        <div class="about-font-img d-none d-lg-block">
                            <img src="{{ asset(toption('homepage-about','image_1')) }}" alt="">
                        </div>
                        @endif
                            @if(!empty(toption('homepage-about','image_2')))
                        <div class="about-back-img ">
                            <img src="{{ asset(toption('homepage-about','image_2')) }}" alt="">
                        </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Law End-->
    @endif

    @if(optionActive('testimonials'))
    <!--? Testimonial Start -->
    <div class="testimonial-area fix pt-180 pb-180 section-bg" data-background="{{ tasset('assets/img/gallery/section_bg03.png') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9 col-md-9">
                    <div class="h1-testimonial-active">
                    @for($i=1;$i <= 6; $i++)
                        @if(!empty(toption('testimonials','name'.$i)))


                            <!-- Single Testimonial -->
                        <div class="single-testimonial pt-65">
                            <!-- Testimonial tittle -->
                            <div class="testimonial-icon mb-45">
                                @if(!empty(toption('testimonials','image'.$i)))
                                    <img   class="ani-btn " src="{{ asset(toption('testimonials','image'.$i)) }}" >
                                @else
                                    <img   class="ani-btn "    src="{{ asset('img/man.jpg') }}">
                                @endif
                            </div>
                            <!-- Testimonial Content -->
                            <div class="testimonial-caption text-center">
                                <p>{{ toption('testimonials','text'.$i) }}</p>
                                <!-- Rattion -->
                                <div class="testimonial-ratting">
                                    @for($j=1;$j <= toption('testimonials','stars'.$i); $j++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <div class="rattiong-caption">
                                    <span>{{ toption('testimonials','name'.$i) }}<span> - {{ toption('testimonials','role'.$i) }}</span> </span>
                                </div>
                            </div>
                        </div>

                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
    @endif

    @if(optionActive('blog'))
    <!--? Blog Area Start -->
    <div class="home-blog-area section-padding30">
        <div class="container">
            <!-- Section Tittle -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tittle text-center mb-50">
                        <span>{{ toption('blog','sub_heading') }}</span>
                        <h2>{{ toption('blog','heading') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(intval(toption('blog','limit')))->get() as $post)

                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="home-blog-single mb-30">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                @if(!empty($post->cover_photo))
                                    <img src="{{ asset($post->cover_photo) }}" alt="">
                            @endif
                                <!-- Blog date -->
                                <div class="blog-date text-center">
                                    <span>{{  \Carbon\Carbon::parse($post->publish_date)->format('D') }}</span>
                                    <p>{{  \Carbon\Carbon::parse($post->publish_date)->format('M') }}</p>
                                </div>
                            </div>
                            <div class="blog-cap">
                                <p>{{ \Carbon\Carbon::parse($post->publish_date)->diffForHumans() }}</p>
                                <h3><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                                <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}" class="more-btn">{{ __lang('read-more') }} »</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Blog Area End -->
    @endif

@endsection




