@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('/admin/partner_mark/index')=>__lang('mark-management'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('content')
    <div ng-app="myApp" ng-controller="myCtrl">
        <div>
            <div class="card">

                <div class="card-body">
{{--                    @if($markTable->status == 'Draft' || $markTable->status == 'Back to Partner')--}}
                        <form method="post" id="edited_marks" style="display: none">
                            <input type="text" id="edit_status" name="edit_status" value="{{ $markTable->status }}">
                            <table class="table table-bordered table-striped table-condensed"
                                   id="example">
                                @foreach($markDistributionTable as $markD)
                                    <tr>
                                        <td id="">{{ $loop->iteration }}</td>
                                        <td id="">{{ $markD->student_id }}</td>
                                        <td id="">
                                            <input type="number"
                                                   id="edit_mark_distributions_id_{{ $markD->student_id }}"
                                                   class="form-control required"
                                                   name="edit_mark_distributions_id[{{ $markD->student_id }}]"
                                                   value="{{ $markD->id }}">
                                            <input type="number"
                                                   id="edit_attendance_mark_{{ $markD->student_id }}"
                                                   class="form-control required"
                                                   name="edit_attendance_mark[{{ $markD->student_id }}]"
                                                   value="{{ $markD->attendance_mark }}">
                                        </td>
                                        <td id="">
                                            <input type="number"
                                                   id="edit_assignment_mark_{{ $markD->student_id }}"
                                                   class="form-control required"
                                                   name="edit_assignment_mark[{{ $markD->student_id }}]"
                                                   value="{{ $markD->assignment_mark }}">
                                        </td>
                                        <td id="">
                                            <input type="number"
                                                   id="edit_assessment_mark_{{ $markD->student_id }}"
                                                   class="form-control required"
                                                   name="edit_assessment_mark[{{ $markD->student_id }}]"
                                                   value="{{ $markD->assessment_mark }}">
                                        </td>
                                        <td id="">
                                            <input type="text"
                                                   id="distribution_status_{{ $markD->student_id }}"
                                                   class="form-control required"
                                                   name="distribution_status[{{ $markD->student_id }}]"
                                                   value="{{ $markD->distribution_status }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </form>

                        <form method="post" action="{{ adminUrl(array('controller'=>'partnermark','action'=>$action,'id'=>$id)) }}">
                        @csrf


                        <div class="form-group">
                            <label for="filter">Course</label>
                            <select id="course_id"
                                    name="course_id"
                                    class="form-control required"
                                    required
                                    onchange="show_mark_distribution_table()">
                                <option value="">Select an option</option>
                                @foreach($sessions as $session)
                                    @php
                                        if($session->id == $markTable->course_id){
                                            $selected = 'selected="selected"';
                                        }else{
                                            $selected = '';
                                        }
                                    @endphp
                                    <option value="{{ $session->id }}" {{ $selected }}>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="search_result">

                        </div>

                        <div class="form-footer marks_form" style="display: none;">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </form>
                    {{--@else
                        <div class="alert alert-danger">
                            Edit mode is disabled for this course.
                        </div>
                    @endif--}}
                </div>
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>

@endsection

@section('footer')
    <script type="text/javascript">

        var show_mark_distribution_table = function (){
            var course_id = $('#course_id').val();
            if(course_id == ''){
                $('.marks_form').hide();
                $("#search_result").html('');
            }else{
                var edit_attendance_mark = $('[name="edit_attendance_mark[]"]').map(function () {
                    return this.value; // $(this).val()
                }).get();
                console.log(edit_attendance_mark)
                var edit_assignment_mark = $('[name="edit_assignment_mark[]"]').map(function () {
                    return this.value; // $(this).val()
                }).get();
                var edit_assessment_mark = $('[name="edit_assessment_mark[]"]').map(function () {
                    return this.value; // $(this).val()
                }).get();

                var dataString = $('#edited_marks').serialize();

                $.ajax({
                    url: './course/students/'+course_id,
                    dataType: 'html',
                    data: dataString,
                    success: function(data) {
                        $("#search_result").html(data);
                        $('.marks_form').show();
                    }
                });
            }
        }

        var basePath = '{{ basePath() }}';

        show_mark_distribution_table();
    </script>

@endsection
