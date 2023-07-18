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

                    @if($row->type=='b')

                        <a class="btn btn-primary mb-2  btn-lg " href="{{  $resumeLink  }}"><i class="fa fa-play-circle"></i> {{  $resumeText }}  {{  __lang('Online Classes')  }}</a> &nbsp;&nbsp;

                    @endif
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
                    <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-table"></i> {{  __lang('Time Table')  }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-chalkboard-teacher"></i> {{  __lang('instructors')  }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="download-tab3" data-toggle="tab" href="#download3" role="tab" aria-controls="download" aria-selected="false"><i class="fa fa-download"></i> {{  __lang('resources')  }}</a>
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

                            {!! $row->description !!}
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">

                    @php  $sessionVenue= $row->venue;  @endphp
                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>{{  __lang('Class')  }}</th>
                                <th>{{  __lang('Date')  }}</th>
                                <th>{{  __lang('Starts')  }}</th>
                                <th>{{  __lang('Ends')  }}</th>
                                <th>{{  __lang('Instructors')  }}</th>
                                <th>{{  __lang('Venue')  }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php  foreach($rowset as $row2):  @endphp
                            <tr>
                                <td><a data-toggle="modal" data-target="#classModal{{  $row2->id  }}" href="#" >{{  $row2->name }}</a>
                                    @section('footer')
                                        @parent
                                    <!-- Modal -->
                                    <div class="modal fade" id="classModal{{  $row2->id  }}" tabindex="-1" role="dialog" aria-labelledby="classModal{{  $row2->id  }}Label">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="classModal{{  $row2->id  }}Label">{{  $row2->name  }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                                                </div>
                                                <div class="modal-body">

                                                    <div class="row">
                                                        @php  if(!empty($row2->picture)):  @endphp
                                                        <div class="col-md-3">
                                                            <a href="#" >
                                                                <img class="img-responsive" src="{{  resizeImage($row2->picture,300,300,url('/')) }}" >
                                                            </a>
                                                        </div>
                                                        @php  endif;  @endphp

                                                        <div class="col-md-{{  (empty($row2->picture)? '12':'9')  }}">
                                                            <article >{!! $row2->description !!}  </article>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{  __lang('Close')  }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        @endsection
                                </td>
                                <td>{{  showDate('d/m/Y',$row2->lesson_date) }}</td>
                                <td>{{  (!empty($row2->lesson_start)? $row2->lesson_start : '') }}</td>
                                <td>{{  (!empty(@$row2->lesson_end)? @$row2->lesson_end : '') }}</td>
                                <th>
                                    <div class="btn-group dropup btn-group-xs">
                                        <button type="button" class="btn btn-inverse dropdown-toggle btn-xs" data-toggle="dropdown">
                                            @php  $total = $table->getTotalInstructors($row2->lesson_id,$id); echo empty($total)?'N/A':$total;  @endphp @php  if(!empty($total)): @endphp<i class="fa fa-caret-up"></i>@php  endif;  @endphp
                                        </button>
                                        @php  if(!empty($total)): @endphp
                                        <ul class="dropdown-menu float-right animation-slide" role="menu" style="text-align: left;">
                                            @php  foreach($table->getInstructors($row2->lesson_id,$id) as $row22): @endphp
                                            <li><a href="#">{{  $row22->name }} {{  $row22->last_name }}</a></li>
                                            @php  endforeach;  @endphp

                                        </ul>
                                        @php  endif;  @endphp
                                    </div>
                                </th>
                                <td>
                                    @php  if($row2->type=='c'):  @endphp
                                    {{  __lang('Online')  }}
                                    @php  if( (empty($row2->lesson_date) || stamp($row2->lesson_date) < time()) && $studentSessionTable->enrolled($studentId,$id) ): @endphp
                                    <a class="btn btn-primary float-right" href="{{  route('student.course.class',['course'=>$row2->course_id,'lesson'=>$row2->lesson_id])  }}"><i class="fa fa-play"></i> {{  __lang('Start Class')  }}</a>
                                    @php  endif;  @endphp
                                    @php  else:  @endphp
                                    {{  empty($row2->lesson_venue)? $sessionVenue: $row2->lesson_venue }}
                                    @php  endif;  @endphp

                                </td>

                            </tr>
                            @php  endforeach;  @endphp
                            </tbody>


                        </table>
                    </div>

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

                <div class="tab-pane fade" id="download3" role="tabpanel" aria-labelledby="download-tab3">

                    @php  if($studentSessionTable->enrolled($studentId,$id)):  @endphp
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{  __lang('id')  }}</th>
                            <th>{{  __lang('Name')  }}</th>
                            <th>{{  __lang('Files')  }}</th>
                            <th ></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php  foreach($downloads as $download):  @endphp
                        <td><span class="label label-success">{{  $download->download_id  }}</span></td>
                        <td>{{  $download->name }}</td>
                        <td>{{  $fileTable->getTotalForDownload($download->download_id) }}</td>

                        <td class="text-right">
                            @php  if ($fileTable->getTotalForDownload($download->download_id)> 0):  @endphp
                            <a href="{{  route('student.download.files',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('View files')  }}"><i class="fa fa-eye"></i> {{  __lang('View files')  }}</a>
                            <a href="{{  route('student.download.allfiles',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('Download All')  }}"><i class="fa fa-download"></i> {{  __lang('Download All')  }}</a>
                            @php  else: @endphp
                            <strong>{{  __lang('no-files-available')  }}</strong>
                            @php  endif;  @endphp
                        </td>
                        </tr>

                        @php  endforeach;  @endphp

                        </tbody>
                    </table>
                    @php  else:  @endphp
                    <p>{{  __lang('resource-warning-text')  }}</p>
                    @php  endif;  @endphp

                </div>


                <div class="tab-pane fade" id="test3" role="tabpanel" aria-labelledby="test-tab3">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>

                                <th style="min-width: 100px">{{  __lang('test')  }}</th>
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
                                <td>{{  $questionTable->getTotalQuestions($testRow->test_id) }}</td>
                                <td>@php  if(!empty($testRow->opening_date)) echo showDate('d/M/Y',$testRow->opening_date);  @endphp</td>
                                <td>@php  if(!empty($testRow->closing_date))  echo showDate('d/M/Y',$testRow->closing_date);  @endphp</td>

                                <td>{{  empty($testRow->minutes)?__lang('unlimited'):$testRow->minutes }}</td>
                                <td>{{  boolToString($testRow->allow_multiple) }}</td>
                                <td>{{  ($testRow->passmark > 0)? $testRow->passmark.'%':__lang('ungraded') }}</td>

                                <td>
                                    @php  if( (!$studentTest->hasTest($testRow->test_id,$studentId) || !empty($testRow->allow_multiple)) && ($testRow->opening_date < time() || $testRow->opening_date == 0 ) && ($testRow->closing_date > time() || $testRow->closing_date ==0)):  @endphp
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

            @if($row->type=='b')
            <div class="text-center">
                <a class="btn btn-primary mb-2 btn-block  btn-lg" href="{{  $resumeLink  }}"><i class="fa fa-play-circle"></i> {{  $resumeText }}  {{  __lang('Online Classes')  }}</a> &nbsp;&nbsp;
        </div>
            @endif


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



@if(false)
@section('content')

<div class="container" style="min-height: 100px;   padding-bottom:50px; margin-bottom: 10px;   " >

<div class="row" style="border-bottom: solid 1px #CCCCCC; min-height: 100px; margin-bottom: 20px ">

    <div class="col-md-8">
        <div class="row">

    @php  if(!empty($row->picture)): @endphp
    <div class="col-md-5">
        <a href="#" class="thumbnail">
            <img src="{{  resizeImage($row->picture,400,300,url('/')) }}" >
        </a>
    </div>
    @php  endif;  @endphp
            <div class="col-md-7">
                <h3>{{  $row->name  }}</h3>
                <p>
                    <article class="readmore">
                    {{  $row->short_description }}
                    </article>
                </p>
            </div>


        </div>

    </div>
    @php  if(!$studentSessionTable->enrolled($studentId,$id) && $row->enrollment_closes > time()):  @endphp
    <div class="col-md-3 col-md-offset-1" style="margin-bottom: 20px">
        <br/><br/>
        <a class="btn btn-primary btn-block btn-lg" href="{{  $this->url('set-session',array('id'=>$row->session_id)) }}">{{  __lang('Enroll Now')  }}</a>
    </div>
    @php  elseif($row->session_type=='b'):  @endphp
        <div class="col-md-3 col-md-offset-1" style="margin-bottom: 20px">
            <br/><br/>
            <a class="btn btn-primary btn-block btn-lg" href="{{  $resumeLink  }}">{{  $resumeText }} {{  __lang('Online Classes')  }}</a>

        </div>

    @php  endif;  @endphp




</div>
    <div class="row">
        <div class="col-md-8">
            <div class="tabbable tabs-primary">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab"> <i class="fa fa-info"></i> {{  __lang('Details')  }}</a></li>
                    <li role="presentation"><a href="#timetable" aria-controls="timetable" role="tab" data-toggle="tab"><i class="fa fa-table"></i> {{  __lang('Time Table')  }}</a></li>
                    <li role="presentation"><a href="#instructors" aria-controls="instructors" role="tab" data-toggle="tab"><i class="fa fa-users"></i> {{  __lang('Instructors')  }}</a></li>
                    <li role="presentation"><a href="#resources" aria-controls="instructors" role="tab" data-toggle="tab"><i class="fa fa-download"></i> {{  __lang('Resources')  }}</a></li>
                    @php  if($enrolled):  @endphp
                    <li role="presentation"><a href="#tests" aria-controls="tests" role="tab" data-toggle="tab"><i class="fa fa-check"></i> {{  __lang('Tests')  }}</a></li>
                    @php  endif;  @endphp
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="details">
                        <p>{{  $row->description  }}</p>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="timetable">

                        @php  $sessionVenue= $row->venue;  @endphp
                        <div class="table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                <tr>
                                    <th>{{  __lang('Class')  }}</th>
                                    <th>{{  __lang('Date')  }}</th>
                                    <th>{{  __lang('Starts')  }}</th>
                                    <th>{{  __lang('Ends')  }}</th>
                                    <th>{{  __lang('Instructors')  }}</th>
                                    <th>{{  __lang('Venue')  }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php  foreach($rowset as $row2):  @endphp
                                    <tr>
                                        <td><a data-toggle="modal" data-target="#classModal{{  $row2->id  }}" href="#" style="text-decoration: underline">{{  $row2->name }}</a>
                                             <!-- Modal -->
                                            <div class="modal fade" id="classModal{{  $row2->id  }}" tabindex="-1" role="dialog" aria-labelledby="classModal{{  $row2->id  }}Label">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="classModal{{  $row2->id  }}Label">{{  $row2->name  }}</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                @php  if(!empty($row2->picture)):  @endphp
                                                                    <div class="col-md-3">
                                                                        <a href="#" >
                                                                            <img class="img-responsive" src="{{  resizeImage($row2->picture,300,300,url('/')) }}" >
                                                                        </a>
                                                                    </div>
                                                                @php  endif;  @endphp

                                                                <div class="col-md-{{  (empty($row2->picture)? '12':'9')  }}">
                                                                    <article >{{  $row2->content }}</article>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">{{  __lang('Close')  }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                        <td>{{  showDate('d/m/Y',$row2->lesson_date) }}</td>
                                        <td>{{  (!empty($row2->lesson_start)? $row2->lesson_start : '') }}</td>
                                        <td>{{  (!empty(@$row2->lesson_end)? @$row2->lessong_end : '') }}</td>
                                        <th>
                                            <div class="btn-group dropup btn-group-xs">
                                                <button type="button" class="btn btn-inverse dropdown-toggle btn-xs" data-toggle="dropdown">
                                                    @php  $total = $table->getTotalInstructors($row2->lesson_id,$id); echo empty($total)?'N/A':$total;  @endphp @php  if(!empty($total)): @endphp<i class="fa fa-caret-up"></i>@php  endif;  @endphp
                                                </button>
                                                @php  if(!empty($total)): @endphp
                                                    <ul class="dropdown-menu float-right animation-slide" role="menu" style="text-align: left;">
                                                        @php  foreach($table->getInstructors($row2->lesson_id,$id) as $row22): @endphp
                                                            <li><a href="#">{{  $row22->name }} {{  $row22->last_name }}</a></li>
                                                        @php  endforeach;  @endphp

                                                    </ul>
                                                @php  endif;  @endphp
                                            </div>
                                        </th>
                                        <td>
                                            @php  if($row2->type=='c'):  @endphp
                                                {{  __lang('Online')  }}
                                                @php  if( (empty($row2->lesson_date) || $row2->lesson_date < time()) && $studentSessionTable->enrolled($studentId,$id) ): @endphp
                                                <a class="btn btn-primary float-right" href="{{  $this->url('view-class',['sessionId'=>$row->session_id,'classId'=>$row2->lesson_id])  }}"><i class="fa fa-play"></i> {{  __lang('Start Class')  }}</a>
                                                 @php  endif;  @endphp
                                            @php  else:  @endphp
                                            {{  empty($row2->lesson_venue)? $sessionVenue: $row2->lesson_venue }}
                                            @php  endif;  @endphp

                                        </td>

                                    </tr>
                                @php  endforeach;  @endphp
                                </tbody>


                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="instructors">
                        @php  foreach($instructors as $instructor): @endphp
                            <div class="row">
                                @php  if(!empty($instructor->picture)):  @endphp
                                    <div class="col-md-3">
                                        <a href="#" class="thumbnail">
                                            <img src="{{  resizeImage($instructor->picture,300,300,url('/')) }}" >
                                        </a>
                                    </div>
                                @php  endif;  @endphp

                                <div class="col-md-{{  (empty($instructor->picture)? '12':'9')  }}">
                                    <h4>{{  $instructor->name.' '.$instructor->last_name  }}</h4>
                                    <article class="readmore">{{  $instructor->account_description }}</article>
                                    @php  if($studentSessionTable->enrolled($studentId,$id)):  @endphp

                                    <p style="margin-top: 10px">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contactModal{{  $instructor->session_instructor_id  }}">
                                            <i class="fa fa-envelope"></i> {{  __lang('Contact')  }}
                                        </button>
                                    </p>
                                    @php  endif;  @endphp

                                    <!-- Modal -->
                                    <div class="modal fade" id="contactModal{{  $instructor->session_instructor_id  }}" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel{{  $instructor->session_instructor_id  }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form class="form" method="post" action="{{  $this->url('application/default',['controller'=>'student','action'=>'adddiscussion']) }}">

                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="contactModalLabel">{{  __lang('Contact')  }} {{  $instructor->name.' '.$instructor->last_name  }}</h4>
                                                </div>
                                                <div class="modal-body">

                                                        {{  formElement($form->get('security')) }}


                                                        <input type="hidden" name="account_id[][]" value="{{  $instructor->account_id  }}"/>

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
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{  __lang('Close')  }}</button>
                                                    <button type="submit" class="btn btn-primary">{{  __lang('Send Message')  }}</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @php  endforeach;  @endphp
                    </div>

                    <div role="tabpanel" class="tab-pane" id="resources">
                        @php  if($studentSessionTable->enrolled($studentId,$id)):  @endphp
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{  __lang('id')  }}</th>
                                <th>{{  __lang('Name')  }}</th>
                                <th>{{  __lang('Files')  }}</th>
                                <th ></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php  foreach($downloads as $download):  @endphp
                 <td><span class="label label-success">{{  $download->download_id  }}</span></td>
                        <td>{{  $download->download_name }}</td>
                        <td>{{  $fileTable->getTotalForDownload($download->download_id) }}</td>

                        <td class="text-right">
                        @php  if ($fileTable->getTotalForDownload($download->download_id)> 0):  @endphp
                                <a href="{{  $this->url('application/download-list',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View Files"><i class="fa fa-eye"></i> {{  __lang('View files')  }}</a>
                                <a href="{{  $this->url('application/download-all',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Download All Files"><i class="fa fa-download"></i> {{  __lang('Download All')  }}</a>
                            @php  else: @endphp
                                <strong>{{  __lang('no-files-available')  }}</strong>
                            @php  endif;  @endphp
                        </td>
                    </tr>

            @php  endforeach;  @endphp

                            </tbody>
                        </table>
                        @php  else:  @endphp
                            <p>{{  __lang('resource-warning-text')  }}</p>
                        @php  endif;  @endphp
                    </div>
                    @php  if($enrolled):  @endphp
                    <div role="tabpanel" class="tab-pane" id="tests">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>

                                    <th>{{  __lang('Test')  }}</th>
                                    <th>{{  __lang('Questions')  }}</th>
                                    <th>{{  __lang('Opens')  }}</th>
                                    <th>{{  __lang('Closes')  }}</th>
                                    <th>{{  __lang('Minutes Allowed')  }}</th>
                                    <th>{{  __lang('multiple-attempts-allowed')  }}</th>
                                    <th>{{  __lang('passmark')  }}</th>
                                    <th class="text-right1" style="width:90px">{{  __lang('actions')  }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php  foreach($tests as $testRow):  @endphp
                                    @php  if($testRow->test_status==1): @endphp
                                    <tr>
                                        <td>{{  $testRow->name }}</td>
                                        <td>{{  $questionTable->getTotalQuestions($testRow->test_id) }}</td>
                                        <td>@php  if(!empty($testRow->opening_date)) echo showDate('d/M/Y',$testRow->opening_date);  @endphp</td>
                                        <td>@php  if(!empty($testRow->closing_date))  echo showDate('d/M/Y',$testRow->closing_date);  @endphp</td>

                                        <td>{{  empty($testRow->minutes)?__lang('Unlimited'):$testRow->minutes }}</td>
                                        <td>{{  boolToString($testRow->allow_multiple) }}</td>
                                        <td>{{  ($testRow->passmark > 0)? $testRow->passmark.'%':__lang('Ungraded') }}</td>

                                        <td class="text-right">
                                            @php  if( (!$studentTest->hasTest($testRow->test_id,$this->getStudent()->student_id) || !empty($testRow->allow_multiple)) && ($testRow->opening_date < time() || $testRow->opening_date == 0 ) && ($testRow->closing_date > time() || $testRow->closing_date ==0)):  @endphp
                                                <a target="_blank" href="{{  $this->url('application/taketest',array('id'=>$testRow->test_id)) }}" class="btn btn-primary " >{{  __lang('Take Test')  }}</a>
                                            @php  endif;  @endphp
                                        </td>

                                    </tr>
                                    @php  endif;  @endphp
                                @php  endforeach;  @endphp

                                </tbody>
                            </table>
                            </div>
                    </div>
                    @php  endif;  @endphp
                </div>

            </div>
        </div>
        <div class="col-md-4" style="border: solid 1px #CCCCCC; padding: 10px; font-size: 14px; ">
            <table class="table table-striped">
                <tr>
                    <td style="border-top: none">{{  __lang('Start Date')  }}</td>
                    <td  style="border-top: none">{{  showDate('d/M/Y',$row->session_date) }}</td>
                </tr>
                <tr>
                    <td >{{  __lang('Enrollment Closes')  }}</td>
                    <td>{{  showDate('d/M/Y',$row->enrollment_closes) }}</td>
                </tr>
                <tr>
                    <td >{{  __lang('End Date')  }}</td>
                    <td>{{  showDate('d/M/Y',$row->session_end_date) }}</td>
                </tr>
                @php  if(setting('general_show_fee')==1): @endphp
                <tr>
                    <td>{{  __lang('Fee')  }}</td>
                    <td>@php  if(empty($row->payment_required)): @endphp
                            {{  __lang('Free')  }}
                        @php  else:  @endphp
                            {{  price($row->fee) }}
                        @php  endif;  @endphp</td>
                </tr>
                @php  endif;  @endphp

                @php  if(!empty($row->venue)): @endphp
                <tr>
                    @php  $sessionVenue= $row->venue;  @endphp
                    <td>{{  __lang('Venue')  }}</td>
                    <td>{{  $sessionVenue }}</td>
                </tr>
                @php  endif;  @endphp


            </table>
            @php  if(!$studentSessionTable->enrolled($studentId,$id) && $row->enrollment_closes > time()):  @endphp
            <a class="btn btn-primary btn-block btn-lg" href="{{  $this->url('set-session',array('id'=>$row->session_id)) }}">{{  __lang('Enroll Now')  }}</a>
            @php  endif;  @endphp
        </div>

    </div>



</div>

@php  $this->headScript()->prependFile(url('/') . '/client/vendor/readmore/readmore.min.js')
 @endphp
<script>
    $(function(){
        $('article.readmore').readmore({
            collapsedHeight : 90
        });
    });

</script>
@endsection
@endif
