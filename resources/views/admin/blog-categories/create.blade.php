@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.blog-categories.index')=>__('default.blog-categories'),
            '#'=>__lang('add')
        ]])
@endsection
@section('pageTitle',__('default.create-new').' '.__('default.blog-category'))
@section('innerTitle',__('default.create-new').' '.__('default.blog-category'))

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ url('/admin/blog-categories') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ url('/admin/blog-categories') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.blog-categories.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
