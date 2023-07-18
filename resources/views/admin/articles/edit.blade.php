@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.articles.index')=>__lang('articles'),
            '#'=>__('default.edit')
        ]])
@endsection
@section('pageTitle',__('default.edit').' '.__('default.article').': '.$article->title)
@section('innerTitle',__('default.edit').' '.__('default.article').': '.$article->title)

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ url('/admin/articles') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/articles/' . $article->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.articles.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer')
    <script type="text/javascript" src="{{ basePath() . '/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('textcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
    </script>

@endsection
