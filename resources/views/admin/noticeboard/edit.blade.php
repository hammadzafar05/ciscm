@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.noticeboard.index')=>__lang('noticeboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
    <div ng-app="myApp" ng-controller="myCtrl">
        <div>
            <div class="card">
                <div class="card-body">
                    <form method="post"
                          enctype="multipart/form-data"
                          action="{{  adminUrl(array('controller'=>'noticeboard','action'=>'edit','id'=>$id)) }}">
                        @csrf
                        <div class="form-group">
                            <label for="schedule_time">Display until date</label>
                            <input name="last_date_to_display"
                                   type="text"
                                   class="form-control schedule_time"
                                   value="{{ @$row->last_date_to_display }}"
                                   id="schedule_time"
                                   autocomplete="off"
                                   >
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select id="type"
                                    name="type"
                                    class="form-control required"
                                    required
                                    onchange="show_options()">
                                <option value="Student" {{ @$row->type == 'Student' ? 'selected' : '' }}>By Student</option>
                                <option value="Course" {{ @$row->type == 'Course' ? 'selected' : '' }}>By Course</option>
                            </select>
                        </div>
                        <div class="form-group course_form">
                            <label>Course Lists</label>
                            <select id="course_id"
                                    name="course_id"
                                    class="form-control select2">
                                <option value="">Select an option</option>
                                @php
                                $course_id = @$row->course_id;
                                @endphp
                                @foreach($courses as $course)
                                    <option value="{{ $course['id'] }}" {{ @$row->course_id == $course['id'] ? 'selected' : '' }}>{{ $course['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group course_form">
                            <label>Student Lists by Course</label>
                            <select id="student_by_courses"
                                    name="student_by_courses[]"
                                    class="form-control select2"
                                    multiple>
                                <option value="">Select an option</option>
                                @php
                                    $_student_by_courses = @$row->student_by_courses;
                                    $_student_by_courses = explode(',',$_student_by_courses);
                                @endphp
                                @foreach($student_by_courses as $student)
                                    <option value="{{ $student['user_id'] }}"
                                            class='{{ $student['session_id'] }}'
                                            data-subtext='{{ $student['email'] }}'
                                            @php
                                                if (in_array($student['user_id'],$_student_by_courses)){
	                                                echo 'selected="selected"';
                                                }
                                            @endphp
                                    >{{ $student['name'] }} - {{ $student['email'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group student_form">
                            <label>Student Lists</label>
                            <select id="students"
                                    name="students[]"
                                    class="form-control"
                                    multiple
                                    data-selected-text-format="count>2"
                                    data-actions-box="true">
                                @php
                                    $students = @$row->students;
                                    $students = explode(',',$students);
                                @endphp
                                @foreach($all_students as $student)
                                    <option value="{{ $student->user_id }}"
                                            data-subtext='{{ $student->email }}'
                                            @php
                                            if (in_array($student->user_id,$students)){
                                                echo 'selected="selected"';
                                            }
                                            @endphp
                                    >{{ $student->name.' '.$student->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __lang('title') }}</label>
                            <input name="title" class="form-control" type="text" value="{{ @$row->title }}"/>

                        </div>


                        <div class="form-group">
                            <label>{{ __lang('Description') }}</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="10">{{ @$row->message }}</textarea>
                        </div>


                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </form>
                </div>
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>

@endsection

@section('header')
    <link rel="stylesheet"
          href="{{ asset('client/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"
          integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <style type="text/css">
        .student_form .form-control{
            padding: 0px !important;
        }
        .bootstrap-select>.dropdown-toggle {
            font-size: 14px;
            padding: 10px 15px;
            height: 42px;
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .input-group-text, select.form-control:not([size]):not([multiple]), .form-control:not(.form-control-sm):not(.form-control-lg) {
            padding: 0px 15px;
        }
        .btn-light:hover, .btn-light:focus, .btn-light:active, .btn-light.disabled:hover, .btn-light.disabled:focus, .btn-light.disabled:active {
            background-color: #fff !important;
        }
    </style>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/jquery.chained.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
    <script type="text/javascript"
            src="{{ asset('client/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        $('.schedule_time').datetimepicker({
            sideBySide: true,
            format: "YYYY-MM-DD"
        });

        var show_options = function(){
            var type = $('#type').val();
            if(type == 'Course'){
                $('.course_form').show();
                $('.student_form').hide();
            }else{
                $('.course_form').hide();
                $('.student_form').show();
            }
        }
        show_options();
        $('select#students').selectpicker({
            caretIcon: 'fa fa-menu-down',
            liveSearch: true,
        });
        $("#student_by_courses").chained("#course_id");

        CKEDITOR.replace('message', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>

    <script>
        var basePath = '{{ basePath() }}';
    </script>

@endsection
