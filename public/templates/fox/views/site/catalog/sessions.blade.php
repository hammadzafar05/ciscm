@extends(TLAYOUT)

@section('page-title',$pageTitle)
@section('inline-title',$pageTitle)

@section('content')
<style>
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
    padding: 4px 10px;
    border: 2px solid #576299;
    border-radius: 10px;
    height: 76px;
    text-align: center;
    background: #fafd54;
    box-shadow: 20px 20px 50px 10px pink inset;
    font-size=13px !important: ;
    font-size: 13px !important;
}

.course-block .course-content h3 a {
    color: blue !important;
    font-size: 12px !important;
    font-weight: 700;
}
</style>
    <section class="ftco-section">
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-md-3">
                    
                    @if($subCategories || $parent)
                    <ul class="list-group mb-5">
                        <li class="list-group-item active">{{ __lang('sub-categories') }}</li>
                        @if($parent)
                            <li class="list-group-item">
                                <a href="{{ route('courses') }}?group={{ $parent->id }}" ><strong>{{ __lang('parent') }}: {{ $parent->name }}</strong></a>
                            </li>
                            @endif

                       @if($subCategories)
                        @foreach($subCategories as $category)
                            <li class="list-group-item">
                                <a href="{{ route('courses') }}?group={{ $category->id }}" >{{ $category->name }}</a>
                            </li>
                        @endforeach
                           @endif
                    </ul>
                    @endif

                    <ul class="list-group">
                        <li class="list-group-item active">{{ __lang('categories') }}</li>
                        <li class="list-group-item"><a href="{{ route('courses') }}">{{ __lang('all-courses') }}</a></li>
                        @foreach($categories as $category)
                        <li class="list-group-item @if(request()->get('group') == $category->id) active @endif"><a href="{{ route('courses') }}?group={{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach

                    </ul>


                    <div class="card card-default" data-toggle="card-collapse" data-open="true">
                        <div class="card-header card-collapse-trigger">
                            {{  __lang('filter')  }}
                        </div>
                        <div class="card-body">
                            <form id="filterform" class="form" role="form"  method="get" action="{{  route('sessions') }}">
                                <div class="form-group input-group margin-none">
                                    <div class=" margin-none">
                                        <input type="hidden" name="group" value="{{  $group  }}"/>

                                        <div class="form-group">
                                            <label  for="filter">{{  __lang('search')  }}</label>
                                            {{  formElement($text)  }}
                                        </div>
                                        <div  class="form-group">
                                            <label  for="group">{{  __lang('sort')  }}</label>
                                            {{  formElement($sortSelect)  }}
                                        </div>

                                        <div  >
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{  __lang('filter')  }}</button>
                                            <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-secondary">{{  __lang('clear')  }}</button>

                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>



                </div>

                <div class="col-md-9">
                    <div class="row">
                        @if($paginator->count()==0)
                            {{ __lang('no-results') }}
                        @endif
                        @foreach($paginator as $course)

                                  <div class="col-md-4 course ftco-animate">
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

                            @if(false)
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="single-recent-cap mb-30 ">
                                    <div class="recent-img text-center" style="max-height: 300px">
                                        @if(!empty($course->picture))
                                            <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"><img class="course-img" src="{{ asset($course->picture) }}" alt="{{ $course->name }}"></a>
                                        @endif

                                    </div>
                                    <div class="recent-cap pb-5">
                                        <span>
                                            {{ __lang('starts') }}: {{ showDate('d M, Y',$course->start_date) }}
                                        </span>
                                        <h4><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h4>
                                        <p>{{ limitLength(strip_tags($course->short_description),50) }}</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span>{{ sitePrice($course->fee) }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}" class="btn btn-primary float-right btn-sm"><i class="fa fa-info-circle"></i> {{ __lang('details') }}</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endif

                        @endforeach
                    </div>

                    <div>
                        @php
                            // add at the end of the file after the table
                                echo paginationControl(
                                // the paginator object
                                    $paginator,
                                    // the scrolling style
                                    'sliding',
                                    // the partial to use to render the control
                                    null,
                                    // the route to link to when a user clicks a control link
                                    route('courses')
                                );

                        @endphp
                    </div>
                </div>

            </div>
        </div> <!-- row -->
        </div>
    </section>




@endsection
