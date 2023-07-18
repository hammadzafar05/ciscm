@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
<div class="row">

    <div class="col-md-9">

        @php  if($homeworkPresent): @endphp
        <div class="panel panel-default">
            <div class="media v-middle">
                <div class="media-left">
                    <div class="bg-green-400 text-white">
                        <div class="panel-body">
                            <i class="fa fa-edit fa-fw fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="media-body">
                    {{  __lang('pending-homework')  }}
                </div>
                <div class="media-right media-padding">
                    <a class="btn btn-white paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated href="{{  $this->url('application/assignments') }}">
                        {{  __lang('View Homework')  }}
                    </a>
                </div>
            </div>
        </div>
        @php  endif;  @endphp



        <div class="row" data-toggle="isotope">
            <div class="item col-xs-12 col-lg-6">
                <div class="panel panel-default paper-shadow" data-z="0.5">
                    <div class="panel-heading">
                        <h4 class="text-headline margin-none">{{ setting('label_sessions_courses',__lang('courses-sessions')) }}</h4>
                        <p class="text-subhead text-light">Your recent {{ strtolower(setting('label_sessions_courses',__lang('courses-sessions'))) }}</p>
                    </div>
                    <ul class="list-group">
                        @php  foreach($mysessions['paginator'] as $row):  @endphp
                        <li class="list-group-item media v-middle">
                            <div class="media-body">
                                <a
                                @php  if($row->session_type=='c'): @endphp
                                href="{{   $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)])  }}"
                                @php  else: @endphp
                                 href="{{   $this->url('session-details',['id'=>$row->session_id])  }}"

                                @php  endif;  @endphp
                                        class="text-subhead list-group-link">{{  $row->session_name }}</a>
                            </div>
                            <div class="media-right">
                                <div class="progress progress-mini width-100 margin-none">
                                    <div class="progress-bar progress-bar-green-300" role="progressbar" aria-valuenow="{{ $controller->getStudentProgress($row->session_id) }}" aria-valuemin="0" aria-valuemax="100" style="width:45%;">
                                    </div>
                                </div>
                            </div>
                        </li>
                        @php  endforeach;  @endphp
                    </ul>
                    <div class="panel-footer text-right">
                        <a href="{{  $this->url('application/mysessions') }}"
                           class="btn btn-white paper-shadow relative"
                           data-z="0"
                           data-hover-z="1"
                           data-animated href="#"> {{  __lang('View all')  }}</a>
                    </div>
                </div>
            </div>

            <div class="item col-xs-12 col-lg-6">
                @php  if(false):  @endphp
                <div class="panel panel-default paper-shadow" data-z="0.5">
                    <div class="panel-body">
                        <h4 class="text-headline margin-none">Rewards</h4>
                        <p class="text-subhead text-light">Your latest achievements</p>
                        <div class="icon-block half img-circle bg-purple-300">
                            <i class="fa fa-star text-white"></i>
                        </div>
                        <div class="icon-block half img-circle bg-indigo-300">
                            <i class="fa fa-trophy text-white"></i>
                        </div>
                        <div class="icon-block half img-circle bg-green-300">
                            <i class="fa fa-mortar-board text-white"></i>
                        </div>
                        <div class="icon-block half img-circle bg-orange-300">
                            <i class="fa fa-code-fork text-white"></i>
                        </div>
                        <div class="icon-block half img-circle bg-red-300">
                            <i class="fa fa-diamond text-white"></i>
                        </div>
                    </div>
                </div>
                @php  endif;  @endphp

                <div class="panel panel-default paper-shadow" data-z="0.5">
                    <div class="panel-heading">
                        <h4 class="text-headline">{{  __lang('Certificates')  }} </h4>
                    </div>
                    <div class="panel-body">
                        @php  foreach($certificate['paginator'] as $row):  @endphp
                        <a href="{{  $this->url('application/download-certificate',['id'=>$row->certificate_id]) }}" class="btn btn-default text-grey-400 btn-lg btn-circle paper-shadow relative" data-hover-z="0.5" data-animated data-toggle="tooltip" data-title="{{  $row->certificate_name }}">
                            <i class="fa fa-file-pdf"></i>
                        </a>
                        @php  endforeach;  @endphp

                    </div>
                </div>
            </div>


            <div class="item col-xs-12 col-lg-6">
                <div class="panel panel-default paper-shadow" data-z="0.5">
                    <div class="panel-heading">
                        <h4 class="text-headline margin-none">{{  __lang('Tests')  }}</h4>
                        <p class="text-subhead text-light">{{  __lang('your-recent-performance')  }}</p>
                    </div>
                    <ul class="list-group">

                        @php  foreach($student->studentTests()->orderBy('student_test_id','desc')->limit(5)->get() as $testResult): @endphp
                        <li class="list-group-item media v-middle">
                            <div class="media-body">
                                <h4 class="text-subhead margin-none">
                                    <a href="{{  $this->url('application/taketest',array('id'=>$testResult->test_id)) }}" class="list-group-link">{{ $testResult->test->name }}</a>
                                </h4>
                                <div class="caption">
                                    <span class="text-light">{{  __lang('Taken on')  }} </span>
                                    <a href="{{  $this->url('application/taketest',array('id'=>$testResult->test_id)) }}">{{ showDate('d/M/Y',$testResult->created_on) }}</a>
                                </div>
                            </div>
                            <div class="media-right text-center">
                                <div class="text-display-1">{{ round($testResult->score) }}%</div>
                                <span class="caption text-light">{{ $gradeTable->getGrade($testResult->score) }}</span>
                            </div>
                        </li>
                        @php  endforeach; @endphp

                    </ul>
                    <div class="panel-footer">
                        <a href="{{  $this->url('application/default',['controller'=>'test','action'=>'statement']) }}" class="btn btn-primary paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="#"> {{  __lang('go-to-results')  }}</a>
                    </div>
                </div>
            </div>

            <div class="item col-xs-12 col-lg-6">
                <h4 class="text-headline margin-none">{{  __lang('forum-activity')  }}</h4>
                <p class="text-subhead text-light">{{  __lang('latest-topics')  }}</p>
                <ul class="list-group relative paper-shadow" data-hover-z="0.5" data-animated>

                    @php  foreach($forumTopics as $row):  @endphp
                    <li class="list-group-item paper-shadow">
                        <div class="media v-middle">
                            <div class="media-left">

                                    <img src="{{ profilePictureUrl(forumUser($row->topic_owner,$row->topic_owner_type)['photo'],url('/')) }}" alt="person" class="img-circle width-40"/>

                            </div>
                            <div class="media-body">
                                <a href="{{ $this->url('application/default',['controller'=>'forum','action'=>'topic','id'=>$row->forum_topic_id]) }}" class="text-subhead link-text-color">{{  $row->topic_title }}</a>
                                <div class="text-light">
                                    <a  @php  if($row->session_type=='c'): @endphp
                                        href="{{   $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)])  }}"
                                    @php  else: @endphp
                                            href="{{   $this->url('session-details',['id'=>$row->session_id])  }}"
                                    @php  endif;  @endphp
                                    >{{  $row->session_name }}</a> &nbsp;
                                    {{  __lang('latest-topics')  }}: {{  forumUser($row->topic_owner,$row->topic_owner_type)['name']  }}
                                </div>
                            </div>
                            <div class="media-right">
                                <div class="width-60 text-right">
                                    <span class="text-caption text-light">{{ time_elapsed_string(date('r',$row->forum_created_on)) }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    @php  endforeach;  @endphp


                </ul>
            </div>
            <div class="item col-xs-12 col-lg-6">
                <h4 class="text-headline margin-none">{{  __lang('Instructor Chats')  }}</h4>
                <p class="text-subhead text-light">{{  __lang('latest-instructor-disc')  }}</p>
                <ul class="list-group relative paper-shadow" data-hover-z="0.5" data-animated>
                    @php  foreach($discussions['paginator'] as $row):  @endphp
                    <li class="list-group-item paper-shadow">
                        <div class="media v-middle">
                            <div class="media-left">
                                <a href="#">
                                    <img src="{{ profilePictureUrl($student->picture,url('/')) }}" alt="person" class="img-circle width-40"/>
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{  $this->url('application/viewdiscussion',array('id'=>$row->discussion_id)) }}" class="text-subhead link-text-color">{{ $row->subject }}</a>
                                <div class="text-light">

                                    @php  if(!empty($row->session_id) && \Application\Entity\Session::find($row->session_id)):  @endphp

                                       @php  $session = \Application\Entity\Session::find($row->session_id);  @endphp
                                        <a  @php  if($session->session_type=='c'): @endphp
                                            href="{{   $this->url('course-details',['id'=>$session->session_id,'slug'=>safeUrl($session->session_name)])  }}"
                                        @php  else: @endphp
                                            href="{{   $this->url('session-details',['id'=>$session->session_id])  }}"
                                        @php  endif;  @endphp
                                        >{{  $session->session_name }}</a>
                                    @php  endif;  @endphp

                                </div>
                            </div>
                            <div class="media-right">
                                <div class="width-60 text-right">
                                    <span class="text-caption text-light">{{ time_elapsed_string(date('r',$row->created_on)) }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    @php  endforeach;  @endphp

                </ul>
            </div>
        </div>


        <br/>
        <br/>

    </div>
    <div class="col-md-3">

        <div class="panel panel-default" data-toggle="panel-collapse" data-open="true">
            <div class="panel-heading panel-collapse-trigger">
                <h4 class="panel-title">{{  __lang('my-account')  }}</h4>
            </div>
            <div class="panel-body list-group">
                <ul class="list-group list-group-menu">
                    <li class="list-group-item active"><a class="link-text-color" href="{{  $this->url('application/dashboard') }}">{{  __lang('Dashboard')  }}</a></li>
                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/mysessions') }}">{{  setting('label_my_sessions',__lang('my-sessions')) }}</a></li>
                    @php  if(setting('menu_show_courses')==1): @endphp
                        <li class="list-group-item"><a   class="link-text-color" href="{{  $this->url('courses') }}">{{  setting('label_courses',__lang('Online Courses')) }}</a></li>
                    @php  endif;  @endphp
                    @php  if(setting('menu_show_sessions')==1): @endphp
                        <li class="list-group-item"> <a  class="link-text-color" href="{{  $this->url('sessions') }}">{{  setting('label_sessions',__lang('Upcoming Sessions')) }} </a>  </li>
                    @php  endif;  @endphp

                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/assignments') }}">{{  setting('label_homework',__lang('Homework')) }} </a></li>
                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/default',['controller'=>'forum','action'=>'index']) }}">{{  __lang('Student Forum')  }}</a></li>
                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/discussions') }}"><span>{{  __lang('Instructor Chat')  }}</span></a></li>
                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/downloads') }}"><span>{{  __lang('Downloads')  }}</span></a></li>
                    <li class="list-group-item"><a class="link-text-color" href="{{  $this->url('application/certificates') }}"><span>{{  __lang('Certificates')  }}</span></a></li>
                </ul>
            </div>
        </div>

        @php  if($courses['paginator']->count() > 0):  @endphp
        <h4>{{  __lang('Online Courses')  }}</h4>
        <div class="slick-basic slick-slider" data-items="1" data-items-lg="1" data-items-md="1" data-items-sm="1" data-items-xs="1">

            @php  foreach($courses['paginator'] as $row):  @endphp
            <div class="item">
                <div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1" data-animated>
                    <div class="panel-body">
                        <div class="media media-clearfix-xs">
                            <div class="media-left">
                                <div class="cover width-90 width-100pc-xs overlay cover-image-full hover">
                                    <span class="img icon-block s90 bg-default"></span>
                                    <span class="overlay overlay-full padding-none icon-block s90 bg-default">
                        <span class="v-center">
                            @php  if(!empty($row->picture)): @endphp
                            <img src="{{  resizeImage($row->picture,90,90,url('/')) }}" >
                            @php  else:  @endphp
                            <i class="fa fa-graduation-cap"></i>
                            @php  endif;  @endphp
                        </span>
                    </span>
                                    <a href="{{  $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}" class="overlay overlay-full overlay-hover overlay-bg-white">

                                    </a>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading margin-v-5-3"><a href="{{  $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}">{{  $row->session_name  }}</a></h4>
<p>

    @php  if(setting('general_show_fee')==1): @endphp
        <span class="float-right">
            @php  if(empty($row->payment_required)): @endphp
                {{  __lang('Free')  }}
            @php  else:  @endphp
                {{  price($row->amount) }}
            @php  endif;  @endphp
</span>
    @php  endif;  @endphp
</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php  endforeach;  @endphp

        </div>
        @php  endif;  @endphp

        @php  if($sessions['paginator']->count() > 0):  @endphp
        <h4>{{  __lang('Upcoming Sessions')  }}</h4>
        <div class="slick-basic slick-slider" data-items="1" data-items-lg="1" data-items-md="1" data-items-sm="1" data-items-xs="1">
            @php  foreach($sessions['paginator'] as $row):  @endphp

            <div class="item">
                <div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1" data-animated>
                    <div class="panel-body">
                        <div class="media media-clearfix-xs">
                            <div class="media-left">
                                <div class="cover width-90 width-100pc-xs overlay cover-image-full hover">
                                    <span class="img icon-block s90 bg-default"></span>
                                    <span class="overlay overlay-full padding-none icon-block s90 bg-default">
                        <span class="v-center">
                              @php  if(!empty($row->picture)): @endphp
                                  <img src="{{  resizeImage($row->picture,90,90,url('/')) }}" >
                              @php  else:  @endphp
                                  <i class="fa fa-graduation-cap"></i>
                              @php  endif;  @endphp
                        </span>
                    </span>
                                    <a href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}" class="overlay overlay-full overlay-hover overlay-bg-white">
                        <span class="v-center">
                            <span class="btn btn-circle btn-white btn-lg"><i class="fa fa-graduation-cap"></i></span>
                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading margin-v-5-3"><a href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}">{{  $row->session_name  }}</a></h4>
                                <p>

                                    @php  if(setting('general_show_fee')==1): @endphp
                                        <span class="float-right">
            @php  if(empty($row->payment_required)): @endphp
                {{  __lang('Free')  }}
            @php  else:  @endphp
                {{  price($row->amount) }}
            @php  endif;  @endphp
</span>
                                    @php  endif;  @endphp
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php  endforeach;  @endphp

        </div>
        @php  endif;  @endphp
    </div>

</div>
@endsection
