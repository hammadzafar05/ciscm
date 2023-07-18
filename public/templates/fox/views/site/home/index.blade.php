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

                                            .slhc{{ $i }} {
                        color: # {{ toption('slideshow','heading_font_color'.$i) }}  !important;
                    }

                    @endif

                                        @if(!empty(toption('slideshow','text_font_color'.$i)))
                                        .sltx{{ $i }} {
                        color: # {{ toption('slideshow','text_font_color'.$i) }}  !important;
                    }
                    @endif

                </style>
            @endsection
            @php
                $image = (new \App\Helper\AppHelper)->imageExits(toption('slideshow','file'.$i));
            @endphp
            <div class="slider-item"
                 @if(!empty(toption('slideshow','file'.$i)))
                    style="background-image:url({{ $image }});"
                 @endif >
                <div class="overlay"></div>
                <div class="container">
                    <div class="row no-gutters slider-text align-items-center justify-content-start" data-scrollax-parent="true">
                        <div class="col-md-6 ftco-animate">
                            <h1 class="mb-4 @if(!empty(toption('slideshow','heading_font_color'.$i)))  slhc{{ $i }} @endif">{{ toption('slideshow','slide_heading'.$i) }}</h1>
                            <p @if(!empty(toption('slideshow','text_font_color'.$i))) class="sltx{{ $i }}" @endif >{{ toption('slideshow','slide_text'.$i) }}</p>
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
                            <div class="col-md-3 d-flex services align-self-stretch py-2 px-1 ftco-animate {{ ($i%2==0) ? 'bg-primary' : 'bg-dark' }}">
                                <div class="media block-6 d-block text-center">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="{{ toption('homepage-services','icon'.$i) }}"></span>
                                    </div>
                                    <div class="media-body p-2 mt-1">
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

    <!--training calendar section start-->
    <!--<section class="section-padding course-category" >-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center justify-content-center">-->
    <!--            <div class="col-lg-9">-->
    <!--                <div class="section-heading center-heading">-->
    <!--                    <h3>Program Calendar</h3>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <br>-->
    <!--        <div class="row">-->
    <!--            <div class="timetable-example">-->
    <!--                <div class="tiva-timetable" data-start="monday"></div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    <!--training calendar section end-->

    <!--course category section start-->
    <section class="section-padding course-category bg-light" >
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-9">
                    <div class="section-heading center-heading">
                        <span class="subheading">Top Categories</span>
                        <h3>Explore by Category</h3>
                        <p>It's time to amplify your online Career</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                @foreach(\App\CourseCategory::with('courses')->orderBy('sort_order')->where('enabled',1)->limit(100)->get() as $category)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-course-category cat-1">
                            <h5>
                                <a href="{{ route('courses') }}?group={{ $category->id }}"> {{ $category->name }} </a>
                            </h5>
                            @php
                                //use App\V2\Model\SessionTable;
                                $count = $category->courses()->count();
                                $sessionTable = new \App\V2\Model\SessionTable();
                                $count = $sessionTable->getPaginatedRecords(false,null,true,null,$category->id,null,null,true,null,'website')->count();
								//getPaginatedRecords(1.$paginated=false,2.$id=null,3.$activeOnly=false,4.$filter=null,5.$group=null,$order=null,$type=null,$futureOnly=false,$payment=null,$request_form=null)

                            @endphp
                            <p>{{ $count }} courses</p>
                        </div>
                    </div>
                @endforeach
                
               
            </div>
            <div class="row">
                 <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="single-course-category cat-1" style="background: #f80101;padding:0 0 0 0 !important">
                            <h5 style="padding:8px 0px">
                                <a style="color: yellow;
    font-weight: 800;" target="_blank" href="https://mba-edu.uk/">MBA/Masters</a>
                            </h5>
                        
                            <p>20 courses</p>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!--course section end-->

    @if(optionActive('homepage-about'))
    <section class="feature section-padding">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7">
                    <div class="section-heading center-heading">
                        <span class="subheading">Maximize your potentials</span>
                        <h3>Learn the secrets to Life Success</h3>
                        <p>{{ toption('homepage-about','heading') }}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row ">
                @for($i=1;$i<=6;$i++)
                    @if(!empty(toption('homepage-about','heading'.$i)))
                        {{--<div class="col-lg-3 col-md-6 col-xl-3">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="{{ toption('homepage-about','icon'.$i) }}"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>{{ toption('homepage-about','heading'.$i) }}</h4>
                                    <p class="comment more">{!! strip_tags(clean(toption('homepage-about','text'.$i))) !!}</p>
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-lg-4 col-md-6 col-xl-4">
                            <div class="feature-item feature-style-2">
                                <div class="feature-icon">
                                    <i class="{{ toption('homepage-about','icon'.$i) }}"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>{{ toption('homepage-about','heading'.$i) }}</h4>
                                    <p class="comment more">{!! (clean(toption('homepage-about','text'.$i))) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="text-center mt-3">
                        <a href="{{ route('courses') }}"
                           class="btn btn-solid-border">Explore Courses</a>
                        @guest
                            <a href="{{ route('register') }}"
                               class="btn btn-main">@lang('default.register')</a>
                        @else
                            <a href="{{ route('home') }}"
                               class="btn btn-main">@lang('default.my-account')</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Clients logo Section Start -->
    <section class="cta-2 clients section-padding bg-gray">
        <div class="container">
            <div class="row ">
                <div class="col-xl-10">
                    <div class="section-heading ">
                        <span class="subheading">Accreditation</span>
                        <h3>Accreditation Body</h3>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="row mx-auto">
                    @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->where('show_at_accreditation_page',1)->orderBy('publish_date','desc')->limit(6)->get() as $post)
                        <div class="col-lg-2 col-sm-6 col-xl-2">
                            <div class="client-logo">
                                @php
                                    $image = (new \App\Helper\AppHelper)->imageExits($post->cover_photo);
                                @endphp
                                <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">
                                    <img src="{{ $image }}"
                                         alt="{{ $post->title }}"
                                         class="img-fluid">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Clients logo Section End -->

    @if(optionActive('homepage-about'))
    <section class="about-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                
                <!--<div class="col-xl-7 col-lg-7 col-md-7">-->
                <!--    <div  class="section-heading mt-4 mt-lg-0 ">-->
                <!--        <h3>Program Calendar</h3>-->
                <!--    </div>-->
                    
                <!--    <div class="timetable-example">-->
                <!--    <div class="tiva-timetable" data-start="monday"></div>-->
                <!--</div>-->
                <!--</div>-->
                
                
                <div class="col-xl-12 col-lg-12 col-md-12">
                        <div  class="section-heading mt-4 mt-lg-0 ">
                            <h3>Want Us To Call You Back!</h3>
                        </div>
                         <div id="tidycal-embed" data-path="worldacademy/course-information-discussion">
                         </div>
                   
                    </div>
                </div>
                
               

                <!--<div class="col-xl-5 pl-5 col-lg-5 col-md-5">-->
                    
                <!--    <div class="section-heading mt-4 mt-lg-0 ">-->
                <!--        <span class="subheading">WARD - Complete Solution for your Career</span>-->
                <!--        <h3>Become Certified, Accelerate your Career</h3>-->
                <!--        <p>{!! clean( toption('homepage-about','text') ) !!}</p>-->

                <!--    </div>-->
                <!--    <ul class="about-features">-->
                <!--        @for($i=1;$i<=6;$i++)-->
                <!--            @if(!empty(toption('homepage-about','heading'.$i)))-->
                <!--                <li>-->
                <!--                    <i class="fa fa-check"></i>-->
                <!--                    <h5>{{ toption('homepage-about','heading'.$i) }}</h5>-->
                <!--                </li>-->
                <!--            @endif-->
                <!--        @endfor-->
                <!--    </ul>-->
                <!--    <a href="{{ route('courses') }}" class="btn btn-main">Our Courses</a>-->
                <!--</div>-->
                
            </div>
        </div>
    </section>
    @endif
    
    


    <!--Featiured For the Current Months==============================-->
    <?php
     $date=date('Y-m-d');
       $current_courses=DB::table('courses')->whereYear('start_date',Carbon\Carbon::now()->year)->whereMonth('start_date',Carbon\Carbon::now()->month)->where('start_date','>=',$date)->count();
    ?>
    @if($current_courses>0)
        <section class="ftco-section bg-grey section-padding">
            <div class="container-fluid px-4">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7">
                        <div class="section-heading center-heading" style="padding-bottom: 37px;">
                            <h3 style="color:#ca20d5;color: #ca20d5;
    font-weight: 800;">Programmes in {{date('F-Y')}}</h3>
                        </div>
                    </div>
                </div>
                
                
                
                
                
                <div class="row">
                    @php
 $date=date('Y-m-d');
$current_courses=DB::table('courses')->whereYear('start_date',Carbon\Carbon::now()->year)->whereMonth('start_date',Carbon\Carbon::now()->month)->where('start_date','>=',$date)->get();

//dd($current_courses);
                        //$courses = toption('featured-courses','courses');
                    @endphp
                        @foreach($current_courses as $course)
                            @if(!empty($course) && \App\Course::find($course->id))
                                @php
                                    $course = \App\Course::find($course->id);
                                    $image = (new \App\Helper\AppHelper)->imageExits($course->picture);
                                @endphp
                                <div class="col-md-3 course ftco-animate">
                                    <div class="course-block" style="    background: #e7e2a2;
    !important: ;
    background: #e7e2a2;
    !important: ;
    border-radius: 0px 16px 16px 0px;
    box-shadow: 1px;
    box-shadow: 0px 0px 0px 5px #5bad9e;">
                                        
                                        <?php
                                     
                    $getCateCourse=DB::table('course_course_category')->where('course_id',$course->id)->first();
                     $getCateCourseCount=DB::table('course_course_category')->where('course_id',$course->id)->count();
                   //dd($getCateCourse->course_category_id);
                    if($getCateCourseCount>0){
                      $getCat=DB::table('course_categories')->where('id',$getCateCourse->course_category_id)->first();  
                    }
                    ?>
                    
                                        <div class="background_design" style="background: red;padding: 14px 0;    border-radius: 1px 17px 1px 2px;color: #fff;
    text-align: center;
    font-size: 16px;
    font-weight: 700;">
                                {{$getCat->name??""}}            
                                        </div>
                                            
                                        <div class="text pt-4 course-content">
                                          
                                            <h3 class="online_class"><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h3>
                                            
                                            
                                            <p style="width:263px;color: black !important;">{{ limitLength(strip_tags($course->short_description),50) }}</p>
                                            <p style="background:#2e79b9;
    color: #fff;
    padding: 10px 10px;
    /* border-radius: 5px; */
    /* float: left; */
    position: relative;
    left: -15px;
    border-radius: 0px 10px 10px 0px;
    font-size: 12px;
    font-weight: 700;
"> @if($course->type=='c') Fully Online <span style="color:#09f7d6">(self-paced learing) </span> @else Online Class Based  <span style="color:#09f7d6">({{date('d-M-y',strtotime($course->start_date))}})</span> @endif </p>
                                             <p class="meta d-flex">
                                                  @if($course->regular_fee)
                                                      <span style="color:#8031d7;font-size: 16px;font-weight: bold;"><del style="color:red">FEE {{sitePrice($course->regular_fee)}}</del> </span>
                                        <span  style="color:#2e79b9;font-size: 16px;font-weight: bold;padding-left: 20px;"><i class="fa fa-money-bill"></i>FEE: {{ sitePrice($course->fee) }}</span> 

                                                      @else
                                                      <span  style="color:#2e79b9;font-size: 16px;font-weight: bold;padding-left: 20px;"><i class="fa fa-money-bill"></i>FEE{{ sitePrice($course->fee) }}</span> 
                                                      @endif
                                            
                                                
                                            <p>
                                                <button type="button" data-url="{{url('/single-important-information/'.$course->id)}}" data-target="#eligibleModal" onclick="eligibleView({{ $course->id }})" id="{{ $course->id }}" style="font-size: 14px;
    border-radius: 6px !important;
    background: #e7e2a2;
    border: 1px solid #af239d;
    color: black;font-weight: 600;
    text-transform: capitalize;" class="btn btn-primary">Basic Information</button>
                                                
                                                <a target="_blank" style="    font-size: 14px;
    border-radius: 6px !important;
    background: #e7e2a2;
    border: 1px solid #af239d;
    float: right;
    font-weight: 600;
    color: black;
" href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"
                                                  class="btn btn-primary">{{ __lang('details') }}</a>
                                            </p>
                                            
                                             <p>
                                               
                                                  
                                                <div class="down_page" style="padding-top:10px;padding-bottom:20px">
                                                    
                                                    
                                                    <!--social media part-->
                                                      <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;" href="https://worldacademy.org.uk/form/app/form?id=kO1udg"
                                                  class="btn btn-primary">Apply Now</a>
                                                  
                                                  <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;background:green !important; border: 1px solid green;" href="https://api.whatsapp.com/send?phone=%2B8801799400500"
                                                  class="btn btn-primary"><span class="icon-whatsapp"></span></a>
                                                  
                                                  @if(!empty(toption('footer','social_facebook')))
                                                  <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;background:#4267B2;border: 1px solid #4267B2;" href="{{toption('footer','social_facebook')}}"
                                                  class="btn btn-primary"> <span class="icon-facebook"></span></a>
                                                  @endif
                                                  
                                                  <a target="_blank" style="font-size: 10px;
    border-radius: 6px !important;background-image: linear-gradient(#FF6968,#A334FA,#0695FF);border: 1px solid #4267B2;float: right;" href="http://m.me/wardbd"
                                                  class="btn btn-primary"> <span class="fa fa-messenger"></span>messenger</a>
                                                </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="text-center mt-5">
                            Take the control of your life back and start doing things to make your dream come true. <a href="{{ route('courses') }}" class="font-weight-bold text-underline">View all courses </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @else 
       <section class="ftco-section bg-grey section-padding">
            <div class="container-fluid px-4">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7">
                        <div class="section-heading center-heading">
                            <h3 style="color:#ca20d5;color: #ca20d5;
    font-weight: 800;">Programmes in {{date('F-Y')}}</h3>
                            <p>All Programmes of {{date('F-Y')}} have been started . <a target="_blank" href="https://worldacademy.org.uk/form/app/form?id=kO1udg">Click here</a> to Enroll for the next Batch </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    
    
    <!--Next Month Courses============================================-->
    
    @if(optionActive('featured-courses'))
        <section class="ftco-section bg-grey section-padding">
            <div class="container-fluid px-4">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7">
                        <div class="section-heading center-heading" style="    padding-bottom: 37px;">
                            <?php
                                $next_month=date('F-Y',strtotime("+1 month"));
                            ?>
                            <h3 style="color:#ca20d5;color: #ca20d5;
    font-weight: 800;">Programmes in {{$next_month}}</h3>
                        </div>
                    </div>
                </div>
               
                
                
                <div class="row">
                    @php
$next_month_courses=DB::table('courses')->whereYear('start_date',Carbon\Carbon::now()->year)->whereMonth('start_date',Carbon\Carbon::now()->addMonth(1))->get();

//dd($current_courses);
                        //$courses = toption('featured-courses','courses');
                    @endphp
                        @foreach($next_month_courses as $course)
                            @if(!empty($course) && \App\Course::find($course->id))
                                @php
                                    $course = \App\Course::find($course->id);
                                    $image = (new \App\Helper\AppHelper)->imageExits($course->picture);
                                @endphp
                                <div class="col-md-3 course ftco-animate">
                                    <div class="course-block" style="    background: #e7e2a2;
    !important: ;
    background: #e7e2a2;
    !important: ;
    border-radius: 0px 16px 16px 0px;
    box-shadow: 1px;
    box-shadow: 0px 0px 0px 5px #5bad9e;">
                                        <?php
                                     
                    $getCateCourse=DB::table('course_course_category')->where('course_id',$course->id)->first();
                     $getCateCourseCount=DB::table('course_course_category')->where('course_id',$course->id)->count();
                   //dd($getCateCourse->course_category_id);
                    if($getCateCourseCount>0){
                      $getCat=DB::table('course_categories')->where('id',$getCateCourse->course_category_id)->first();  
                    }
                    
                        ?>
                                        <div class="background_design" style="background: red;padding: 14px 0;    border-radius: 1px 17px 1px 2px;    color: #fff;text-align: center;font-size: 16px; font-weight: 700;">{{$getCat->name??""}}</div>
                                            
                                        <div class="text pt-4 course-content">
                                          
                                            <h3 class="online_class"><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h3>
                                            
                                            
                                            <p style="width:263px;color:black">{{ limitLength(strip_tags($course->short_description),50) }}</p>
                                            <p style="background:#2e79b9;
    color: #fff;
    padding: 10px 10px;
    /* border-radius: 5px; */
    /* float: left; */
    position: relative;
    left: -15px;
    border-radius: 0px 10px 10px 0px;
    font-size: 12px;
    font-weight: 700;
"> @if($course->type=='c') Fully Online <span style="color:#09f7d6">(self-paced learing) </span> @else Online Class Based  <span style="color:#09f7d6">({{date('d-M-y',strtotime($course->start_date))}})</span> @endif </p>
                                             <p class="meta d-flex">
                                                  @if($course->regular_fee)
                                                      <span style="color:#8031d7;font-size: 16px;font-weight: bold;">FEE:<del style="color:red"> {{sitePrice($course->regular_fee)}}</del> </span>
                                        <span  style="color:#2e79b9;font-size: 16px;font-weight: bold;padding-left: 20px;"><i class="fa fa-money-bill"></i> {{ sitePrice($course->fee) }}</span> 

                                                      @else
                                                      <span  style="color:#2e79b9;font-size: 16px;font-weight: bold;padding-left: 20px;"><i class="fa fa-money-bill"></i>FEE: {{ sitePrice($course->fee) }}</span> 
                                                      @endif
                                            
                                                
                                            <p>
                                                <button type="button" data-url="{{url('/single-important-information/'.$course->id)}}" data-target="#eligibleModal" onclick="eligibleView({{ $course->id }})" id="{{ $course->id }}" style="font-size: 14px;
    border-radius: 6px !important;
    background: #e7e2a2;
    border: 1px solid #af239d;
    color: black;font-weight: 600;
    text-transform: capitalize;" class="btn btn-primary">Basic Information</button>
                                                
                                                <a target="_blank" style="    font-size: 14px;
    border-radius: 6px !important;
    background: #e7e2a2;
    border: 1px solid #af239d;
    float: right;
    font-weight: 600;
    color: black;
" href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"
                                                  class="btn btn-primary">{{ __lang('details') }}</a>
                                            </p>
                                            
                                             <p>
                                               
                                                  
                                                <div class="down_page" style="padding-top:10px;padding-bottom:20px">
                                                    
                                                    
                                                    <!--social media part-->
                                                      <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;" href="https://worldacademy.org.uk/form/app/form?id=kO1udg"
                                                  class="btn btn-primary">Apply Now</a>
                                                  
                                                  <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;background:green !important; border: 1px solid green;" href="https://api.whatsapp.com/send?phone=%2B8801799400500"
                                                  class="btn btn-primary"><span class="icon-whatsapp"></span></a>
                                                  
                                                  @if(!empty(toption('footer','social_facebook')))
                                                  <a target="_blank" style="font-size: 12px;
    border-radius: 6px !important;background:#4267B2;border: 1px solid #4267B2;" href="{{toption('footer','social_facebook')}}"
                                                  class="btn btn-primary"> <span class="icon-facebook"></span></a>
                                                  @endif
                                                  
                                                  <a target="_blank" style="font-size: 10px;
    border-radius: 6px !important;background-image: linear-gradient(#FF6968,#A334FA,#0695FF);border: 1px solid #4267B2;float: right;" href="http://m.me/wardbd"
                                                  class="btn btn-primary"> <span class="fa fa-messenger"></span>messenger</a>
                                                </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="text-center mt-5">
                            Take the control of your life back and start doing things to make your dream come true. <a href="{{ route('courses') }}" class="font-weight-bold text-underline">View all courses </a>
                        </div>
                    </div>
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
                    @php
                        $instructors = toption('instructors','instructors');
                    @endphp
                    @if(is_array($instructors))
                        @foreach(toption('instructors','instructors') as $admin)
                            @php
                                $admin = \App\Admin::find($admin);
                                $image = (new \App\Helper\AppHelper)->imageExits($admin->user->picture);
                            @endphp
                            <div class="col-md-6 col-lg-3 ftco-animate">
                                <div class="staff">
                                    <div class="img-wrap d-flex align-items-stretch">
                                        <div class="img align-self-stretch"
                                             style="background-image: url({{ $image }});"></div>
                                    </div>
                                    <div class="text pt-3 text-center">
                                        <h3><a href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h3>

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
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="ftco-section ftco-consult ftco-no-pt ftco-no-pb"
             style="background-image:url({{ tasset('images/xbg_5.jpg.webp') }})" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-6 py-5 px-md-5">
                    <div class="py-md-5">
                        <div class="heading-section heading-section-white ftco-animate mb-5">
                            <h2 class="mb-4">Request a call back</h2>
                            <p>One of our customer representative will call you shortly.</p>
                        </div>
                        <form class="appointment-form ftco-animate" action="{{ route('contact.send-mail') }}" method="post" >
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea required class="form-control w-100" name="message" id="message" cols="30" rows="2" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-message')) }}'" placeholder=" {{ __t('enter-message') }}"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input required  class="form-control valid" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-your-name')) }}'" placeholder="{{ __t('enter-your-name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input required  class="form-control valid" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-email')) }}'" placeholder="{{ __t('enter-email') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-subject')) }}'" placeholder="{{ __t('enter-subject') }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label>@lang('default.verification')</label><br/>
                                    <label for="">{!! clean( captcha_img() ) !!}</label>
                                    <input class="form-control" type="text" name="captcha" placeholder="@lang('default.verification-hint')"/>

                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __t('send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(optionActive('blog'))
        <section class="ftco-section section-padding bg-light">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7">
                        <div class="section-heading center-heading">
                            <span class="subheading">Get ffffInstant Access To Expert solution</span>
                            <h3>{{ toption('blog','heading') }}</h3>
                            <p>{{ toption('blog','description') }}</p>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->where('show_at_accreditation_page',0)->where('show_at_success_stories',0)->orderBy('publish_date','desc')->limit(intval(toption('blog','limit')))->get() as $post)
                        <div class="col-md-6 col-lg-3 ftco-animate">
                            <div class="blog-entry">
                                @php $image = (new \App\Helper\AppHelper)->imageExits($post->cover_photo); @endphp
                                <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}"
                                   class="block-20 d-flex align-items-end"
                                   style="background-image: url('{{ $image }}');">
                                    <div class="meta-date text-center p-2">
                                        <span class="day">{{  \Carbon\Carbon::parse($post->publish_date)->format('D') }}</span>
                                        <span class="mos">{{  \Carbon\Carbon::parse($post->publish_date)->format('M') }}</span>
                                        <span class="yr">{{  \Carbon\Carbon::parse($post->publish_date)->format('Y') }}</span>
                                    </div>
                                </a>
                                <div class="text bg-white p-4">
                                    <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                                    <p class="comment more">{{ limitLength(strip_tags($post->content),100) }}</p>
                                    <div class="d-flex align-items-center mt-4">
                                        <p class="mb-0">
                                            <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}"
                                               class="btn btn-primary">{{ __lang('read-more') }} <span class="ion-ios-arrow-round-forward"></span>
                                            </a>
                                        </p>
                                        <p class="ml-auto mb-0 display-hide">
                                            @if($post->admin)
                                                <a @if($post->admin->public == 1)  href="{{ route('instructor',['admin'=>$post->admin_id]) }}"
                                                   @endif class="mr-2">{{ $post->admin->user->name.' '.$post->admin->user->last_name }}</a>
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
        <section class="ftco-section testimony-section testimonial section-padding">
            <div class="container">
                <div class="section-heading center-heading">
                    <span class="subheading">Testimonials</span>
                    <h3>{{ toption('testimonials','heading') }}</h3>
                    <p>{{ toption('testimonials','description') }}</p>
                </div>
                <div class="row ftco-animate justify-content-center">
                    <div class="col-md-12">
                        <div class="carousel-testimony owl-carousel">
                            @for($i=1;$i <= 6; $i++)
                                @if(!empty(toption('testimonials','name'.$i)))
                                    {{--<div class="item">
                                        <div class="testimony-wrap d-flex">
                                            @php
                                                $image = (new \App\Helper\AppHelper)->imageExits(toption('testimonials','image'.$i));
                                            @endphp
                                            <div class="user-img mr-4" style="background-image: url({{ $image }})"></div>
                                            <div class="text ml-2">
                                                <span class="quote d-flex align-items-center justify-content-center">
                                                  <i class="icon-quote-left"></i>
                                                </span>                                                <span class="quote d-flex align-items-center justify-content-center">
                                                  <i class="icon-quote-left"></i>
                                                </span>
                                                <p>{{ toption('testimonials','text'.$i) }}</p>
                                                <p class="name">{{ toption('testimonials','name'.$i) }}</p>
                                                <span class="position">{{ toption('testimonials','role'.$i) }}</span>
                                            </div>
                                        </div>
                                    </div>--}}
                                    <div class="testimonial-item">
                                        <i class="fa fa-quote-right"></i>
                                        <div class="client-info">
                                            @php
                                                $image = (new \App\Helper\AppHelper)->imageExits(toption('testimonials','image'.$i));
                                            @endphp
                                            <img src="{{ $image }}"
                                                 alt=""
                                                 class="img-fluid">
                                            <div class="testionial-author">
                                                {{ toption('testimonials','name'.$i) }} - {{ toption('testimonials','role'.$i) }}
                                            </div>
                                        </div>
                                        <div class="testimonial-info-desc">
                                            <p class="">{{ toption('testimonials','text'.$i) }}</p>{{--comment more--}}
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

    <!-- COunter Section start -->
    <section class="counter-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-10">
                    <div class="section-heading">
                        <span class="subheading">Maximize your potentials</span>
                        <h3>We break down barriers so teams can focus on what matters – learning together to create
                            online career you love.</h3>
                    </div>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-3 col-md-6">
                    <div class="counter-item">
                        <h6>Instructors</h6>
                        <div class="count">
                            <span class="counter">53</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="counter-item">
                        <h6>Total Courses</h6>
                        <div class="count">
                            <span class="counter">124</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="counter-item">
                        <h6>Registered Enrolls</h6>
                        <div class="count">
                            <span class="counter">6992</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="counter-item">
                        <h6>Satisfaction rate</h6>
                        <div class="count">
                            <span class="counter">100</span>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- COunter Section END -->

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <hr>
            </div>
        </div>
    </div>




    @if(optionActive('footer-gallery'))
        <section class="ftco-gallery">
            <div class="container-wrap">
                <div class="row no-gutters">
                    @for($i=1;$i <= 4; $i++)
                        @if(!empty(toption('footer-gallery','image'.$i)))
                            <div class="col-md-3 ftco-animate">
                                @php
                                    $image = (new \App\Helper\AppHelper)->imageExits(toption('footer-gallery','image'.$i));
                                @endphp
                                <a href="{{ $image }}"
                                   class="gallery image-popup img d-flex align-items-center"
                                   style="background-image: url({{ $image }});">
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

    <section class="cta bg-gray section-padding">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7">
                    <div class="section-heading center-heading mb-0">
                        <span class="subheading">{{ __t('stay-updated') }}</span>
                        <h3>Subscribe to our newsletters</h3>
                        @if(!empty(toption('footer','newsletter-code')))
                            {!! toption('footer','newsletter-code') !!}
                        @else
                            <form action="#" class="">
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control mb-2 text-center"
                                           placeholder="Enter email address">
                                    <button type="submit"
                                            value="Subscribe"
                                            class="form-control px-3 btn-danger"
                                            style="background: #fd5f00 !important;color: #fff !important;">Subscribe</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <!--Partner Part-->
    <?php 
        $delegate=DB::table('delegates')->where('status',1)->get();
    ?>
        <!-- Partner Start -->
        <section id="rs-partner" class="rs-partner pt-70 pb-70">
            <div class="container">
                 <div class="row align-items-center justify-content-center">
                     <div class="col-lg-12">
                    <div class="section-heading center-heading mb-0">
                       <h2> <span class="subheading">Our Delegants</span></h2>
				<div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="80" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="false" data-nav="false" data-nav-speed="false" data-mobile-device="2" data-mobile-device-nav="false" data-mobile-device-dots="false" data-ipad-device="4" data-ipad-device-nav="false" data-ipad-device-dots="false" data-md-device="3" data-md-device-nav="false" data-md-device-dots="false">
                    @foreach($delegate as $row)
                    <div class="partner-item">
                        <a href="#"><img class="img-fluid" src="{{asset('public/usermedia/delegate/'.$row->image)}}" alt="Delegant" ></a>
                    </div>
                    @endforeach
                </div>
                </div>
                </div>
                </div>
            </div>
        </section>
        
        
        
       
        <!-- Partner End -->

<!-- Button trigger modal -->



    <div style="display: none">
        <?php
        $timezone = date_default_timezone_get();
        echo "<br>The current server timezone is: " . $timezone;
        echo "<br>The current server time is: " . date('Y-M-d h:i A');
        ?>
    </div>


@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/timetable/timetable.css') }}">
    <style>
        .tiva-timetable{
            height: 100%;
        }
    </style>
@endsection
@section('footer')


<!-- Button trigger modal -->


<!-- Modal -->

   <script type="text/javascript" src="{{ asset('client/vendor/timetable/timetable.js') }}"></script>
@endsection