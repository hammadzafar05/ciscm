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
                <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'download','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('create-download') }}</a>



            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('id') }}</th>
                        <th>{{ __lang('name') }}</th>
                        <th>{{ __lang('files') }}</th>
                        <th>{{ __lang('enabled') }}</th>
                        @php if(GLOBAL_ACCESS): @endphp
                        <th>{{ __lang('created-by') }}</th>
                        @php endif;  @endphp
                        <th class="text-right1" style="width:130px">{{ __lang('actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $fileTable->getTotalForDownload($row->id) }}</td>
                            <td>{{ boolToString($row->enabled) }}</td>
                            @php if(GLOBAL_ACCESS): @endphp
                                <td>{{ adminName($row->admin_id) }}</td>
                            @php endif;  @endphp
                            <td>
                                 <div class="button-group dropup">
                                                       <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                          <i class="fa fa-cogs"></i>          {{ __lang('actions') }}
                                                       </button>
                                                       <div class="dropdown-menu wide-btn">
                                                           <a href="{{ adminUrl(array('controller'=>'download','action'=>'edit','id'=>$row->id)) }}" class="dropdown-item" ><i class="fa fa-edit"></i> {{__lang('edit')}}</a>

                                                           <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'download','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{__lang('delete')}}</a>
                                                           <a  class="dropdown-item" href="{{ adminUrl(array('controller'=>'download','action'=>'duplicate','id'=>$row->id)) }}"  ><i class="fa fa-copy"></i> {{ __lang('duplicate') }}</a>

                                                       </div>
                                                     </div>

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
                        'controller'=>'download',
                        'action'=>'index',
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
