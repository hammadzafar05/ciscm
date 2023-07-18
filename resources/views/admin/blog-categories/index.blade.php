@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__('default.blog-categories')
        ]])
@endsection

@section('pageTitle',__('default.blog-categories'))
@section('innerTitle',__('default.manage-categories'))

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ url('/admin/blog-categories/create') }}" class="btn btn-success btn-sm" title="@lang('default.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('default.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('default.category')</th><th>@lang('default.sort-order')</th><th>@lang('default.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($blogcategories as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->sort_order }}</td>
                                        <td>
                                            <form  method="POST" action="{{ url('/admin/blog-categories' . '/' . $item->id) }}" accept-charset="UTF-8" >

                                            <a class="btn btn-info btn-sm" href="{{ url('/admin/blog-categories/' . $item->id) }}" title="@lang('default.view')"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</a> &nbsp;
                                            <a  class="btn btn-primary btn-sm" href="{{ url('/admin/blog-categories/' . $item->id . '/edit') }}" title="@lang('default.edit')"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</a>&nbsp;

                                            {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $blogcategories->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
