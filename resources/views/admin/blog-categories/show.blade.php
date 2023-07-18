@extends('layouts.admin')

@section('pageTitle',__('default.blog-category').' :'.$blogcategory->id)
@section('innerTitle',__('default.blog-category').' :'.$blogcategory->id)

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/blog-categories') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <a href="{{ url('/admin/blog-categories/' . $blogcategory->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('default.edit')</button></a>

                        <form method="POST" action="{{ url('admin/blogcategories' . '/' . $blogcategory->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('default.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('default.id')</li>
                            <li class="list-group-item">{{ $blogcategory->id }}</li>
                            <li class="list-group-item active">@lang('default.category')</li>
                            <li class="list-group-item">{{ $blogcategory->category }}</li>
                            <li class="list-group-item active">@lang('default.sort-order')</li>
                            <li class="list-group-item">{{ $blogcategory->sort_order }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
