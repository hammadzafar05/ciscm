@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
@php $this->headStyle()->captureStart()  @endphp
body .container.body .right_col {
background: #F7F7F7;
}
@php $this->headStyle()->captureEnd()  @endphp
<div>
    <div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ adminUrl(['controller'=>'student','action'=>'index']) }}">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="count">{{ $totalStudents }}@php if(defined('STUDENT_LIMIT') && STUDENT_LIMIT > 0): @endphp
                    /{{ STUDENT_LIMIT }}
                    @php endif;  @endphp
                </div>
                <h3>{{ __lang('active-students') }}</h3>
            </div>
            </a>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ adminUrl(array('controller'=>'student','action'=>'sessions')) }}?type=c">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-book"></i></div>
                    <div class="count">{{ $totalCourses }}</div>
                    <h3>{{ __lang('online-courses') }}</h3>
                </div>
            </a>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ adminUrl(array('controller'=>'student','action'=>'sessions')) }}">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-o"></i></div>
                    <div class="count">{{ $totalSessions }}</div>
                    <h3>{{ __lang('active-sessions') }}</h3>
                </div>
            </a>
        </div>

        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ adminUrl(array('controller'=>'lesson','action'=>'index')) }}">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-desktop"></i></div>
                    <div class="count">{{ $totalClasses }}</div>
                    <h3>{{ __lang('classes') }}</h3>
                </div>
            </a>
        </div>

    </div>

</div>
@php if($assignment['total'] > 0):  @endphp
<div class="card">
    <div class="card-header">
        {{ __lang('homework') }}
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ __lang('title') }}</th>
                <th>{{ __lang('session-course') }}</th>
                <th>{{ __lang('created-on') }}</th>
                <th>{{ __lang('due-date') }}</th>
                <th>{{ __lang('submissions') }}</th>
                <th class="text-right1" >{{ __lang('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @php foreach($assignment['paginator'] as $row):  @endphp
                <tr>
                    <td>{{ $row->title }}</td>
                    <td><span >{{ $row->session_name }}</span></td>
                    <td>{{ showDate('d/m/Y',$row->created_on) }}</td>
                    <td>{{ showDate('d/m/Y',$row->due_date) }}</td>
                    <td>
                        {{ $assignment['submissionTable']->getTotalForAssignment($row->assignment_id) }} <a class="btn btn-primary btn-sm" href="{{ adminUrl(['controller'=>'assignment','action'=>'submissions','id'=>$row->assignment_id]) }}">{{ __lang('view-all') }}</a>
                    </td>

                    <td class="text-right1">
                        <a href="{{ adminUrl(array('controller'=>'assignment','action'=>'edit','id'=>$row->assignment_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>

                        <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'assignment','action'=>'delete','id'=>$row->assignment_id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                        <a onclick="openModal('{{__lang('homework-info')}}','{{ adminUrl(['controller'=>'assignment','action'=>'view','id'=>$row->assignment_id]) }}')" href="#" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('info') }}"><i class="fa fa-info"></i></a>
                    </td>
                </tr>
            @php endforeach;  @endphp

            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <a href="{{ adminUrl(['controller'=>'assignment','action'=>'index']) }}">{{__lang('view-all')}}</a>
    </div>
</div>
@php endif;  @endphp


@php if($this->hasPermission('view_discussions') && $discuss['total']>0): @endphp
    <div class="card">
        <div class="card-header">
            {{ __lang('pending-discussions') }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>{{ __lang('subject') }}</th>
                    <th>{{ __lang('student') }}</th>
                    <th>{{ __lang('created-on') }}</th>
                    <th>{{ __lang('replied') }}</th>
                    <th class="text-right1" style="width:120px">{{ __lang('actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @php foreach($discuss['paginator'] as $row):  @endphp
                    <tr>
                        <td>{{ $row->subject }}</td>
                        <td>{{ $row->name.' '.$row->last_name }}</td>
                        <td>{{ showDate('d/M/Y',$row->created_on) }}</td>
                        <td>{{ boolToString($row->replied) }}</td>


                        <td class="text-right">
                            <a href="{{ adminUrl(array('controller'=>'discuss','action'=>'viewdiscussion','id'=>$row->discussion_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('view') }}"><i class="fa fa-eye"></i></a>

                            <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'discuss','action'=>'delete','id'=>$row->discussion_id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @php endforeach;  @endphp

                </tbody>
            </table>
            </div>
        </div>
        <div class="panel-footer">
            <a href="{{ $this->url('admin/payments') }}">{{__lang('view-all')}}</a>
        </div>
    </div>
@php endif;  @endphp

@php if($this->hasPermission('view_sessions')): @endphp
<div class="card">
    <div class="card-header">
        {{ __lang('courses-and-sessions') }}
    </div>
    <div class="card-body">

        @php foreach($session['paginator'] as $row):  @endphp
        <div style="margin-bottom: 20px; border-bottom: solid 1px #cccccc; padding-top: 5px; padding-bottom: 5px">
            <div class="row">
                <div class="col-md-2">

                    @php if(!empty($row->picture)):  @endphp

                        <a href="{{ adminUrl(array('controller'=>'student','action'=>'editsession','id'=>$row->session_id)) }}"  style="border: none; margin-bottom: 0px">
                            <img class="img-thumbnail img-responsive" src="{{ resizeImage($row->picture,150,130,basePath()) }}" >
                        </a>
                    @php else:  @endphp
                        <a href="{{ adminUrl(array('controller'=>'student','action'=>'editsession','id'=>$row->session_id)) }}"  style="border: none; margin-bottom: 0px">
                            <img class="img-thumbnail img-responsive" src="{{ basePath() }}/img/course.png" >
                        </a>
                    @php endif;  @endphp

                </div>
                <div class="col-md-8">
                    <h3>{{ $row->session_name }}</h3>
                    <h5>@php
                        switch($row->session_type){
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
                        @endphp</h5>
                    @php if(GLOBAL_ACCESS): @endphp
                        <strong>{{ __lang('created-by') }}: {{ adminName($row->admin_id) }}</strong>
                    @php endif;  @endphp
                    <div>
                        <div class="row">
                            <div class="col-md-4" style="padding: 0px;">
                                @php if($row->payment_required==1):  @endphp
                                    {{ __lang('price') }}:  <strong style="color: green">{{ price($row->amount) }}</strong>
                                @php else:  @endphp
                                    <strong style="color:red">{{ strtoupper(__lang('free')) }}</strong>
                                @php endif;  @endphp

                                <p>{{ __lang('status') }}: {{ ($row->session_status!=1)?'<span style="color: red;">'.__lang('disabled').'</span>':'<span style="color: green;">'.__lang('enabled').'</span>' }}</p>
                            </div>
                            <div class="col-md-4" style="padding: 0px;">
                                <div class="row">
                                    <div class="col-md-8">{{ __lang('enrolled-students') }}:</div>
                                    <div class="col-md-4"><a style="text-decoration: underline"  href="javascript:;" onclick="openModal('{{__lang('enrollees-for')}} {{ $row->session_name}}','{{ adminUrl(array('controller'=>'student','action'=>'sessionenrollees','id'=>$row->session_id)) }}')">

                                            <strong>{{ $session['studentSessionTable']->getTotalForSession($row->session_id) }}</strong>
                                        </a></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">{{ __lang('total-attended') }}</div>
                                    <div class="col-md-4">   <a  style="text-decoration: underline"    href="javascript:;" onclick="openModal('{{__lang('attendees-for')}} {{ $row->session_name}}','{{ adminUrl(array('controller'=>'student','action'=>'sessionattendees','id'=>$row->session_id)) }}')">
                                            <strong>{{ $session['attendanceTable']->getTotalStudentsForSession($row->session_id) }}</strong>
                                        </a>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="btn-group dropup btn-group-xs">
                                    <button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">
                                        <i class="fa fa-wrench"></i> {{ __lang('export') }} <i class="fa fa-caret-up"></i>
                                    </button>
                                    <ul class="dropdown-menu float-right animation-slide" role="menu" style="text-align: left;">
                                        <li><a  href="{{ adminUrl(array('controller'=>'student','action'=>'export','id'=>$row->session_id)) }}"><i class="fa fa-users"></i> {{ __lang('export-students') }}</a></li>
                                        <li><a  href="{{ adminUrl(array('controller'=>'student','action'=>'exportbulkattendance','id'=>$row->session_id)) }}"><i class="fa fa-users"></i> {{ __lang('export-students') }} ({{ __lang('attendance-import') }})</a></li>
                                        @php if($row->session_type != 'c'): @endphp
                                            <li><a target="_blank" href="{{ adminUrl(array('controller'=>'student','action'=>'exportattendance','id'=>$row->session_id)) }}"><i class="fa fa-table"></i> {{ __lang('attendance-sheet') }}</a></li>
                                        @php endif;  @endphp
                                        <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'exporttel','id'=>$row->session_id)) }}"><i class="fa fa-phone"></i> {{ __lang('telephone-numbers') }}</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 expand-div" style="padding-top: 30px; text-align: right; ">
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-list"></i> {{ __lang('actions') }} <i class="fa fa-caret-up"></i>
                        </button>
                        <ul class="dropdown-menu float-right animation-slide" role="menu" style="text-align: left;">
                            @php if($row->session_type != 'c'): @endphp
                                <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'editsession','id'=>$row->session_id)) }}"  ><i class="fa fa-edit"></i> {{ __lang('edit') }}</a></li>
                                <li><a href="{{ adminUrl(array('controller'=>'session','action'=>'sessionclasses','id'=>$row->session_id)) }}"  ><i class="fa fa-desktop"></i> {{ __lang('manage-classes') }}</a></li>
                            @php else: @endphp
                                <li><a href="{{ adminUrl(array('controller'=>'session','action'=>'editcourse','id'=>$row->session_id)) }}"  ><i class="fa fa-edit"></i> {{ __lang('edit') }}</a></li>
                                <li><a href="{{ adminUrl(array('controller'=>'session','action'=>'courseclasses','id'=>$row->session_id)) }}"  ><i class="fa fa-desktop"></i> {{ __lang('manage-classes') }}</a></li>
                                <li><a target="_blank" href="{{ adminUrl(array('controller'=>'course','action'=>'intro','id'=>$row->session_id)) }}"  ><i class="fa fa-play"></i> {{ __lang('try-course') }}</a></li>

                            @php endif;  @endphp
                            <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'sessionstudents','id'=>$row->session_id)) }}"  ><i class="fa fa-users"></i> {{ __lang('view-enrolled') }}</a></li>
                            @php if($row->session_type != 'c'): @endphp
                                <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'instructors','id'=>$row->session_id)) }}" ><i class="fa fa-user"></i> {{ __lang('manage-instructors') }}</a></li>
                            @php endif;  @endphp
                            <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'mailsession','id'=>$row->session_id)) }}"  ><i class="fa fa-envelope"></i> {{ __lang('send-message-enrolled') }}</a></li>
                            <li><a href="{{ adminUrl(array('controller'=>'student','action'=>'duplicatesession','id'=>$row->session_id)) }}"  ><i class="fa fa-copy"></i> {{ __lang('duplicate') }}</a></li>
                            @php if($row->session_type != 'c'): @endphp
                                <li><a onclick="openModal('Change Type: {{ addslashes($row->session_name) }}','{{ adminUrl(['controller'=>'session','action'=>'sessiontype','id'=>$row->session_id]) }}')" href="#"><i class="fa fa-refresh"></i> {{ __lang('change-session-type') }}</a></li>
                            @php endif;  @endphp
                            <li><a href="{{ adminUrl(array('controller'=>'session','action'=>'tests','id'=>$row->session_id)) }}"><i class="fa fa-check"></i> Manage tests</a></li>
                            <li><a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'student','action'=>'deletesession','id'=>$row->session_id)) }}"   ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a></li>
                        </ul>
                    </div>

                </div>

            </div>

        </div>
        @php endforeach;  @endphp




    </div>
    <div class="panel-footer">
        <a href="{{ adminUrl(array('controller'=>'student','action'=>'sessions')) }}">{{__lang('view-all')}}</a>
    </div>
</div>
@php endif;  @endphp



@php if($this->hasPermission('view_students')): @endphp
<div class="card">
    <div class="card-header">
        {{ __lang('new-students') }}
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __lang('first-name') }}</th>
                <th>{{ __lang('last-name') }}</th>
                <th>{{ __lang('course-session') }}</th>
                <th class="text-right1" style="width:150px">{{ __lang('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @php $count = 0;  @endphp
            @php foreach($student['paginator'] as $row):  @endphp
                @php $count++; @endphp
                <tr>
                    <td><span class="label label-success">{{ $row->student_id }}</span></td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->last_name }}</td>
                    <td><strong>{{ $student['studentSessionTable']->getTotalForStudent($row->student_id) }}</strong>

                    </td>

                    <td >
                        <a href="{{ adminUrl(array('controller'=>'student','action'=>'edit','id'=>$row->student_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                        <a href="#" onclick="openModal('{{ __lang('enroll') }}','{{ adminUrl(array('controller'=>'student','action'=>'enroll','id'=>$row->student_id)) }}')"  data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('enroll') }}"   title="{{ __lang('enroll') }}" type="button" class="btn btn-xs btn-primary btn-equal"  ><i class="fa fa-plus"></i></a>

                        <button   data-id="{{ $row->student_id }}" data-toggle="modal" data-target="#simpleModal" title="View" type="button" class="btn btn-xs btn-primary btn-equal viewbutton"  ><i class="fa fa-eye"></i></button>
                        <a onclick="return confirm('{{ __lang('delete-confirm') }}')" href="{{ adminUrl(array('controller'=>'student','action'=>'delete','id'=>$row->student_id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @php if($count > 10 ){break;} @endphp
            @php endforeach;  @endphp

            </tbody>
        </table>

    </div>
    <div class="panel-footer">
        <a href="{{ adminUrl(array('controller'=>'student','action'=>'index')) }}">{{__lang('view-all')}}</a>
    </div>
</div>
@php endif;  @endphp


@php if($this->hasPermission('view_payments')): @endphp
<div class="card">
    <div class="card-header">
        {{ __lang('recent-payments') }}
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __lang('student') }}</th>
                <th>{{ __lang('payment-method') }}</th>
                <th>{{ __lang('amount') }}</th>
                <th>{{ __lang('added-on') }}</th>
            </tr>
            </thead>
            <tbody>


            @php $count = 0;  @endphp
            @php foreach($payment['paginator'] as $row):  @endphp
                @php $count++; @endphp
                <tr>
                    <td>{{ $row->payment_id }}</td>
                    <td>{{ $row->name }} {{ $row->last_name }} ({{ $row->email }})</td>
                    <td>{{ $row->payment_method }}</td>
                    <td>{{ $this->formatPrice($row->amount) }}</td>
                    <td>{{ showDate('d/M/Y',$row->added_on) }}</td>

                </tr>
                @php if($count > 10 ){break;} @endphp
            @php endforeach;  @endphp





            </tbody>
        </table>

    </div>
    <div class="panel-footer">
        <a href="{{ $this->url('admin/payments') }}">{{__lang('view-all')}}</a>
    </div>
</div>
@php endif;  @endphp


<!-- START SIMPLE MODAL MARKUP -->
<div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="simpleModalLabel">{{ __lang('student-details') }}</h4>
            </div>
            <div class="modal-body" id="info">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->

<script type="text/javascript">
    $(function(){
        $('.viewbutton').click(function(){
            $('#info').text('Loading...');
            var id = $(this).attr('data-id');
            $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
        });
        $.get('{{ basePath() }}/db/migrate');
    });
</script>
@endsection
