@extends('layouts.admin')

@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.articles.index')=>__lang('articles'),
            '#'=>__('default.view')
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
                        <form method="POST" action="{{ url('admin/articles' . '/' . $article->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        @can('access','view_articles')
                        <a href="{{ url('/admin/articles') }}"  ><button  type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        @endcan

                        @can('access','edit_article')
                        <a href="{{ url('/admin/articles/' . $article->id . '/edit') }}"  ><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button></a>
                        @endcan

                        @can('access','delete_article')

                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>

                        @endcan
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('default.id')</li>
                            <li class="list-group-item">{{ $article->id }}</li>
                            <li class="list-group-item active">@lang('default.title')</li>
                            <li class="list-group-item">{{ $article->title }}</li>
                            <li class="list-group-item active">@lang('default.content')</li>
                            <li class="list-group-item">{!! $article->content !!}</li>
                            <li class="list-group-item active">@lang('default.sort-order')</li>
                            <li class="list-group-item">{{ $article->sort_order }}</li>
                            <li class="list-group-item active">@lang('default.meta-title')</li>
                            <li class="list-group-item">{{ $article->meta_title }}</li>
                            <li class="list-group-item active">@lang('default.meta-description')</li>
                            <li class="list-group-item">{{ $article->meta_description }}</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
