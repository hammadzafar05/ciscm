@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.blog-posts.index')=>__lang('blog-posts'),
            '#'=>__lang('view')
        ]])
@endsection
@section('pageTitle',__('default.blog-post').' :'.$blogpost->title)
@section('innerTitle',__('default.blog-post').' :'.$blogpost->title)

@section('content')
    <div class="card">
     <div class="card-header">
         @can('access','view_blog')
             <a href="{{ url('/admin/blog-posts') }}" ><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a> &nbsp;&nbsp;
         @endcan

         @can('access','edit_blog')
             <a href="{{ url('/admin/blog-posts/' . $blogpost->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button></a> &nbsp;&nbsp;
         @endcan

         @can('access','delete_blog')
             <form method="POST" action="{{ url('admin/blogposts' . '/' . $blogpost->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                 {{ method_field('DELETE') }}
                 {{ csrf_field() }}
                 <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>
             </form>
         @endcan
    </div>
    </div>

    <div class="card">
     <div class="card-header">
       <h4>@lang('default.title')</h4>
    </div>
    <div class="card-body">
        {{ $blogpost->title }}
    </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h4> @lang('default.content')</h4>
        </div>
        <div class="card-body">
            {!! $blogpost->content !!}
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4>@lang('default.enabled')</h4>
        </div>
        <div class="card-body">
            {{ boolToString($blogpost->enabled) }}
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4>@lang('default.cover-image')</h4>
        </div>
        <div class="card-body">
            @if(!empty($blogpost->cover_photo))
                <img src="{{ asset($blogpost->cover_photo) }}" />
            @endif
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4>@lang('default.created-by')</h4>
        </div>
        <div class="card-body">
            @if($blogpost->admin()->exists())
                {{ $blogpost->admin->user->name }} {{ $blogpost->admin->user->last_name }}
            @endif
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4>@lang('default.meta-title')</h4>
        </div>
        <div class="card-body">
            {{ $blogpost->meta_title }}
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h4>@lang('default.meta-description')</h4>
        </div>
        <div class="card-body">
            {{ $blogpost->meta_description }}
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h4>@lang('default.categories')</h4>
        </div>
        <div class="card-body">
            @foreach($blogpost->blogCategories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h4>@lang('default.published-on')</h4>
        </div>
        <div class="card-body">
            {{ $blogpost->publish_date }}
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h4>@lang('default.show_at_success_stories')</h4>
        </div>
        <div class="card-body">
            {{ boolToString($blogpost->show_at_success_stories) }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>@lang('default.show_at_accreditation_page')</h4>
        </div>
        <div class="card-body">
            {{ boolToString($blogpost->show_at_accreditation_page) }}
        </div>
    </div>

@endsection
