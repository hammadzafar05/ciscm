@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__('default.external_certificates')
        ]])
@endsection
@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ url('/admin/external_certificates') }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}"   name="filter" class="form-control" type="search" placeholder="{{ __lang('search') }}" aria-label="{{ __lang('search') }}" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection
@section('pageTitle',__('default.external_certificates'))
@section('innerTitle')
    {{ __('default.manage-external_certificates') }} ({{ $external_certificates->count() }})
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_external_certificates')
                        <a href="{{ url('/admin/external_certificates/create') }}"
                           class="btn btn-success btn-sm" title="@lang('default.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('default.add-new')
                        </a>
                        @endcan
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('default.title')</th><th>@lang('default.slug')</th>
                                        <th>@lang('default.enabled')</th>
                                        <th>{{ __lang('mobile') }}</th>
                                        <th>@lang('default.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($external_certificates as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->title }}</td><td>{{ $item->slug }}</td>
                                        <td>{{ $item->enabled==1? __('default.enabled'):__('default.disabled') }}</td>
                                        <td>
                                            {{ boolToString($item->mobile) }}
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ url('/admin/external_certificates' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            @can('access','view_external_certificates')
                                            <a href="{{ url('/admin/external_certificates/' . $item->id) }}" title="@lang('default.view')"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</button></a>
                                            @endcan

                                            @can('access','edit_article')
                                            <a href="{{ url('/admin/external_certificates/' . $item->id . '/edit') }}" title="@lang('default.edit')"><button  type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button></a>
                                            @endcan

                                            @can('access','delete_article')

                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>

                                            @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $external_certificates->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
