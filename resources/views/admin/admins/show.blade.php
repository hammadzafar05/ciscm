@extends('layouts.admin')

@section('pageTitle',__('default.administrator').': '.$admin->name)
@section('innerTitle',__('default.administrator').': '.$admin->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.admins.index')=>__lang('administrators'),
            '#'=>__lang('view')
        ]])
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <form method="POST" action="{{ url('admin/admins' . '/' . $admin->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        <a href="{{ url('/admin/admins') }}" title="Back"><button type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <a href="{{ url('/admin/admins/' . $admin->id . '/edit') }}" ><button type="button"  class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('default.edit')</button></a>


                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('default.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('default.id')</li>
                            <li class="list-group-item">{{ $admin->id }}</li>
                            <li class="list-group-item active">@lang('default.name')</li>
                            <li class="list-group-item">{{ $admin->name }} {{ $admin->last_name }}</li>
                            <li class="list-group-item active">@lang('default.email')</li>
                            <li class="list-group-item">{{ $admin->email }}</li>
                            <li class="list-group-item active">@lang('default.enabled')</li>
                            <li class="list-group-item">{{ boolToString($admin->enabled) }}</li>

                            <li class="list-group-item active">@lang('default.role')</li>
                            <li class="list-group-item">
                                {{ $admin->admin->adminRole->name }}

                            </li>

                            <li class="list-group-item active">@lang('default.about')</li>
                            <li class="list-group-item">{{ $admin->admin->about }}</li>

                            <li class="list-group-item active">@lang('default.notifications')</li>
                            <li class="list-group-item">{{ boolToString($admin->admin->notify) }}</li>

                            <li class="list-group-item active">@lang('default.public')</li>
                            <li class="list-group-item">{{ boolToString($admin->admin->public) }}</li>

                            <li class="list-group-item active">@lang('default.facebook')</li>
                            <li class="list-group-item">{{ $admin->admin->social_facebook }}</li>

                            <li class="list-group-item active">@lang('default.twitter')</li>
                            <li class="list-group-item">{{ $admin->admin->social_twitter }}</li>

                            <li class="list-group-item active">@lang('default.linkedin')</li>
                            <li class="list-group-item">{{ $admin->admin->social_linkedin }}</li>

                            <li class="list-group-item active">@lang('default.instagram')</li>
                            <li class="list-group-item">{{ $admin->admin->social_instagram }}</li>

                            <li class="list-group-item active">@lang('default.website')</li>
                            <li class="list-group-item">{{ $admin->admin->social_website }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
