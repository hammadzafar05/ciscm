@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$sessionRow->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')

    <div class="card profile-widget mt-5">
        <div class="profile-widget-header">
            @if(!empty($sessionRow->picture))
                <img  class="rounded-circle profile-widget-picture" src="{{  resizeImage($sessionRow->picture,400,300,url('/')) }}" >
            @else
                <img  class="rounded-circle profile-widget-picture"  src="{{ asset('img/course.png') }}" >
            @endif
            <div class="profile-widget-items">
                <div class="profile-widget-item">
                    <div class="profile-widget-item-value pt-2 pb-1">{{ __lang('introduction') }}</div>
                </div>
                <div class="profile-widget-item">
                    <a class="btn btn-success btn-lg " href="{{  $classLink  }}"><i class="fa fa-play-circle"></i> {{  __lang('start-course')  }}</a>
                </div>
            </div>
        </div>
        <div class="profile-widget-description">
            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{  __lang('introduction')  }}</a></li>
                    <li  class="nav-item"><a class="nav-link"  href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-table"></i> {{  __lang('table-of-contents')  }}</a></li>
                    <li  class="nav-item"><a class="nav-link"  href="#resources" aria-controls="resources" role="tab" data-toggle="tab"><i class="fa fa-download"></i> {{  __lang('resources')  }}</a></li>
                    <li  class="nav-item"><a class="nav-link"  href="#progress" aria-controls="progress" role="tab" data-toggle="tab"><i class="fa fa-chart-bar"></i> {{  __lang('progress')  }}</a></li>
                    @php  if(!empty($sessionRow->enable_discussion)): @endphp
                    <li  class="nav-item"><a class="nav-link"  href="#discuss" aria-controls="discuss" role="tab" data-toggle="tab"><i class="fa fa-comments"></i> {{  __lang('discuss')  }}</a></li>
                    @php  endif;  @endphp
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">

                        <div  >
                            <div  >
                                <p>{!! $sessionRow->introduction !!}  </p>
                            </div>
                            <a class="btn btn-success btn-lg float-right" href="{{  $classLink  }}"><i class="fa fa-play-circle"></i> {{  __lang('start-course')  }}</a>

                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                        @php  if($totalClasses>0): @endphp
                        @php  $count=1; foreach($classes as $row):  @endphp

                        <div class="card">
                            <div class="card-header">
                                {{  $count.'. '.$row->name  }}
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{  __lang('lectures')  }}</th>
                                        <th>{{  __lang('attendance')  }}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php  foreach($lectureTable->getPaginatedRecords(false,$row->lesson_id) as $lecture):  @endphp
                                    <tr>
                                        <td>{{  $lecture->title  }}</td>
                                        <td>
                                            @php  if($sessionLogTable->hasAttendance($studentId,$sessionId,$lecture->id)): @endphp
                                            {{  __lang('completed-on')  }} {{  showDate('d/M/Y',$sessionLogTable->getAttendance($studentId,$sessionId,$lecture->id)->created_at)  }}
                                            @php  else:  @endphp
                                            {{  __lang('pending')  }}
                                            @php  endif;  @endphp
                                        </td>
                                        <td><a class="btn btn-xs btn-primary" href="{{  route(MODULE.'.course.lecture',['course'=>$sessionId,'lecture'=>$lecture->id])  }}">{{  __lang('view-lecture')  }}</a></td>
                                    </tr>
                                    @php  endforeach;  @endphp
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"  >
                                <a class="btn btn-primary btn-lg float-right" href="{{  route(MODULE.'.course.class',['course'=>$sessionId,'lesson'=>$row->lesson_id])  }}"><i class="fa fa-play-circle"></i> {{  __lang('start-class')  }}</a>
                            </div>
                        </div>
                        @php  $count++;  @endphp
                        @php  endforeach;  @endphp
                        @php  endif;  @endphp
                    </div>
                    <div role="tabpanel" class="tab-pane" id="resources">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{  __lang('name')  }}</th>
                                <th>{{  __lang('files')  }}</th>
                                <th ></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($downloads as $download)
                                <td>{{  $download->name }}</td>
                                <td>{{  $fileTable->getTotalForDownload($download->download_id) }}</td>

                                <td class="text-right">
                                    @if ($fileTable->getTotalForDownload($download->download_id)> 0)
                                        <a href="{{  route('student.download.files',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View Files"><i class="fa fa-eye"></i> {{  __lang('view-files')  }}</a>
                                        <a href="{{  route('student.download.allfiles',array('id'=>$download->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Download All Files"><i class="fa fa-download"></i> {{  __lang('download-all')  }}</a>
                                    @else
                                        <strong>{{  __lang('no-files-available')  }}</strong>
                                    @endif
                                </td>
                                </tr>

                                @php  endforeach;  @endphp

                            </tbody>
                        </table>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="progress">

                        <div class="text-center mt-3"><h2>{{  $percentage  }}%</h2></div>

                            <div class="progress mb-3 mb-3" data-height="25">
                                <div class="progress-bar bg-success" role="progressbar" data-width="{{  $percentage  }}%" aria-valuenow="{{  $percentage  }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        <div class="row mt-5">

                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <div id="accordion">
                                    <div class="accordion">
                                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
                                            <h4>{{  __lang('classes-attended')  }}</h4>
                                        </div>
                                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                            <p>{{  __lang('here-are-classes')  }}</p>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>{{  __lang('class')  }}</th>
                                                    <th>{{  __lang('date')  }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php  foreach($attendanceRecords as $attendance): @endphp
                                                <tr>
                                                    <td><a href="{{  route(MODULE.'.course.class',['lesson'=>$attendance->lesson_id,'course'=>$attendance->course_id])  }}">{{  $attendance->name  }}</a></td>
                                                    <td>{{  showDate('d/M/Y',$attendance->attendance_date)  }}</td>
                                                </tr>

                                                @php  endforeach;  @endphp
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="col-md-6 col-sm-6 col-xs-12">


                                <div id="accordion2">
                                    <div class="accordion">
                                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-12" aria-expanded="true">
                                            <h4>{{  __lang('pending-classes')  }}</h4>
                                        </div>
                                        <div class="accordion-body collapse show" id="panel-body-12" data-parent="#accordion2">
                                            <p>{{  __lang('classes-yet-to-take')  }}</p>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>{{  __lang('class')  }}</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php  if($totalClasses>0): @endphp
                                                @php  foreach($classes as $class): @endphp
                                                @php  if(!$attendanceTable->hasAttendance($studentId,$class->lesson_id,$sessionId)): @endphp
                                                <tr>
                                                    <td>{{  $class->name  }}</td>
                                                    <td>
                                                        @php  if($class->lesson_date > time()): @endphp
                                                        {{  __lang('starts-on')  }} {{  showDate('d/M/Y',$class->lesson_date)  }}
                                                        @php  else:  @endphp
                                                        <a class="btn btn-primary" href="{{  route(MODULE.'.course.class',['lesson'=>$class->lesson_id,'course'=>$sessionId])  }}">{{  __lang('start-class')  }}</a>
                                                        @php  endif;  @endphp
                                                    </td>
                                                </tr>
                                                @php  endif;  @endphp
                                                @php  endforeach;  @endphp
                                                @php  endif;  @endphp
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="discuss">

                        <div>

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills" role="tablist">
                                <li  class="nav-item"><a  class="nav-link active"  href="#home1" aria-controls="home1" role="tab" data-toggle="tab">{{  __lang('instructor-chat')  }}</a></li>
                                <li  class="nav-item"><a  class="nav-link"  href="#profile1" aria-controls="profile1" role="tab" data-toggle="tab">{{  __lang('student-forum')  }}</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home1">
                                    @php  if(!empty($sessionRow->enable_discussion)): @endphp
                                    <form class="form" method="post" action="{{  route('student.student.adddiscussion') }}">

                                        <p>{{  __lang('ask-a-question')  }}</p>
                                        <div class="modal-body">

                                            @csrf
                                            <div class="form-group">
                                                <label>Recipients</label>
                                                {{  formElement($form->get('admin_id[]')) }}
                                            </div>


                                            <input type="hidden" name="course_id" value="{{  $sessionId  }}"/>
                                            <div class="form-group">
                                                {{  formLabel($form->get('subject')) }}
                                                {{  formElement($form->get('subject')) }}   <p class="help-block">{{  formElementErrors($form->get('subject')) }}</p>

                                            </div>




                                            <div class="form-group">
                                                {{  formLabel($form->get('question')) }}
                                                {{  formElement($form->get('question')) }}   <p class="help-block">{{  formElementErrors($form->get('question')) }}</p>

                                            </div>

                                            <button type="submit" class="btn btn-primary">{{  __lang('submit')  }}</button>

                                        </div>

                                    </form>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <h4>{{  __lang('your-questions')  }}</h4>
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{  __lang('subject')  }}</th>
                                                    <th>{{  __lang('created-on')  }}</th>
                                                    <th>{{  __lang('recipients')  }}</th>
                                                    <th>{{  __lang('replied')  }}</th>
                                                    <th class="text-right1" style="width:90px"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php  foreach($discussions as $row):  @endphp
                                                <tr>
                                                    <td>{{  $row->subject }}
                                                    </td>

                                                    <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                                                    <td>

                                                        @php  if($row->admin==1): @endphp
                                                        {{  __lang('administrators')  }},
                                                        @php  endif;  @endphp

                                                        @php  foreach($accountTable->getDiscussionAccounts($row->id) as $row2):  @endphp
                                                        {{  $row2->name.' '.$row2->last_name }},
                                                        @php  endforeach;  @endphp



                                                    </td>

                                                    <td>{{  boolToString($row->replied)  }}</td>

                                                    <td class="text-right">
                                                        <a href="{{  route('student.student.viewdiscussion',array('id'=>$row->id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View"><i class="fa fa-eye"></i> {{  __lang('view')  }}</a>

                                                    </td>
                                                </tr>
                                                @php  endforeach;  @endphp

                                                </tbody>
                                            </table>


                                        </div>

                                    </div>
                                    @php  else: @endphp
                                    {{  __lang('instruct-chat-unavailable')  }}
                                    @php  endif;  @endphp


                                </div>
                                <div role="tabpanel" class="tab-pane" id="profile1">
                                    @php  if(!empty($sessionRow->enable_forum)): @endphp
                                    {!! $forumTopics  !!}
                                    @php  else: @endphp
                                    {{  __lang('student-forum-unavailable')  }}
                                    @php  endif;  @endphp
                                </div>
                            </div>

                        </div>





                    </div>

                </div>

            </div>



        </div>

    </div>








@endsection
