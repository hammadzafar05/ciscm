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
                          <a class="btn btn-primary float-right"
							 href="{{ adminUrl(array('controller'=>'noticeboard','action'=>'add')) }}"><i class="fa fa-plus"></i> Add Notice</a>

					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
                                    <th>Display until date</th>
									<th>{{ __lang('Type') }}</th>
                                    <th>{{ __lang('Title') }}</th>
									<th>{{ __lang('created-on') }}</th>
									<th>{{ __lang('actions') }}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td>{{ showDate('d/m/Y',$row->last_date_to_display) }}</td>
									<td>{{ $row->type }}</td>
									<td>{{ $row->title }}</td>
									<td>{{ showDate('d/m/Y',$row->created_at) }}</td>
									<td  >
                                        <div class="btn-group dropleft">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __lang('actions') }}
                                            </button>
                                            <div class="dropdown-menu dropleft wide-btn">
                                                <a href="{{ adminUrl(array('controller'=>'noticeboard','action'=>'edit','id'=>$row->id)) }}" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title=""><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>

                                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'noticeboard','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
                                               {{-- <a onclick="openModal('{{__lang('homework-info')}}','{{ adminUrl(['controller'=>'assignment','action'=>'view','id'=>$row->id]) }}')" href="#" class="dropdown-item"  ><i class="fa fa-info"></i> {{ __lang('info') }}</a>--}}

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
		 'controller'=>'assignment',
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

<script type="text/javascript">
$(function(){
	$('.viewbutton').click(function(){
		 $('#info').text('Loading...');
		 var id = $(this).attr('data-id');
        $('#info').load('{{ url('admin/assignment/view')  }}'+'/'+id);
		});
	});
</script>
@endsection
