@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')



<div class="card">
    <div class="card-header">
        <h2>{{ $row->course_name }}</h2>
    </div>
    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.session.result_publications',['id'=>$student_courses_id]) }}"
              accept-charset="UTF-8"
              class="form-horizontal"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden"
                   id="student_courses_id"
                   name="student_courses_id"
                   value="{{ $student_courses_id }}">
            <div class="form-group">
                <label for="result_grade" class="control-label">Results(Grade)</label>
                <input id="result_grade"
                       class="form-control"
                       name="result_grade"
                       value="{{ $row->result_grade }}"></input>
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <label for="result_certificate_number" class="control-label">Certificate Number</label>
                <input id="result_certificate_number"
                       class="form-control"
                       name="result_certificate_number"
                       value="{{ $row->result_certificate_number }}"></input>
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <label for="result_passing_year" class="control-label">Passing Year</label>
                <input id="result_passing_year"
                       class="form-control"
                       name="result_passing_year"
                       value="{{ $row->result_passing_year }}"></input>
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <label for="publish_date" class="control-label">Results(CGPA)</label>
                <textarea class="form-control"
                          rows="5"
                          name="result_cgpa"
                          id="result_cgpa">{{ $row->result_cgpa }}</textarea>
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <label for="publish_date" class="control-label">Results</label>
                <textarea class="form-control"
                          rows="5"
                          name="result_description"
                          id="result_description">{{ $row->result_description }}</textarea>
                <p class="help-block"></p>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Create">
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ basePath() . '/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script>
        CKEDITOR.replace('result_description', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
        CKEDITOR.replace('result_cgpa', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
    </script>
@endsection