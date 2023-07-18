@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__('default.courses'),
            '#'=>__lang('send-message')
        ]])
@endsection

@section('content')
    <div>
        <ul class="nav nav-pills" id="myTab3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   id="home-tab3"
                   data-toggle="tab"
                   href="#home3"
                   role="tab"
                   aria-controls="home"
                   aria-selected="true">
                    <i class="fa fa-envelope"></i> {{ __lang('email') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   id="profile-tab3"
                   data-toggle="tab"
                   href="#profile3"
                   role="tab"
                   aria-controls="profile"
                   aria-selected="false">
                    <i class="fa fa-mobile"></i> {{ __lang('sms') }}
                </a>
            </li>
        </ul>
        <div class="tab-content"
             id="myTabContent2">
            <div class="tab-pane fade show active"
                 id="home3"
                 role="tabpanel"
                 aria-labelledby="home-tab3">
                <div class="card">
                    {{--<div class="card-header">
                        {{ $subTitle  }}
                    </div>--}}
                    <div class="card-body">
                        <form method="post"
                              class="form-horizontal"
                              action="{{  adminUrl(array('controller'=>'student','action'=>'mailsession','id'=>$id)) }}">
                            @csrf
                            {{--@if($students->count() > 0)--}}
                            @if($id > 0)
                                <div class="form-group">
                                    <label>{{ __lang('students') }}</label>
                                    <select id="students_by_course_for_email"
                                            class="form-control select2"
                                            name="students[]"
                                            multiple>
                                        <option></option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->email }}">{{ $student->name.' '.$student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group">
                                    <label>Email Send Type</label>
                                    <select id="email_schedule_type"
                                            name="email_schedule_type"
                                            class="form-control required"
                                            required
                                            onchange="show_sent_email_options()">
                                        <option value="Instant">Instant</option>
                                        <option value="Scheduled">Scheduled</option>
                                    </select>
                                </div>
                                <div class="form-group email_scheduled_form">
                                    <label for="schedule_time">Date</label>
                                    <input name="schedule_time"
                                           type="text"
                                           class="form-control schedule_time"
                                           value=""
                                           id="schedule_time" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select id="type"
                                            name="type"
                                            class="form-control required" required onchange="show_options()">
                                        <option value="Student">By Student</option>
                                        <option value="Course">By Course</option>
                                    </select>
                                </div>

                                <div class="form-group course_form">
                                    <label>Course Lists</label>
                                    <select id="course_id"
                                            name="course_id"
                                            class="form-control">
                                        <option value="">Select an option</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
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
                                        @foreach($student_by_courses as $student)
                                            <option value="{{ $student['email'] }}"
                                                    class='{{ $student['session_id'] }}'
                                                    data-subtext='{{ $student['email'] }}'>{{ $student['name'] }} - {{ $student['email'] }}</option>
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
                                        @foreach($all_students as $student)
                                            <option value="{{ $student->email }}"
                                                    data-subtext='{{ $student->email }}'>{{ $student->name.' '.$student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif


                            <div class="form-group">
                                <label>{{ __lang('sender-name') }}</label>
                                <input required="required" name="name" class="form-control" type="text" value="{{ $senderName }}"/>
                            </div>


                            <div class="form-group">
                                <label>{{ __lang('sender-email') }}</label>
                                <input required="required" name="senderEmail" class="form-control" type="text" value="{{ $senderEmail }}"/>

                            </div>

                            <div class="form-group">
                                <label>{{ __lang('subject') }}</label>
                                <input name="subject" class="form-control" type="text" value=""/>

                            </div>


                            <div class="form-group">
                                <label>{{ __lang('message') }}</label>
                                <textarea class="form-control" name="message" id="message" cols="30" rows="10"></textarea>
                            </div>


                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> {{ __lang('send-now') }}</button>
                            </div>
                        </form>

                    </div>
                </div>


            </div>
            <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                @php if(getSetting('sms_enabled')==1): @endphp
                <div class="card">
                    <div class="card-header">
                        {{$smsTitle}}
                    </div>
                    <div class="card-body">
                        <form class="form"
                              method="post"
                              action="{{ adminUrl( ['controller' => 'session', 'action' => 'smssession','id'=>$id]) }}">
                            @csrf
                            @if($id > 0)
                                <div class="form-group">
                                    <label>{{ __lang('students') }}</label>
                                    <select id="students_by_course_for_sms"
                                            class="form-control select2"
                                            name="sms_students[]"
                                            multiple>
                                        <option></option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->email }}">{{ $student->name.' '.$student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group">
                                    <label>SMS Send Type</label>
                                    <select id="sms_schedule_type"
                                            name="sms_schedule_type"
                                            class="form-control required"
                                            required
                                            onchange="show_sent_sms_options()">
                                        <option value="Instant">Instant</option>
                                        <option value="Scheduled">Scheduled</option>
                                    </select>
                                </div>
                                <div class="form-group scheduled_form">
                                    <label for="schedule_time">Date</label>
                                    <input name="schedule_time"
                                           type="text"
                                           class="form-control schedule_time"
                                           value=""
                                           id="schedule_time" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Type</label>
                                    <select id="sms_type"
                                            name="sms_type"
                                            class="form-control required" required onchange="show_sms_options()">
                                        <option value="Student">By Student</option>
                                        <option value="Course">By Course</option>
                                    </select>
                                </div>

                                <div class="form-group sms_course_form">
                                    <label>Course Lists</label>
                                    <select id="sms_course_id"
                                            name="sms_course_id"
                                            class="form-control">
                                        <option value="">Select an option</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group sms_course_form">
                                    <label>Student Lists by Course (SMS)</label>
                                    <select id="sms_student_by_courses"
                                            name="sms_student_by_courses[]"
                                            class="form-control select2"
                                            multiple>
                                        <option value="">Select an option</option>
                                        @foreach($student_by_courses as $student)
                                            <option value="{{ $student['mobile_number'] }}"
                                                    class='{{ $student['session_id'] }}'
                                                    data-subtext='{{ $student['mobile_number'] }}'>{{ $student['name'] }} - {{ $student['mobile_number'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group sms_student_form">
                                    <label>Student Lists</label>
                                    <select id="sms_students"
                                            name="sms_students[]"
                                            class="form-control"
                                            multiple
                                            data-selected-text-format="count>2"
                                            data-actions-box="true">
                                        @foreach($all_students as $student)
                                            <option value="{{ $student->mobile_number }}"
                                                    data-subtext='{{ $student->mobile_number }}'>{{ $student->name.' '.$student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif


                            <div class="form-group">
                                <label for="gateway">{{ __lang('gateway') }}</label>
                                <select required name="gateway" id="gateway" class="form-control">
                                    <option value=""></option>
                                    @foreach($gateways as $gateway)
                                        <option @if(old('gateway')==$gateway->id) selected @endif value="{{ $gateway->id }}">{{ $gateway->gateway_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="smsmessage">{{ __lang('message') }}</label>
                                <textarea required name="message" id="smsmessage" cols="30" rows="10" class="form-control">{{ old('message') }}</textarea>
                                <p>
                                    <span id="remaining">160 {{ __lang('characters-remaining') }}</span>
                                    <span id="messages">1 {{ __lang('messages') }}</span>
                                </p>
                            </div>

                            <button class="btn btn-primary" type="submit">{{ __lang('send') }}</button>
                        </form>
                    </div>
                </div>

                @php else:  @endphp
                {{ __lang('sms-disabled') }}
                . @can('access','configure_sms_gateways') {!! clean(__lang('click-to-configure',['link'=>adminUrl(array('controller'=>'smsgateway','action'=>'index'))])) !!}@endcan
                @php endif;  @endphp

            </div>
        </div>


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript"
            src="{{ asset('client/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        var show_sent_sms_options = function(){
            var type = $('#sms_schedule_type').val();
            if(type == 'Scheduled'){
                $('.scheduled_form').show();
            }else{
                $('.scheduled_form').hide();
            }
        }
        show_sent_sms_options();
        var show_sent_email_options = function(){
            var type = $('#email_schedule_type').val();
            if(type == 'Scheduled'){
                $('.email_scheduled_form').show();
            }else{
                $('.email_scheduled_form').hide();
            }
        }
        show_sent_email_options();

        $('.schedule_time').datetimepicker({
            sideBySide: true,
            format: "YYYY-MM-DD HH:ss"
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

        var show_sms_options = function(){
            var sms_type = $('#sms_type').val();
            if(sms_type == 'Course'){
                $('.sms_course_form').show();
                $('.sms_student_form').hide();
            }else{
                $('.sms_course_form').hide();
                $('.sms_student_form').show();
            }
        }
        show_sms_options();
        $('select#sms_students').selectpicker({
            caretIcon: 'fa fa-menu-down',
            liveSearch: true,
        });
        $("#sms_student_by_courses").chained("#sms_course_id");


        CKEDITOR.replace('message', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        $(document).ready(function () {
            var $remaining = $('#remaining'),
                $messages = $remaining.next();

            $('#smsmessage').keyup(function () {
                var chars = this.value.length,
                    messages = Math.ceil(chars / 160),
                    remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

                $remaining.text(remaining + ' {{ __lang('characters-remaining') }}');
                $messages.text(messages + ' {{ __lang('messages') }}');
            });
        });

    </script>

@endsection
