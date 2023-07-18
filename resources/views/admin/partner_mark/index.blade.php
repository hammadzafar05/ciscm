@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('header')
	<link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection


@section('footer')
	@parent
	<div class="modal fade"
		 id="modalBootstrap"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="myModalLabel"
		 aria-hidden="true"
		 data-backdrop="static"
		 data-keyboard="false">
		<div class="modal-dialog modal-xl">{{--modal-lg | modal-sm--}}
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_title" style="float: left !important;"></h5>
					<button type="button"
							class="close"
							data-dismiss="modal"
							aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">

				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.flash.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

	<script>

		$(document).on('click', 'a[data-target="#modalBootstrap"]', function(event) {
			event.preventDefault();
			var id = $(this).attr('id');
			var page_link = $(this).data('page_link');
			var modal_title = $(this).data('modal_title');
			$('#modal_title').html(modal_title);

			var modal_size = $(this).data('modal_size');
			$('.modal-dialog').addClass(modal_size);

			var myModal = $('#modalBootstrap');
			var modalBody = myModal.find('.modal-body');
			modalBody.html('<div class="text-center"><em class="fa fa-4x fa-spin fa-spinner"></em></div>');
			modalBody.load(page_link, function() {
				myModal.modal('show');
			});
		});


		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		var change_status = function(id,name,pk,table,value){

			/*var name = jQuery("select#"+id).data('name');
			alert('id-->'+id)
			var pk = jQuery("select#"+id).data('pk');
			var table = jQuery("select#"+id).data('table');
			var value = jQuery("select#"+id).val();*/

			jQuery("#typechangeLoader"+id).html('<i class="fa fa-spin fa-spinner"></i>');
			var datastring = "name="+name+"&pk="+pk+"&table="+table+"&value="+value;

			$.ajax({
				type: "POST",
				'_token': '{{ csrf_token() }}',
				url: "./editable/update_editable",
				data: datastring,
				success: function(response) {
					alert(response.message);
					jQuery("#typechangeLoader"+id).html('');
				},
				error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						alert('Not connect. Verify Network.');
					} else if (jqXHR.status == 404) {
						alert('Requested page not found. [404].');
					} else if (jqXHR.status == 500) {
						alert('Internal Server Error [500].');
					} else if (exception === 'parsererror') {
						alert('Requested JSON parse failed.');
					} else if (exception === 'timeout') {
						alert('Time out error.');
					} else if (exception === 'abort') {
						alert('Ajax request aborted.');
					} else {
						alert('Uncaught Error.' + jqXHR.responseText);
					}
					$('button[name=nameSubmitBtn]').button('reset');
				}
			});
			return false;
		}
	</script>

@endsection



@section('content')
<div>
			<div >
				<div class="card">
					<div class="card-header">
						<header></header>
						@if($admin_role == 'Partner')
                          <a class="btn btn-primary float-right"
                             href="{{ adminUrl(array('controller'=>'partnermark','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('Add Mark') }}</a>
						@endif
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('session-course') }}</th>
									<th>{{ __lang('status') }}</th>
									<th>{{ __lang('created-on') }}</th>
                                    <th>{{ __lang('created-by') }}</th>
                                    <th>{{ __lang('view') }}</th>
									<th class="text-right1" style="width:90px">{{ __lang('actions') }}</th>
									@if($admin_role != 'Partner')
										<th class="text-right1" style="width:90px">Prepare Result</th>
									@endif
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span >{{ $row->course_name }}</span></td>
									<td>{{ $row->status }}</td>
									<td>{{ showDate('d/m/Y',$row->created_at) }}</td>
                                    <td>{{ adminName($row->admin_id) }}</td>
                                    <td>
										<a href="#"
										   id="{{ $row->id }}"
										   class="btn btn-danger"
										   data-toggle="modal"
										   data-target="#modalBootstrap"
										   data-modal_size="modal-lg"
										   data-page_link="./view/{{ $row->id }}"
										   data-modal_title="{{ $row->course_name }}"
										>
											<i class="fa fa-info-circle"></i>
											{{ __lang('view') }}
										</a>

									</td>

									<td class="text-right">
										@if($admin_role == 'Partner')
											{{--@if($row->status == 'Draft' || $row->status == 'Back to Partner')

											@endif--}}
											@if($row->edit_enabled_for_distribution == 1)
												<a href="{{ adminUrl(array('controller'=>'partnermark','action'=>'edit','id'=>$row->id)) }}"
												   class="btn btn-primary">
													<i class="fa fa-edit"></i>
													{{__lang('edit')}}
												</a>
											@endif
										@else
											{{--<select id="{{ $row->id }}"
													class="form-control "
													name="mark_status"
													onchange="change_status({{ $row->id }},'status',{{ $row->id }},'marks',this.value)"
													data-name="status"
													data-type="select"
													data-pk="{{ $row->id }}"
													data-table="marks">
												<option value="Submit for Approval" {{ ($row->status == 'Submit for Approval' ? 'selected' : '') }}>Submit for Approval</option>
												<option value="Back to Partner" {{ ($row->status == 'Back to Partner' ? 'selected' : '') }}>Back to Partner</option>
												<option value="Final" {{ ($row->status == 'Final' ? 'selected' : '') }}>Final</option>
											</select>
											<span id="typechangeeLoader_{{ $row->id }}">&nbsp;</span>--}}
											@if($row->edit_enabled_for_distribution == 1)
												@if($row->status == 'Submit for Approval')
													<a href="{{ adminUrl(array('controller'=>'partnermark','action'=>'approval','id'=>$row->id)) }}"
													   class="btn btn-primary">
														<i class="fa fa-check"></i>
														Go to approve
													</a>
												@endif
											@endif
										@endif
                                    </td>
									@if($admin_role != 'Partner')
										<td class="text-right">
											<a href="{{ adminUrl(array('controller'=>'partnermark','action'=>'prepare_result','id'=>$row->id)) }}"
											   class="btn btn-primary">
												<i class="fa fa-check"></i>
												Admin Mark Distribution
											</a>
										</td>
									@endif
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
		 'controller'=>'partnermark',
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
