@extends('layouts.student')
@section('pageTitle',$row->name)
@section('innerTitle',$row->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.student.mysessions')=>__lang('my-courses'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')

<div class="row mb-4">
    <div class="col-md-4  mb-2">
        @if(!empty($row->picture))
            <img class="rounded img-responsive" src="{{  resizeImage($row->picture,400,300,url('/')) }}" >
        @else
            <img class="rounded img-responsive"  src="{{ asset('img/course.png') }}" >
        @endif

    </div>
    <div class="col-md-8">
        <div class="card course-info profile-widget mt-0">
            <div class="profile-widget-header">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">{{ __lang('cost') }}</div>
                        <div class="profile-widget-item-value">
                            @if(empty($row->payment_required))
                                {{  __lang('free')  }}
                            @else
                                {{ price($row->fee) }}
                            @endif
                        </div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">{{ __lang('classes') }}</div>
                        <div class="profile-widget-item-value">{{ $totalClasses }}</div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">{{ __lang('type') }}</div>
                        <div class="profile-widget-item-value">
                            @php
                                switch($row->type){
                                    case 'b':
                                        echo __lang('training-online');
                                        break;
                                    case 's':
                                        echo __lang('training-session');
                                        break;
                                    case 'c':
                                        echo __lang('online-course');
                                        break;
                                }
                            @endphp
                        </div>
                    </div>
                    @if($studentCourse)
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">{{ __lang('enrollment-code') }}</div>
                            <div class="profile-widget-item-value">{{ $studentCourse->reg_code }}</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="profile-widget-description"> {!! clean($row->short_description) !!}
            </div>
            <div class="card-footer text-center">


                    <a class="btn btn-primary mb-2  btn-lg" href="{{  $resumeLink  }}"><i class="fa fa-play-circle"></i> {{  __lang('Resume Course')  }}</a> &nbsp;&nbsp; {{ __lang('or') }}  &nbsp;&nbsp;
                    <a   href="{{ route('student.course.intro', ['id'=>$id]) }}">{{  __lang('go-to-intro')  }}</a>


            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-pills" id="myTab3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-info-circle"></i> {{  __lang('details')  }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-table"></i> {{  __lang('classes')  }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-chalkboard-teacher"></i> {{  __lang('instructors')  }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="test-tab3" data-toggle="tab" href="#test3" role="tab" aria-controls="test" aria-selected="false"><i class="fa fa-check"></i> {{  __lang('tests')  }}</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent2">
            <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                <div class="card">
                <div class="card-body">
                    Organized by:
                    <img class="rounded img-fluid img-thumbnail"
                         src="{{ $logo }}"
                         width="180"
                         onerror="this.style.display='none'">
                    <br>
                    {!! $row->description !!}
                </div>
                </div>

            </div>
            <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">

                @php  $sessionVenue= $row->venue;  @endphp

                @foreach($rowset as $row2)

                <div class="card">
                    <div class="card-header"><h4>{{  $row2->name }}</h4>
                        @if(!empty($row2->lesson_date))
                            <div class="card-header-action">
                                {{  __lang('starts')  }} {{  showDate('d/M/Y',$row2->lesson_date) }}
                            </div>

                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php  if(!empty($row2->picture)):  @endphp
                            <div class="col-md-3">
                                <a href="#" >
                                    <img class="img-responsive rounded" src="{{  resizeImage($row2->picture,300,300,url('/')) }}" >
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
                            <div class="author-box-left">
                                <img alt="image" src="{{ profilePictureUrl($instructor->user_picture) }}" class="rounded-circle author-box-picture">
                                <div class="clearfix"></div>
                                <a href="#" class="btn btn-primary mt-3"  data-toggle="modal" data-target="#contactModal{{  $instructor->admin_id  }}" ><i class="fa fa-envelope"></i> {{  __lang('contact')  }}</a>
                           @section('footer')
                               @parent
                               <!-- Modal -->
                                   <div class="modal fade" id="contactModal{{  $instructor->admin_id  }}" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel{{  $instructor->admin_id  }}">
                                       <div class="modal-dialog" role="document">
                                           <div class="modal-content">
                                               <form class="form" method="post" action="{{  route('student.student.adddiscussion') }}">
                                                   @csrf
                                                   <div class="modal-header">
                                                       <h4 class="modal-title" id="contactModalLabel">{{  __lang('contact')  }} {{  $instructor->name.' '.$instructor->last_name  }}</h4>

                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                   </div>
                                                   <div class="modal-body">




                                                       <input type="hidden" name="account_id[][]" value="{{  $instructor->admin_id  }}"/>

                                                       <div class="form-group">
                                                           {{  formLabel($form->get('subject')) }}
                                                           {{  formElement($form->get('subject')) }}   <p class="help-block">{{  formElementErrors($form->get('subject')) }}</p>

                                                       </div>




                                                       <div class="form-group">
                                                           {{  formLabel($form->get('question')) }}
                                                           {{  formElement($form->get('question')) }}   <p class="help-block">{{  formElementErrors($form->get('question')) }}</p>

                                                       </div>




                                                   </div>
                                                   <div class="modal-footer">
                                                       <button type="button" class="btn btn-default" data-dismiss="modal">{{  __lang('close')  }}</button>
                                                       <button type="submit" class="btn btn-primary">{{  __lang('send-message')  }}</button>
                                                   </div>
                                               </form>
                                           </div>
                                       </div>
                                   </div>

                               @endsection
                            </div>
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
                @endforeach
            </div>
            <div class="tab-pane fade" id="test3" role="tabpanel" aria-labelledby="test-tab3">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>

                            <th style="min-width: 100px">{{  __lang('test')  }}</th>
                            <th>{{ __lang('shomvabona-type') }}</th>
                            <th>{{  __lang('questions')  }}</th>
                            <th>{{  __lang('opens')  }}</th>
                            <th>{{  __lang('closes')  }}</th>
                            <th>{{  __lang('minutes-allowed')  }}</th>
                            <th>{{  __lang('multiple-attempts-allowed')  }}</th>
                            <th>{{  __lang('passmark')  }}</th>
                            <th >{{  __lang('actions')  }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php  foreach($tests as $testRow):  @endphp
                        @php  if($testRow->test_status==1): @endphp
                        <tr>
                            <td>{{  $testRow->name }}</td>
                            <td>{{ ($testRow->exam_type == 1 ? __lang('shomvabona-written') : __lang('shomvabona-mcq')) }}</td>
                            <td>{{  $questionTable->getTotalQuestions($testRow->test_id) }}</td>
                            <td>@php  if(!empty($testRow->opening_date)) echo showDate('d/M/Y',$testRow->opening_date);  @endphp</td>
                            <td>@php  if(!empty($testRow->closing_date))  echo showDate('d/M/Y',$testRow->closing_date);  @endphp</td>

                            <td>{{  empty($testRow->minutes)?__lang('unlimited'):$testRow->minutes }}</td>
                            <td>{{  boolToString($testRow->allow_multiple) }}</td>
                            <td>{{  ($testRow->passmark > 0)? $testRow->passmark.'%':__lang('ungraded') }}</td>

                            <td>

                                @php  if((!$studentTest->hasTest($testRow->test_id,$studentId) || !empty($testRow->allow_multiple)) &&
                                         (strtotime($testRow->opening_date) <= time() || $testRow->opening_date == 0 ) &&
                                         (strtotime($testRow->closing_date) >= time() || $testRow->closing_date ==0)):  @endphp
                                <a  target="_blank" href="{{  route('student.test.taketest',array('id'=>$testRow->test_id)) }}" class="btn btn-primary " >{{  __lang('take-test')  }}</a>
                                @php  endif;  @endphp
                            </td>

                        </tr>
                        @php  endif;  @endphp
                        @php  endforeach;  @endphp

                        </tbody>
                    </table>
                </div>

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

        <a class="btn btn-primary btn-block btn-lg" href="{{  $resumeLink  }}"><i class="fa fa-play-circle"></i> {{  __lang('resume-course')  }}</a>


    </div>

</div>





@endsection


@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/readmore/readmore.min.js') }}"></script>
    <script>
        $(function(){
            $('article.readmore').readmore({
                collapsedHeight : 90
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            console.log('clicked');
            $('#timetable article.readmore').readmore({
                collapsedHeight : 90
            });
        });
    </script>
@endsection

@section('header')
    <style>
        #course-specs tr:first-child > td{
            border-top: none
        }
    </style>
@endsection
