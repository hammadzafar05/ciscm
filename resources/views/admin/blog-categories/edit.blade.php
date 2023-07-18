@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.blog-categories.index')=>__('default.blog-categories'),
            '#'=>__lang('edit')
        ]])
@endsection
@section('pageTitle',__('default.edit').' '.__('default.blog-category').' #'.$blogcategory->id)
@section('innerTitle',__('default.edit').' '.__('default.blog-category').' #'.$blogcategory->id)

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ url('/admin/blog-categories') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/blog-categories/' . $blogcategory->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.blog-categories.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
