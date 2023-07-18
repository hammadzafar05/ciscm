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
                          <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'news','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('Add Post') }}</a>



					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('id') }}</th>
									<th>{{ __lang('title') }}</th>
									<th>{{ __lang('date') }}</th>
                                    @php if(GLOBAL_ACCESS): @endphp
                                    <th>{{ __lang('created-nu') }}</th>
                                    @php endif;  @endphp
									<th class="text-right1" style="width:90px">{{__lang('actions')}}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span class="label label-success">{{ $row->newsflash_id }}</span></td>
									<td>{{ $row->title }}</td>
									<td>{{ showDate('d/m/Y',$row->date) }}</td>


                                    @php if(GLOBAL_ACCESS): @endphp
                                        <td>{{ adminName($row->admin_id) }}</td>
                                    @php endif;  @endphp
									<td class="text-right">
										<a href="{{ adminUrl(array('controller'=>'news','action'=>'edit','id'=>$row->newsflash_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>

										<a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'news','action'=>'delete','id'=>$row->newsflash_id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
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
		 'controller'=>'news',
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
