@extends('layouts.admin')


@section('pageTitle',__('default.administrators'))
@section('innerTitle',__('default.administrators'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('administrators')
        ]])
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ url('/admin/admins/create') }}" class="btn btn-success btn-sm" title="@lang('default.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('default.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('default.name')</th><th>@lang('default.email')</th><th>@lang('default.enabled')</th>
                                        <th>@lang('default.role')</th>
                                        <th></th>
                                        <th>@lang('default.actions')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }} {{ $item->last_name }}</td><td>{{ $item->email }}</td><td>{{ boolToString($item->enabled) }}</td>
                                        <td>
                                            @if($item->admin)
                                            {{ $item->admin->adminRole->name }}
                                            @endif

                                        </td>
                                        <td>
                                            <a href="{{ route('impersonate.impersonate',$item->id) }}">Login as {{ $item->name }}</a>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ url('/admin/admins' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">

                                            <a href="{{ url('/admin/admins/' . $item->id) }}" title="@lang('default.view')"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</button></a>
                                            <a href="{{ url('/admin/admins/' . $item->id . '/edit') }}" title="@lang('default.edit')"><button  type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button></a>

                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $admins->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

