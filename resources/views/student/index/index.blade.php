@extends('layouts.student')
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            url('/')=>__lang('home'),
            '#'=>__lang('dashboard')
        ]])
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            @if(count($all_sessions) > 0)
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td width="30">Sl.</td>
                        <td>Organized by</td>
                        <td>Organizer</td>
                        <td>Course Name</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_sessions as $row)
                        @php
                            if($row->type=='c'){
                                    $url = route('student.course-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]);
                                }
                                else{
                                    $url = route('student.session-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]);
                                }
                        @endphp
                        <tr>
                            <td width="30">{{ $loop->iteration }}</td>
                            <td>
                                <img class="rounded img-fluid img-thumbnail"
                                     src="{{ $row->logo }}"
                                     width="180"
                                     onerror="this.style.display='none'">
                            </td>
                            <td>
                                {{ $row->partner_name }}
                            </td>
                            <td>
                                @php
                                    $course_name = explode(' - Batch ',$row->name);
                                    $course_name = @$course_name[0];
                                @endphp
                                <a href="{{ $url }}">{{ limitLength($course_name,100) }}</a>
                                <br>
                                <div class="progress" data-height="3" >
                                    <div class="progress-bar" role="progressbar" data-width="{{ $controller->getStudentProgress($row->course_id) }}%" aria-valuenow="{{ $controller->getStudentProgress($row->course_id) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ $row->resumeLink }}"><i class="fa fa-play-circle"></i> {{ __lang('view') }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="clearfix"></div>
            @endif
        </div>
    </div>
    @if(count($noticeboards) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4><i class="fa fa-certificate"></i> {{ __lang('Noticeboard') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <td width="30">Sl.</td>
                                <td>Title</td>
                                <td>Description</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($noticeboards as $notice)
                                <tr>
                                    <td width="30">{{ $loop->iteration }}</td>
                                    <td width="250">
                                        {{ $notice['title'] }}
                                    </td>
                                    <td>
                                        {!! $notice['message'] !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-{{ (setting('menu_show_certificates')==1 || setting('menu_show_tests')==1) ? '5':'10' }}">


            @if($homeworkPresent)

                <div class="card card-danger">
                 <div class="card-header">
                     <div ><h4><i class="fa fa-edit"></i> {{ __lang('homework') }}</h4></div>

                </div>
                <div class="card-body">
                    {{ __lang('pending-homework') }}
                </div>
                    <div class="card-footer">
                        <a href="{{ route('student.assignment.index') }}" class="btn btn-success   float-right"><i class="fa fa-edit"></i> {{ __lang('view-homework') }}</a>
                    </div>
                </div>

            @endif
            <div class="card card-primary">
                <div class="card-header">
                    <h4><i class="fa fa-book"></i> {{ setting('label_sessions_courses',__lang('courses-sessions')) }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('student.student.mysessions') }}" class="btn btn-primary">{{ __lang('view-all-courses') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="summary">
                        <div class="summary-item">
                            <h6>{{ __lang('enrolled-courses') }} <span class="text-muted">({{ $mysessions['total'] }} {{__lang('Items')}})</span></h6>
                            <ul class="list-unstyled list-unstyled-border">

                                @foreach($mysessions['paginator'] as $row)

                                <li class="media">
                                    @php
                                        if($row->type=='c'){
                                                $url = route('student.course-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]);
                                            }
                                            else{
                                                $url = route('student.session-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]);
                                            }
                                    @endphp

                                    <a href="{{ $url }}">

                                        @if(!empty($row->picture))
                                            <img class="mr-3 rounded" src="{{ resizeImage($row->picture,671,480,basePath()) }}" alt="product" width="50">

                                        @else
                                            <img class="mr-3 rounded" src="{{ asset('img/course.png') }}" alt="product" width="50">

                                        @endif
                                    </a>
                                    <div class="media-body">
                                        {{--<div class="media-right"><a class="btn btn-primary btn-sm" href="{{ $url }}"><i class="fa fa-play-circle"></i> {{ __lang('view') }}</a></div>--}}
                                        <div class="media-right"><a class="btn btn-primary btn-sm" href="{{ $row->last_lecture_link }}"><i class="fa fa-play-circle"></i> {{ __lang('Start') }}</a></div>
                                        <div class="media-title">

                                            {{--MARUF START--}}
                                            @php
                                                $course_name = explode(' - Batch ',$row->name);
                                                $course_name = @$course_name[0];
                                            @endphp
                                            {{--<a href="{{ $url }}"></a>--}}
                                            {{ limitLength($course_name,100) }}
                                            {{--MARUF END--}}

                                            <div style="width: 70%">
                                                <div class="progress" data-height="3" >
                                                    <div class="progress-bar" role="progressbar" data-width="{{ $controller->getStudentProgress($row->course_id) }}%" aria-valuenow="{{ $controller->getStudentProgress($row->course_id) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-muted text-small"><a href="{{ $url }}">{{ \App\Course::find($row->course_id)->lessons()->count() }} {{ __lang('classes') }}</a>
                                            <div class="bullet"></div> {{ courseType($row->type) }}</div>
                                    </div>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>




                @if(setting('menu_show_discussions')==1)
                <div class="card card-success">
                    <div class="card-header">
                        <h4 class="d-inline"><i class="fa fa-comments"></i> {{ __lang('discussions') }}</h4>
                    </div>
                    <div class="card-body">
                          <ul class="nav nav-pills" id="myTab3" role="tablist">
                                                <li class="nav-item">
                                                  <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __lang('student-forum') }}</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('instructor-chats') }}</a>
                                                </li>
                                              </ul>
                                              <div class="tab-content" id="myTabContent2">
                                                <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">

                                                    <div class="card-title">{{ __lang('latest-topics') }} </div>

                                                    <ul class="list-unstyled list-unstyled-border">
                                                        @foreach($forumTopics as $row)
                                                            @php
                                                                $user = \App\User::find($row->user_id);
                                                            @endphp
                                                            <li class="media">
                                                                @if($user)
                                                                    <img data-toggle="tooltip" data-placement="top" data-original-title="{{  $user->name  }}" class="mr-3 rounded-circle" width="50" src="{{ profilePictureUrl($user->picture) }}" alt="avatar">
                                                                @endif
                                                                <div class="media-body">
                                                                    <a  class="badge badge-pill badge-success mb-1 float-right" href="{{ route('student.forum.topic',['id'=>$row->forum_topic_id]) }}">{{ __lang('view') }}</a>

                                                                    <h6 class="media-title"><a href="{{ route('student.forum.topic',['id'=>$row->id]) }}">{{ $row->title }}</a></h6>
                                                                    <div class="text-small text-muted">  {{ $row->name}}  <div class="bullet"></div> <span class="text-primary">{{ \Illuminate\Support\Carbon::parse($row->forum_created_on)->diffForHumans() }}</span></div>
                                                                </div>
                                                            </li>
                                                        @endforeach


                                                    </ul>

                                                    <a href="{{ route('student.forum.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-right"></i> {{ __lang('view-all') }}</a>
                                                </div>
                                                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">


                                                    <ul class="list-unstyled list-unstyled-border">
                                                        @foreach($discussions['paginator'] as $row)

                                                            <li class="media">

                                                                <div class="media-body">
                                                                    <a  class="badge badge-pill badge-success mb-1 float-right" href="{{ route('student.student.viewdiscussion',['id'=>$row->id]) }}">{{ __lang('view') }}</a>

                                                                    <h6 class="media-title"><a href="{{ route('student.student.viewdiscussion',['id'=>$row->id]) }}">{{ $row->subject }}</a></h6>
                                                                    <div class="text-small text-muted">
                                                                    @if(\App\Course::find($row->course_id))
                                                                     {{ \App\Course::find($row->course_id)->name}}  <div class="bullet"></div>
                                                                    @endif
                                                                     {{ \App\Discussion::find($row->id)->discussionReplies()->count() }} {{ __lang('replies') }}   <div class="bullet"></div>
                                                                        <span class="text-primary">{{ \Illuminate\Support\Carbon::parse($row->created_at)->diffForHumans() }}</span>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach


                                                    </ul>

                                                    <a href="{{ route('student.student.discussion') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-right"></i> {{ __lang('view-all') }}</a>

                                                </div>

                                              </div>



                    </div>
                </div>

                @endif



        </div>
        @if(setting('menu_show_certificates')==1 || setting('menu_show_tests')==1 )
        <div class="col-md-4">

            <!--@if(setting('menu_show_certificates')==1)-->
            <!--<div class="card card-primary">-->
            <!--    <div class="card-header">-->
            <!--        <h4><i class="fa fa-certificate"></i> {{ __lang('certificates') }}</h4>-->
            <!--    </div>-->
            <!--    <div class="card-body">-->
            <!--        <div class="row">-->
            <!--            @foreach($certificate['paginator'] as $row)-->
            <!--            <div class="col text-center">-->
            <!--                <a href="{{ route('student.student.downloadcertificate',['id'=>$row->certificate_id]) }}">-->
            <!--                    <h1><i class="fa fa-file-pdf"></i></h1>-->
            <!--                 </a>-->
            <!--                <div class="mt-2 font-weight-bold">{{ $row->certificate_name }}</div>-->
            <!--            </div>-->
            <!--            @endforeach-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--@endif-->
            
              <!--kabir Works-->
            @if(setting('menu_show_certificates')==1)
            <div class="card card-primary">
                <div class="card-header">
                    <h4><i class="fa fa-book"></i> {{ __lang('downloads') }}</h4>
                </div>
                  <ul class="list-unstyled list-unstyled-border">

                                @foreach($paginator as $row)

                                <li class="media">
                             

                                
                                    <div class="media-body">
                                        {{--<div class="media-right"><a class="btn btn-primary btn-sm" href="{{ $url }}"><i class="fa fa-play-circle"></i> {{ __lang('view') }}</a></div>--}}
                                        <div class="media-right"><a class="btn btn-primary btn-sm" href="{{  route('student.download.files',array('id'=>$row->download_id)) }}"><i class="fa fa-play-circle"></i> {{  __lang('view-files')}}</a></div>
                                        <div class="media-title" style="padding-left:20px">
                                       <i class="fas fa-forward"></i>
                                            {{--<a href="{{ $url }}"></a>--}}
                                            {{  $row->download_name }}
                                            

                                         

                                        </div>
                                     
                                    </div>
                                </li>
                                @endforeach

                            </ul>
            </div>
            @endif

            @if(setting('menu_show_tests')==1)
            <div class="card card-info">
                <div class="card-header">
                    <h4><i class="fas fa-check-circle"></i> {{ __lang('tests') }}</h4>
                    <!--<div class="card-header-action"><a class="btn btn-primary" href="{{ route('student.test.statement') }}">{{ __lang('view-all') }}</a>-->
                    <!--</div>-->
                    
                    <div class="card-header-action"><a target="_blank" class="btn btn-primary" href="{{ url('student/test/index')}}">{{ __lang('view-all') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="summary">
                        <div class="summary-item">
                            <h6>{{ __lang('your-recent-performance') }}</h6>
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach($student->studentTests()->orderBy('id','desc')->limit(5)->get() as $testResult)
                                <li class="media">

                                    <div class="media-body">
                                        <div class="media-right">{{ round($testResult->score) }}%</div>
                                        <div class="media-title"><a href="{{ route('student.test.taketest',['id'=>$testResult->test_id]) }}">{{ $testResult->test->name }}</a></div>
                                        <div class="text-muted text-small">{{ __lang('taken-on') }}    {{ \Illuminate\Support\Carbon::parse($testResult->created_at)->format('d m Y') }}</div>
                                    </div>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif


        </div>
        @endif
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item active">{{ __lang('my-account') }}</li>
                <li class="list-group-item"><a href="{{ route('student.student.mysessions') }}"><i class="fas fa-chalkboard-teacher"></i> {{ setting('label_my_sessions',__lang('my-courses')) }}</a></li>
                @if(setting('menu_show_homework')==1)
                <li class="list-group-item"><a href="{{ route('student.assignment.index') }}"><i class="fas fa-edit"></i> {{ __lang('homework') }}</a> </li>
                @endif

                @if(setting('menu_show_discussions')==1)
                <li class="list-group-item"><a href="{{ route('student.forum.index') }}"><i class="fas fa-comments"></i> {{ __lang('student-forum') }}</a> </li>
                <li class="list-group-item"><a href="{{ route('student.student.discussion') }}"><i class="fas fa-comment"></i> {{ __lang('instructor-chat') }}</a> </li>
                @endif
                @if(setting('menu_show_downloads')==1)
                <li class="list-group-item"><a href="{{ route('student.download.index') }}"><i class="fa fa-book"></i> {{ __lang('downloads') }}</a> </li>
                @endif
                <!--@if(setting('menu_show_certificates')==1)-->
                <!--<li class="list-group-item"><a href="{{ route('student.student.certificates') }}"><i class="fas fa-certificate"></i> {{ __lang('certificates') }}</a> </li>-->
                <!--@endif-->
            </ul>


        </div>
    </div>

@endsection
