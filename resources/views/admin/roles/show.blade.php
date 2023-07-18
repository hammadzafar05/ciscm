@extends('layouts.admin')

@section('pageTitle',__('default.role').': '.$role->name)
@section('innerTitle',__('default.role').': '.$role->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.roles.index')=>__lang('roles'),
            '#'=>__lang('show')
        ]])
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div  >
                    <div  >
                        <form method="POST" action="{{ url('admin/roles' . '/' . $role->id) }}" accept-charset="UTF-8" class="int_inlinedisp">

                        <a href="{{ url('/admin/roles') }}" title="@lang('default.back')"><button type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <a href="{{ url('/admin/roles/' . $role->id . '/edit') }}" ><button type="button"  class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('default.edit')</button></a>

                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('default.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr><th> @lang('default.name') </th><td> {{ $role->name }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                        <h1>@lang('default.permissions')</h1>

                        @foreach(\App\PermissionGroup::orderBy('sort_order')->get() as $group)
                            @if($role->permissions()->where('permission_group_id',$group->id)->count()>0)
                            <div class="card">
                                <div class="card-header">
                                    @lang('default.'.$group->name)
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($group->permissions as $permission)
                                            @if($role->permissions()->find($permission->id))
                                        <li class="list-group-item">@lang('perm.'.$permission->name)</li>
                                            @endif
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            @endif
                      @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
