@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.blog-posts.index')=>__lang('blog-posts'),
            '#'=>__lang('edit')
        ]])
@endsection
@section('pageTitle',__('default.edit').' '.__('default.blog-post').': '.$blogpost->title)
@section('innerTitle',__('default.edit').' '.__('default.blog-post').' #'.$blogpost->id)

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ url('/admin/blog-posts') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/blog-posts/' . $blogpost->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.blog-posts.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')

    <link href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('client/vendor/pickadate/themes/default.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/vendor/select2/css/select2.min.css') }}">
@endsection

@section('footer')

    <script src="{{ asset('client/vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('client/app/blog.js') }}"></script>
    <script type="text/javascript" src="{{ basePath() . '/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('textcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
    </script>
@endsection
