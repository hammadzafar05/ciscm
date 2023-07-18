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

</div>
<div style="clear: both"></div>
<div>
    <div >
        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary float-right" href="{{ adminUrl(['controller'=>'discuss','action'=>'index']) }}?replied=1">{{ __lang('replied') }}</a>
                <a   class="btn btn-success float-right" href="{{ adminUrl(['controller'=>'discuss','action'=>'index']) }}?replied=0">{{ __lang('unreplied') }}</a>
                <a  class="btn btn-secondary float-right" href="{{ adminUrl(['controller'=>'discuss','action'=>'index']) }}" >{{ __lang('all') }}</a>

            </div>

            <div class="card-body">

                <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('subject') }}</th>
                        <th>{{ __lang('subject') }}</th>
                        <th>{{ __lang('created-on') }}</th>
                        <th>{{ __lang('replied') }}</th>
                        <th>{{ __lang('recipients') }}</th>
                        <th class="text-right1" style="width:90px">{{ __lang('actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>{{ $row->subject }}</td>
                            <td>{{ $row->name.' '.$row->last_name }}</td>
                            <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
                            <td>{{ boolToString($row->replied) }}</td>

                            <td>

                                @php if($row->admin==1): @endphp
                                    {{ __lang('administrators') }},
                                @php endif;  @endphp

                                @php foreach($accountTable->getDiscussionAccounts($row->id) as $row2):  @endphp
                                    {{ $row2->name.' '.$row2->last_name }},
                                @php endforeach;  @endphp



                            </td>
                            <td class="text-right">
                                 <div class="button-group dropup">
                                                       <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cogs"></i>  {{ __lang('actions') }}
                                                       </button>
                                                       <div class="dropdown-menu wide-btn">
                                                           <a href="{{ adminUrl(array('controller'=>'discuss','action'=>'viewdiscussion','id'=>$row->id)) }}" class="dropdown-item" ><i class="fa fa-eye"></i> {{ __lang('view') }}</a>

                                                           <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'discuss','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

                                                       </div>
                                                     </div>

                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>
            </div>
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
                        'controller'=>'discuss',
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
