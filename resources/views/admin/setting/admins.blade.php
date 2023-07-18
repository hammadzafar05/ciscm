@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <div >
        <div class="card">
            <div class="card-header">
                <header></header>
                <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'setting','action'=>'addadmin')) }}"><i class="fa fa-plus"></i> {{ __lang('add-administrator') }}</a>



            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('name') }}</th>
                        <th>{{ __lang('email') }}</th>
                        <th>{{ __lang('role') }}</th>
                        <th class="text-right1" style="width:90px">{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>{{ $row->name }} {{ $row->last_name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->role }}</td>
                            <td class="text-right">
                                <a href="{{ adminUrl(array('controller'=>'setting','action'=>'editadmin','id'=>$row->account_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>

                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'setting','action'=>'deleteaccount','id'=>$row->account_id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>

                @php
                // add at the end of the file after the table
                echo paginationControl(
                // the paginator object
                    $paginator,
                    // the scrolling style
                    'sliding',
                    // the partial to use to render the control
                    null,
                    // the route to link to when a user clicks a control link
                    array(
                        'route' => 'admin/default',
                        'controller'=>'setting',
                        'action'=>'admins',
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->


@endsection
