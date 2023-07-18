@extends(TLAYOUT)
{{--MARUF START--}}
@php
    $course_name = explode(' - Batch ',$course->name);
    $course_name = @$course_name[0];
@endphp
@section('page-title',$course_name)
@section('inline-title',$course_name)
{{--MARUF END--}}
@section('crumb')
    <span class="mr-2"><a href="@route('courses')">{{ __lang('courses') }} <i class="ion-ios-arrow-forward"></i></a></span>

    <span class="mr-2"><a href="#">{{ __lang('course-details') }}</a></span>
@endsection
@section('content')

    <section class="ftco-section">
        <div class="container px-4">
           <div class="row">
               <div class="col-md-4 mb-2">
                   @if(!empty($row->picture))
                       <img class="rounded img-fluid img-thumbnail" src="{{  resizeImage($row->picture,400,300,url('/')) }}" >
                   @else
                       <img class="rounded img-fluid img-thumbnail"  src="{{ asset('img/course.png') }}" >
                   @endif
               </div>
               <div class="col-md-8">

                   {{--MARUF START--}}
                   <h3>{{ $course_name }}</h3>
                   {{--MARUF END--}}
                   <p>
                       {!! clean($row->short_description) !!}
                   </p>


                   @if(!empty($row->enrollment_closes))
                       <div class="csi-countdown-area">
                           <div class="csi-countdown-area-inner">
                               <!-- Date Format :"Y/m/d" || For Example: 1017/10/5  -->
                               <div id=""
                                    class="csi-countdown csi-countdown-{{ $row->id }}"
                                    data-date="{{ showDateWithoutTimezone('Y/m/d',$row->enrollment_closes) }}"></div>
                           </div>
                       </div>
                       <br>
                   @endif
                   <div style="clear: both"></div>


                   @guest
                       <!--<a class="btn btn-primary btn-lg"-->
                       <!--   href="{{  route('cart.add.guest',['course'=>$course->id])  }}">-->
                       <!--    <i class="fa fa-cart-plus"></i>-->
                       <!--    {{  __lang('register')  }}-->
                       <!--</a>-->
                       
                         <a class="btn btn-primary btn-lg" href="{{  route('cart.add',['course'=>$course->id])  }}"><i class="fa fa-cart-plus"></i> {{  __lang('enroll')  }} Now</a>
                   @else
                       <a class="btn btn-primary btn-lg"
                          href="{{  route('cart.add',['course'=>$course->id])  }}?payment=later">
                           <i class="fa fa-cart-plus"></i>
                           {{  __lang('register-and-pay-later')  }}
                       </a>

                       <a class="btn btn-primary btn-lg"
                      href="{{  route('cart.add',['course'=>$course->id])  }}">
                       <i class="fa fa-cart-plus"></i>
                       {{  __lang('enroll')  }} @if(setting('general_show_fee')==1) (@if(empty($row->payment_required)){{  __lang('free')  }}@else{{ price($row->fee) }}@endif @endif)
                   </a>

                   @endif
                   <a class="btn btn-primary btn-lg interested-in-future"
                     href="{{  route('cart.add.guest',['course'=>$course->id])  }}">
                       <i class="fa fa-inbox"></i> {{  __lang('interested_in_future')  }}
                   </a>
               </div>
           </div>


            <div class="row mt-5">
                <div class="col-md-8">
                    <ul class="nav nav-pills mb-2" id="myTab3" role="tablist">
                        @if($row->course_objective != '')
                            <li class="nav-item">
                                <a class="nav-link active"
                                   id="home-tab4"
                                   data-toggle="tab"
                                   href="#home4"
                                   role="tab"
                                   aria-controls="home"
                                   aria-selected="true">
                                    <i class="fa fa-info-circle"></i> Objective
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link "
                               id="home-tab3"
                               data-toggle="tab"
                               href="#home3"
                               role="tab"
                               aria-controls="home"
                               aria-selected="true">
                                <i class="fa fa-info-circle"></i>
                                Important Information{{-- {{  __lang('details')  }}--}}
                            </a>
                        </li>
                        @if($row->module_description != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab5"
                                   data-toggle="tab"
                                   href="#contact5"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fa fa-table"></i> {{  __lang('module_description')  }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item" style="display: none">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-table"></i> {{  __lang('classes')  }}</a>
                        </li>
                        @if($row->outcomes != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab4"
                                   data-toggle="tab"
                                   href="#contact4"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false" style="display: none">
                                    <i class="fa fa-table"></i> {{  __lang('outcomes')  }}
                                </a>
                            </li>
                        @endif
                        @if($row->accreditation != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab6"
                                   data-toggle="tab"
                                   href="#contact6"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fa fa-table"></i> International Recognition
                                </a>
                            </li>
                        @endif
                        @if($row->course_details != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab-course_details"
                                   data-toggle="tab"
                                   href="#contact_course_details"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fa fa-table"></i> Details
                                </a>
                            </li>
                        @endif
                        @if($row->student_feedback != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab7"
                                   data-toggle="tab"
                                   href="#contact7"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fa fa-table"></i> Feedback
                                </a>
                            </li>
                        @endif
                        @if($row->course_faq != '')
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab8"
                                   data-toggle="tab"
                                   href="#contact8"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fa fa-table"></i> FAQ
                                </a>
                            </li>
                        @endif

                        @if($instructors->count() > 0)
                        <li class="nav-item">
                            <a class="nav-link"
                               id="contact-tab3"
                               data-toggle="tab"
                               href="#contact3"
                               role="tab"
                               aria-controls="contact"
                               aria-selected="false">
                                <i class="fa fa-chalkboard-teacher"></i> {{  __lang('instructors')  }}
                            </a>
                        </li>
                        @endif

                    </ul>
                    <div class="tab-content" id="myTabContent2">

                        @if($row->course_objective != '')
                            <div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <img class="rounded img-fluid img-thumbnail"
                                             src="{{ $logo }}"
                                             width="180"
                                             onerror="this.style.display='none'">

                                        {!! $row->course_objective !!}
                                    </div>
                                </div>

                            </div>
                        @endif
                        <div class="tab-pane fade" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <div class="card">
                                <div class="card-body">
                                    {!! $row->description !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">

                            @php  $sessionVenue= $row->venue;  @endphp

                            @foreach($rowset as $row2)

                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-7"><h4>{{  $row2->name }}</h4></div>
                                            <div class="col-md-5">
                                                @if(!empty($row2->lesson_date))
                                                    <div class="card-header-action text-right">
                                                        {{  __lang('starts')  }} {{  showDate('d/M/Y',$row2->lesson_date) }}
                                                    </div>

                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @php  if(!empty($row2->picture)):  @endphp
                                            <div class="col-md-3">
                                                <a href="#" >
                                                    <img class="img-fluid  rounded" src="{{  resizeImage($row2->picture,300,300,url('/')) }}" >
                                                </a>
                                            </div>
                                            @php  endif;  @endphp

                                            <div class="col-md-{{  (empty($row2->picture)? '12':'9')  }}">
                                                <article class="readmore" >{!! $row2->description !!}  </article>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            @endforeach


                        </div>
                        @if($row->outcomes != '')
                            <div class="tab-pane fade"
                                 id="contact4"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab4">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->outcomes !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($row->module_description != '')
                            <div class="tab-pane fade"
                                 id="contact5"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab5">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->module_description !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($row->accreditation != '')
                            <div class="tab-pane fade"
                                 id="contact6"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab6">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->accreditation !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($row->student_feedback != '')
                            <div class="tab-pane fade"
                                 id="contact7"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab7">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->student_feedback !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($row->course_details != '')
                            <div class="tab-pane fade"
                                 id="contact_course_details"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab-course_details">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->course_details !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($row->course_faq != '')
                            <div class="tab-pane fade"
                                 id="contact8"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab8">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $row->course_faq !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($instructors->count() > 0)
                            <div class="tab-pane fade"
                                 id="contact3"
                                 role="tabpanel"
                                 aria-labelledby="contact-tab3">
                                @foreach($instructors as $instructor)
                                    <div class="card author-box card-primary">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="author-box-left">
                                                        <img alt="image" src="{{ profilePictureUrl($instructor->user_picture) }}" class="rounded-circle img-fluid author-box-picture">

                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="author-box-details">
                                                        <div class="author-box-name">
                                                            <a href="#">{{  $instructor->name.' '.$instructor->last_name  }}</a>
                                                        </div>
                                                        <div class="author-box-job">{{ \App\Admin::find($instructor->admin_id)->adminRole->name }}</div>
                                                        <div class="author-box-description">
                                                            <p>{!! clean($instructor->about) !!}</p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <table id="course-specs" class="table table-striped">
                        @php  if(!empty($row->session_date)): @endphp
                        <tr>
                            <td >{{  __lang('starts')  }}</td>
                            <td  >{{  showDate('d/M/Y',$row->session_date) }}</td>
                        </tr>
                        @php  endif;  @endphp

                        @php  if(!empty($row->session_end_date)): @endphp
                        <tr>
                            <td >{{  __lang('ends')  }}</td>
                            <td>{{  showDate('d/M/Y',$row->session_end_date) }}</td>
                        </tr>
                        @php  endif;  @endphp
                        @php  if(!empty($row->enrollment_closes)): @endphp
                        <tr>
                            <td >{{  __lang('enrollment-closes')  }}</td>
                            <td>{{  showDate('d/M/Y',$row->enrollment_closes) }}</td>
                        </tr>
                        @php  endif;  @endphp

                        @php  if(!empty($row->length)): @endphp
                        <tr>

                            <td>{{  __lang('length')  }}</td>
                            <td>{{  $row->length }}</td>
                        </tr>
                        @php  endif;  @endphp


                        @php  if(!empty($row->effort)): @endphp
                        <tr>

                            <td>{{  __lang('effort')  }}</td>
                            <td>{{  $row->effort }}</td>
                        </tr>
                        @php  endif;  @endphp
                        @php  if(!empty($row->enable_chat)): @endphp
                        <tr>

                            <td>{{  __lang('live-chat')  }}</td>
                            <td>{{  __lang('enabled')  }}</td>
                        </tr>
                        @php  endif;  @endphp
                        @php  if(setting('general_show_fee')==1): @endphp
                        <tr>
                            <td>{{  __lang('fee')  }}</td>
                            <td>@php  if(empty($row->payment_required)): @endphp
                                {{  __lang('free')  }}
                                @php  else:  @endphp
                                {{  price($row->fee) }}
                                @php  endif;  @endphp</td>
                        </tr>
                        @php  endif;  @endphp





                    </table>

                  


                </div>

            </div>



        </div>

    </section>




@endsection


@section('footer')
    @if($row->enrollment_closes != '')
        <script type="text/javascript">
            var dataTime = $('.csi-countdown-{{ $row->id }}').data('date');
            $('.csi-countdown-{{ $row->id }}').countdown(dataTime, function (event) {
                var $this = $(this).html(event.strftime(''
                    + '<span class="csi-days">%D <i> Days </i></span> '
                    + '<span class="csi-hr">%H <i> Hour </i></span> '
                    + '<span class="csi-min">%M <i> Min </i></span> '
                    + '<span class="csi-sec">%S <i> Sec </i></span>'
                ));
            });
        </script>
    @endif
@endsection