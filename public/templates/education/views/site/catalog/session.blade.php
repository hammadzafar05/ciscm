@extends(TLAYOUT)

@section('page-title',$course->name)
@section('inline-title',$course->name)
@section('crumb')
    <span><a href="@route('sessions')">{{ __lang('upcoming-sessions') }}</a></span>
    <span>/</span>
    <span><a href="#">{{ __lang('session-details') }}</a></span>
@endsection
@section('content')

    <section class="about-area them-2 pb-130 pt-50 recent-area">
        <div class="container">

            <div class="row">
                <div class="col-md-4 mb-2">
                    @if(!empty($row->picture))
                        <img class="rounded img-fluid img-thumbnail" src="{{  resizeImage($row->picture,400,300,url('/')) }}" >
                    @else
                        <img class="rounded img-fluid img-thumbnail"  src="{{ asset('img/course.png') }}" >
                    @endif
                </div>
                <div class="col-md-8">

                    <h3>{{ $course->name }}</h3>
                    <p>
                        {!! clean($row->short_description) !!}
                    </p>

                    <a class="btn btn-primary  btn-lg" href="{{  route('cart.add',['course'=>$course->id])  }}"><i class="fa fa-cart-plus"></i> {{  __lang('enroll')  }} (@if(empty($row->payment_required)){{  __lang('free')  }}@else{{ price($row->fee) }}@endif)</a>
                </div>

            </div>


            <div class="row mt-5">
                <div class="col-md-8">
                    <ul class="nav nav-pills mb-2" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-info-circle"></i> {{  __lang('details')  }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-table"></i> {{  __lang('classes')  }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-chalkboard-teacher"></i> {{  __lang('instructors')  }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
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
                        <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
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

                    <a class="btn btn-primary btn-block btn-lg" href="{{  route('cart.add',['course'=>$course->id])  }}"><i class="fa fa-cart-plus"></i> {{  __lang('enroll')  }}</a>


                </div>

            </div>




        </div>

    </section>




@endsection
