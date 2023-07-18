@extends('layouts.admin')


@section('pageTitle',__lang('Roles'))
@section('innerTitle',__lang('Roles'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('Roles')
        ]])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ url('/admin/roles/create') }}" class="btn btn-success btn-sm" title="@lang('default.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('default.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('default.name')</th><th>@lang('default.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <div class="btn-group dropleft">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('default.actions')
                                                </button>
                                                <div class="dropdown-menu wide-btn">
                                                    <!-- Dropdown menu links -->

                                                    <a class="dropdown-item" href="{{ url('/admin/roles/' . $item->id) }}">@lang('default.view')</a>



                                                    <a class="dropdown-item" href="{{ url('/admin/roles/' . $item->id . '/edit') }}">@lang('default.edit')</a>



                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('default.delete')</a>




                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('default.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/roles' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $roles->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
