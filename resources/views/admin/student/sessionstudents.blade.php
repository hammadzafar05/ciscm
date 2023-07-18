@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__lang('courses'),
            '#'=>__lang('students')
        ]])
@endsection

@section('content')
    <div>


       <div class="card">
            <div class="card-header">
                Import Results
            </div>
            <div class="card-body">
                <form onsubmit="return confirm('Are you sure about importing result?')"
                      enctype="multipart/form-data"
                      class="form"
                      method="post"
                      action="{{ adminUrl(array('controller'=>'student','action'=>'import_result')) }}">
                    @csrf
                    <input type="hidden" id="course_id" name="course_id" value="{{ $id }}">
                    <div class="form-group" style="padding-bottom: 10px">
                        <label for="file">{{ __lang('csv-file') }}</label>
                        <input required="required" name="file" type="file"/>
                    </div>
                    <button class="btn btn-primary" type="submit">{{ __lang('import') }}</button>
                </form>
            </div>
        </div>


        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ __lang('id') }}</th>
                <th>{{ __lang('name') }}</th>
                <th>{{ __lang('email') }}</th>
                <th>RegID</th>
                <th>{{ __lang('classes-attended') }}</th>
                <th>{{ __lang('progress') }}</th>
                <th>{{ __lang('enrollment-code') }}</th>
                <th>Results</th>
                <th>CGPA</th>{{--
                <th>Results Description</th>--}}
                <th>Results</th>
                <th>Login</th>
                <th>{{__lang('actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @php foreach($paginator as $row):  @endphp

            <tr>
                <td><span class="label label-success">{{ $row->student_id }}</span></td>
                <td>{{ $row->name }} {{ $row->last_name }}</td>
                <td>{{ $row->email }}</td>
                <td align="left">
                    @php
                        $std = App\User::find($row->user_id);
                        $reg_year = date('y-m',strtotime($std->created_at));
                        $reg_number = 'WA-'.$reg_year.'-'.str_pad($row->user_id,4,"0",STR_PAD_LEFT);
                    @endphp
                    {{ $reg_number }}
                </td>
                <td><strong>@php $attended= $attendanceTable->getTotalDistinctForStudentInSession($row->student_id,$id); echo $attended @endphp</strong>

                </td>
                <td>

                    <div class="text-center">
                        <small>
                            @php
                                $percent = 100 * @($attended/($totalLessons));
$percent = round($percent, 2);
                                if($percent >=0 ){
                                    echo $percent;
                                }
                                else{
                                    echo 0;
                                    $percent = 0;
                                }

                            @endphp%
                        </small>

                        <div class="progress progress_sm">
                            <div class="progress-bar bg-green"
                                 role="progressbar"
                                 data-transitiongoal="{{ $percent }}"
                                 style="width: {{ $percent }}%;"
                                 aria-valuenow="{{ $percent }}">
                            </div>
                        </div>

                    </div>
                </td>
                <td>
                    {{ $row->reg_code }}
                </td>
                <td>
                    Garde: <b>{{ $row->result_grade }}</b>
                    <br>
                    Certificate No: <b>{{ $row->result_certificate_number }}</b>
                    <br>
                    Passing Year: <b>{{ $row->result_passing_year }}</b>
                </td>
                <td>
                    {!! $row->result_cgpa !!}
                </td>{{--
                <td>
                    {!! $row->result_description !!}
                </td>--}}

                <td>
                    <a href="{{ adminUrl(array('controller'=>'session','action'=>'result_publications','id'=>$row->id)) }}"
                       class="btn btn-xs btn-{{ $row->result_description == '' ? 'danger' : 'primary' }} btn-equal"
                       data-toggle="tooltip"
                       data-placement="top"
                       data-original-title="{{ __lang('student_result_publication') }}">
                        {{ $row->result_description == '' ? 'Prepare' : 'Available' }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('impersonate.impersonate',$row->user_id) }}">Login as {{ $row->name }}</a>
                </td>
                <td>
                    <a href="{{ adminUrl(array('controller'=>'session','action'=>'stats','id'=>$row->id)) }}"
                       class="btn btn-xs btn-primary btn-equal"
                       data-toggle="tooltip"
                       data-placement="top"
                       data-original-title="{{ __lang('student-progress') }}">
                        <i class="fa fa-chart-bar"></i>
                    </a>
                    <a href="{{ adminUrl(array('controller'=>'session','action'=>'manage_class','id'=>$row->id)) }}"
                       class="btn btn-xs btn-primary btn-equal"
                       data-toggle="tooltip"
                       data-placement="top"
                       data-original-title="{{ __lang('student-manage-class') }}">
                        <i class="fa fa-cogs"></i>
                    </a>
                    <a data-toggle="tooltip"
                       data-placement="top"
                       data-original-title="{{ __lang('Un-enroll') }}"
                       onclick="return confirm('Are you sure you want to unenroll this student ?')"
                       href="{{ adminUrl(array('controller'=>'student','action'=>'unenroll','id'=>$row->student_id)) }}?session={{ $id }}"
                       class="btn btn-xs btn-primary btn-equal">
                        <i class="fa fa-minus"></i>
                    </a>
                    <button data-id="{{ $row->student_id }}"
                            data-toggle="modal"
                            data-target="#simpleModal"
                            title="Student Details"
                            type="button"
                            class="btn btn-xs btn-primary btn-equal viewbutton">
                        <i class="fa fa-user"></i>
                    </button>
                    <a href="{{ adminUrl(array('controller'=>'student','action'=>'edit','id'=>$row->student_id)) }}"
                       class="btn btn-xs btn-primary btn-equal"
                       data-toggle="tooltip"
                       data-placement="top"
                       data-original-title="{{ __lang('edit-student') }}">
                        <i class="fa fa-edit"></i>
                    </a>

                </td>
            </tr>
            @php endforeach;  @endphp

            </tbody>
        </table>

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
                array(
                    'route' => 'admin/default',
                    'controller'=>'student',
                    'action'=>'sessionstudents',
                    'id'=>$id
                )
            );
        @endphp
    </div><!--end .col-lg-12 -->



@endsection

@section('footer')

    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="simpleModalLabel">{{ __lang('student-details') }}</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

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
        $(function () {
            $('.viewbutton').click(function () {
                $('#info').text('Loading...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}' + '/' + id);
            });
        });
    </script>
@endsection
