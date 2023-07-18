@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
<form class="form" action="{{ selfURL() }}" method="post">
    @csrf
    <div class="form-group">
        {{ formLabel($form->get('topic_title')) }}
        {{ formElement($form->get('topic_title')) }}
    </div>

    <div class="form-group">
        {{ formLabel($form->get('message')) }}
        {{ formElement($form->get('message')) }}
    </div>

    <button type="submit" class="btn btn-primary">{{  __lang('create-topic')  }}</button>
</form>


@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote-ext-emoji/src/css-new-version.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/summernote-ext-emoji/src/summernote-ext-emoji.js') }}"></script>
    <script>
        $(function(){
            document.emojiSource = '{{ url('/') }}/client/vendor/summernote-ext-emoji/pngs/';
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
