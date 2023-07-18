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
@php  if($homework['total'] > 0):  @endphp
    <div class="panel panel-primary">
        <div class="panel-heading">
            My Homework
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>{{ __lang('course-session') }}</th>
                        <th>Created On</th>
                        <th>Due Date</th>
                        <th class="text-right1" ></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php  foreach($homework['paginator'] as $row):  @endphp
                        <tr>
                            <td>{{  $row->title }}</td>
                            <td><span >{{  $row->session_name  }}</span></td>
                            <td>{{  date('d/M/Y',$row->created_on) }}</td>
                            <td>{{  date('d/M/Y',$row->due_date) }}</td>

                            <td class="text-right1">
                                <a class="btn btn-primary"
                                   href="{{ $this->url('application/submit-assignment',['id'=>$row->assignment_id]) }}">
                                    <i class="fa fa-file"></i> View/Submit Homework
                                </a>
                            </td>
                        </tr>

                    @php  endforeach;  @endphp

                    </tbody>
                </table>

            </div>
        </div>
        <div class="panel-footer">
            <a href="{{  $this->url('application/assignments') }}">View All</a>
        </div>
    </div>
@php  endif;  @endphp

<div class="panel panel-primary">
    <div class="panel-heading">
        My Sessions &amp; Courses
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>

                        <th>{{ __lang('course-session') }}</th>
                        <th>Type</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Classes Attended</th>

                        <th class="text-right1" style="width:90px"></th>

                    </tr>
                    </thead>
                    <tbody>
                    @php  foreach($mysessions['paginator'] as $row):  @endphp
                        <tr>
                            <td>{{  $row->session_name }}</td>
                            <td>@php
                                switch($row->session_type){
                                    case 'b':
                                        echo 'Training Session with Online Classes';
                                        break;
                                    case 's':
                                        echo 'Training Session';
                                        break;
                                    case 'c':
                                        echo 'Online Course';
                                        break;
                                }
                                 @endphp</td>
                            <td>@php  if(!empty($row->session_date)) echo showDate('d/M/Y',$row->session_date);  @endphp</td>
                            <td>@php  if(!empty($row->session_end_date))   echo showDate('d/M/Y',$row->session_end_date);  @endphp</td>
                            <td>{{  $mysessions['attendanceTable']->getTotalForStudentInSession($mysessions['id'],$row->session_id)  }}</td>
                            <td class="text-right">
                                @php  if($row->session_type=='c'): @endphp
                                    <a class="btn btn-success" href="{{   $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)])  }}" ><i class="fa fa-info-circle"></i> Details</a>
                                @php  else: @endphp
                                    <a class="btn btn-success" href="{{   $this->url('session-details',['id'=>$row->session_id])  }}" ><i class="fa fa-info-circle"></i> Details</a>

                                @php  endif;  @endphp
                            </td>

                        </tr>
                    @php  endforeach;  @endphp

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('application/mysessions') }}">View All</a>
    </div>
</div>


@php  if($courses['paginator']->count() > 0): @endphp
<div class="panel panel-primary">
    <div class="panel-heading">
        Recently Added Courses
    </div>
    <div class="panel-body">
        <div class="row">


            @php  foreach($courses['paginator'] as $row):  @endphp
                <div class="col-md-4" style="height: 450px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{  $row->session_name  }}</h3>
                        </div>
                        <div class="panel-body">

                            <div >
                                @php  if(!empty($row->picture)):  @endphp
                                    <div >
                                        <a href="{{  $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}" class="thumbnail" style="border: none; margin-bottom: 0px">
                                            <img src="{{  resizeImage($row->picture,300,150,url('/')) }}" >
                                        </a>
                                    </div>
                                @php  endif;  @endphp

                                <div >
                                    <article style="color: #73879C;
                                font-size: 13px;
                                font-weight: 400;
                                line-height: 1.471;" class="readmore">{{  limitLength($row->short_description,300) }}</article>
                                </div>
                            </div>

                        </div>

                        <div  class="panel-footer" style="min-height: 50px">
                            @php  if(setting('general_show_fee')==1): @endphp
                                <strong>
                                    @php  if(empty($row->payment_required)): @endphp
                                        Free
                                    @php  else:  @endphp
                                        {{  price($row->amount) }}
                                    @php  endif;  @endphp
                                </strong>
                            @php  endif;  @endphp

                            <a class="btn btn-success float-right" href="{{  $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}" ><i class="fa fa-info-circle"></i> Details</a>



                        </div>
                    </div>
                </div>
            @php  endforeach;  @endphp


        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('courses') }}">View All</a>
    </div>
</div>
@php  endif;  @endphp

<div class="panel panel-primary">
    <div class="panel-heading">
        Upcoming Sessions
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">

                @php  foreach($sessions['paginator'] as $row):  @endphp

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{  $row->session_name  }}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                @php  if(!empty($row->picture)):  @endphp
                                    <div class="col-md-3">
                                        <a href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}" class="thumbnail">
                                            <img src="{{  resizeImage($row->picture,300,300,url('/')) }}" >
                                        </a>
                                    </div>
                                @php  endif;  @endphp

                                <div class="col-md-{{  (empty($row->picture)? '12':'9')  }}">
                                    <article class="readmore">{{  $row->short_description }}</article>
                                </div>
                            </div>

                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Enrollment Closes</th>
                                @php  if(setting('general_show_fee')==1): @endphp
                                    <th>Fee</th>
                                @php  endif;  @endphp
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{  showDate('d/M/Y',$row->session_date) }}</td>
                                <td>{{  date('d/M/Y',$row->session_end_date) }}</td>
                                <td>{{  date('d/M/Y',$row->enrollment_closes) }}</td>
                                @php  if(setting('general_show_fee')==1): @endphp
                                    <td>    @php  if(empty($row->payment_required)): @endphp
                                            Free
                                        @php  else:  @endphp
                                            {{  price($row->amount) }}
                                        @php  endif;  @endphp</td>
                                @php  endif;  @endphp
                            </tr>

                            </tbody>
                        </table>
                        <div style="text-align: right" class="panel-footer">
                            @php  if($row->enrollment_closes > time()):  @endphp
                                @php  if($sessions['studentSessionTable']->enrolled($sessions['id'],$row->session_id)):  @endphp
                                    <a href="{{  $this->url('application/default',array('controller'=>'student','action'=>'removesession','id'=>$row->session_id)) }}" class="btn btn-primary " ><i class="fa fa-minus"></i> Un enroll</a>

                                @php  elseif($row->enrollment_closes > time()):  @endphp
                                    <a href="{{  $this->url('set-session',array('id'=>$row->session_id)) }}" class="btn btn-primary " ><i class="fa fa-plus"></i> Enroll Now</a>
                                @php  endif;  @endphp
                            @php  endif;  @endphp

                            <a class="btn btn-success" href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}" ><i class="fa fa-table"></i> Details</a>

                        </div>
                    </div>
                @php  endforeach;  @endphp
            </div>

        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('sessions') }}">View All</a>
    </div>
</div>

@php  if($notes['paginator']->count() > 0): @endphp
<div class="panel panel-primary">
    <div class="panel-heading">
        Recent Revision Notes
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>

                    <th>Session</th>
                    <th>Session Start Date</th>
                    <th>Total Notes</th>
                    <th class="text-right1" style="width:90px">Actions</th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($notes['paginator'] as $row):  @endphp
                    <tr>
                        <td>{{  $row->session_name }}</td>
                        <td>{{  date('d/M/Y',$row->session_date) }}</td>
                        <td>{{  $notes['homeworkTable']->getTotalForCategory($row->session_id)  }}</td>

                        <td class="text-right">

                            <a href="{{  $this->url('application/sessionnotes',array('id'=>$row->session_id)) }}" class="btn btn-primary " ><i class="fa fa-eye"></i> View Notes</a>

                        </td>
                    </tr>
                @php  endforeach;  @endphp

                </tbody>
            </table>

        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('application/notes') }}">View All</a>
    </div>
</div>
@php  endif;  @endphp

@php  if($downloads['paginator']->count() > 0): @endphp
<div class="panel panel-primary">
    <div class="panel-heading">
        Recent Downloads
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Files</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($downloads['paginator'] as $row):  @endphp
                    @php  if($downloads['downloadTable']->getDownload($row->download_id,$downloads['studentId'])): @endphp
                <td><span class="label label-success">{{  $row->download_id  }}</span></td>
                        <td>{{  $row->download_name }}</td>
                        <td>{{  $downloads['fileTable']->getTotalForDownload($row->download_id) }}</td>

                        <td class="text-right">
                        @php  if ($downloads['fileTable']->getTotalForDownload($row->download_id)> 0):  @endphp
                        <a href="{{  $this->url('application/download-list',array('id'=>$row->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View Files"><i class="fa fa-eye"></i> View files</a>
                        <a href="{{  $this->url('application/download-all',array('id'=>$row->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Download All Files"><i class="fa fa-download"></i> Download All</a>
                    @php  else: @endphp
                        <strong>No files available</strong>
                    @php  endif;  @endphp
                        </td>
                    </tr>
                @php  endif;  @endphp
                @php  endforeach;  @endphp

                </tbody>
            </table>

        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('application/downloads') }}">View All</a>
    </div>
</div>
@php  endif;  @endphp

@php  if($discussions['paginator']->count() > 0): @endphp
<div class="panel panel-primary">
    <div class="panel-heading">
        Recent Discussions
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Created On</th>
                    <th>Replied</th>
                    <th class="text-right1" style="width:90px"></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($discussions['paginator'] as $row):  @endphp
                    <tr>
                        <td>{{  $row->subject }}
                        </td>

                        <td>{{  date('d/M/Y',$row->created_on) }}</td>
                        <td>{{  boolToString($row->replied)  }}</td>

                        <td class="text-right">
                            <a href="{{  $this->url('application/viewdiscussion',array('id'=>$row->discussion_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View"><i class="fa fa-eye"></i> View</a>

                        </td>
                    </tr>
                @php  endforeach;  @endphp

                </tbody>
            </table>

        </div>
    </div>
    <div class="panel-footer">
        <a href="{{  $this->url('application/discussions') }}">View All</a>
    </div>
</div>
@php  endif;  @endphp

@endsection
