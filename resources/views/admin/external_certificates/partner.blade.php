@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>$pageTitle
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
     ({{ $getPartner->count() }})
  
@endsection

@section('content')
     <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_external_certificates')
                        <a href="{{ url('admin/partner/import') }}"
                           class="btn btn-success btn-sm" title=" {{__lang('excel-partner')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            {{__lang('excel-partner')}}
                        </a>
                        @endcan
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Partner ID</th>
                                        <th>Name</th>
                                        <th>Website</th>
                                        <th>Country</th>
                                        <th>Valid Till</th>
                                        <th>Status</th>
                                        <th>@lang('default.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($getPartner as $key=>$item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->tracking_number }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->website }}</td>
                                        <td>{{ $item->country}}</td>
                                        <td>
                                            {{$item->issue_date }}
                                        </td>
                                         <td>
                                            @if($item->enabled==1)
                                            Active
                                            @else
                                            UnActive
                                            @endif
                                        </td>
                                        <td>
                                       @if($item->enabled==1)
                                             <a href="{{ url('/admin/external_certificates/' . $item->id) }}" title="@lang('default.view')"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</button></a>
                                             @else
                                             
                                             <a href="{{ url('/admin/external_certificates/' . $item->id) }}" title="@lang('default.view')"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('default.view')</button></a>
                                             
                                             @endif
                                            
                                              <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $getPartner->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
