@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.forum.index')=> __lang('student-forum'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

<form class="form" action="{{selfURL()}}" method="post">
    @csrf
    <div class="form-group">
        {{formLabel($form->get('topic_title'))}}
        {{formElement($form->get('topic_title')) }}
    </div>
    <div class="form-group">
        {{formLabel($form->get('course_id'))}}
        <select name="course_id" id="course_id"
                class="form-control select2">
            <option value=""></option>
            @foreach($form->get('course_id')->getValueOptions() as $option)
                <option @if(old('course_id',$form->get('course_id')->getValue()) == $option['value']) selected @endif data-type="{{ $option['attributes']['data-type'] }}" value="{{ $option['value'] }}">{{$option['label']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        {{formLabel($form->get('message'))}}
        {{formElement($form->get('message')) }}
    </div>
    <button type="submit" class="btn btn-primary">{{ __lang('create-topic') }}</button>
</form>

@endsection

@section('footer')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote-ext-emoji/src/css-new-version.css') }}">
    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/summernote-ext-emoji/src/summernote-ext-emoji.js') }}"></script>
    <script>
        $(function(){
            document.emojiSource = '{{ basePath() }}/static/summernote-ext-emoji/pngs/';
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture','video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['misc', ['emoji']],
                    ['help', ['help']],
                ]
            } );
        });
    </script>
@endsection
