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
                          <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'homework','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('Add Note') }}</a>



					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('session-course') }}</th>
									<th>{{ __lang('title') }}</th>
									<th>{{ __lang('created-on') }}</th>
                                    <th>{{ __lang('created-by') }}</th>
									<th class="text-right1" style="width:90px">{{ __lang('actions') }}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span >{{ $row->course_name }}</span></td>
									<td>{{ $row->title }}</td>
									<td>{{ showDate('d/m/Y',$row->created_at) }}</td>
                                    <td>{{ adminName($row->admin_id) }}</td>

									<td class="text-right">
                                        <div class="btn-group dropup">
                                                               <button class="btn btn-primary dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               <i class="fa fa-cogs"></i>  {{ __lang('actions') }}
                                                               </button>
                                                               <div class="dropdown-menu wide-btn">
                                                                   <a href="{{ adminUrl(array('controller'=>'homework','action'=>'edit','id'=>$row->id)) }}" class="dropdown-item"  ><i class="fa fa-edit"></i> {{__lang('edit')}}</a>
                                                                   <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'homework','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{__lang('delete')}}</a>
                                                                   <a href="#" data-toggle="modal" data-target="#infoModal{{ $row->id }}" class="dropdown-item"><i class="fa fa-info-circle"></i> {{ __lang('view') }}</a>

                                                               </div>
                                         </div>

                                    </td>
								</tr>
                            @section('footer')
                            @parent
                                <div class="modal fade" tabindex="-1" role="dialog" id="infoModal{{ $row->id }}">
                                          <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title">{{ $row->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                <p>{!! clean($row->content) !!}</p>
                                              </div>
                                              <div class="modal-footer bg-whitesmoke br">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>

                                              </div>
                                            </div>
                                          </div>
                                        </div>
                            @endsection
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
		 'controller'=>'homework',
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
