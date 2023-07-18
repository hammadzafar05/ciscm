@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__('default.blog-posts')
        ]])
@endsection
@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ url('/admin/blog-posts') }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}" name="filter" class="form-control" type="search" placeholder="{{ __lang('search') }}" aria-label="{{ __lang('search') }}"
                   data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection
@section('pageTitle',__('default.blog-posts'))
@section('innerTitle',__('default.blog-posts'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div>
                        @can('access','add_blog')
                            <a href="{{ url('/admin/blog-posts/create') }}" class="btn btn-success btn-sm" title="@lang('default.add-new')">
                                <i class="fa fa-plus" aria-hidden="true"></i> @lang('default.add-new')
                            </a>
                        @endcan
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('default.title')</th>
                                    <th>@lang('default.published-on')</th>
                                    <th>@lang('default.enabled')</th>
                                    <th>@lang('default.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($blogposts as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->publish_date)->format('d/M/Y') }}</td>
                                        <td>{{ boolToString($item->enabled) }}</td>
                                        <td>
                                            @if(FALSE)

                                                <div class="button-group dropup">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        {{ __lang('actions') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                    </div>
                                                </div>
                                            @endif

                                            @can('access','view_blog')
                                                <a href="{{ url('/admin/blog-posts/' . $item->id) }}" title="@lang('default.view')">
                                                    <button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</button>
                                                </a>
                                            @endcan

                                            @can('access','edit_blog')
                                                <a href="{{ url('/admin/blog-posts/' . $item->id . '/edit') }}" title="@lang('default.edit')">
                                                    <button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button>
                                                </a>
                                            @endcan

                                            @can('access','delete_blog')
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="$('#form-{{ $item->id }}').submit()"><i
                                                            class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>
                                                <form onsubmit="return confirm(&quot;@lang('default.delete-confirm')&quot;)" id="form-{{ $item->id }}" method="POST"
                                                      action="{{ url('/admin/blog-posts' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                </form>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $blogposts->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
