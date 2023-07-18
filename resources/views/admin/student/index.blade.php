@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('students')
        ]])
@endsection

@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ route('admin.student.index') }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}"   name="filter" class="form-control" type="search" placeholder="{{ __lang('filter-name-email') }}" aria-label="{{ __lang('search') }}" data-width="250">


            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection

@section('content')
<div >
			<div >
				<div class="card">
					<div class="card-header">

                        @if($has_permissions_to_add_student == 1)
                        <a class="btn btn-primary float-right"
                           href="{{ adminUrl(array('controller'=>'student','action'=>'add')) }}">
                            <i class="fa fa-plus"></i> {{ __lang('add-student') }}
                        </a>
                        @endif


					</div>
					<div class="card-body">

                        <div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('id') }}</th>
                                    <th></th>
									<th>{{ __lang('first-name') }}</th>
									<th>{{ __lang('last-name') }}</th>
									<th>{{ __lang('email') }}</th>
									<th>RegID</th>
									<th>{{ __lang('courses-sessions') }}</th>
									<th></th>
									<th class="text-right1"  >{{__lang('actions')}}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span class="label label-success">{{ $row->id }}</span></td>
                                    <td>
                                        <img  class="mr-3 rounded-circle"    width="50" src="{{ profilePictureUrl($row->picture) }}" />
                                    </td>
									<td>{{ $row->name }}</td>
									<td>{{  $row->last_name }}</td>
									<td>{{  $row->email }}</td>

                                    <td align="left">
                                        @php
                                            $std = App\User::find($row->user_id);
                                            $reg_year = date('y-m',strtotime($std->created_at));
                                            $reg_number = 'WA-'.$reg_year.'-'.str_pad($row->user_id,4,"0",STR_PAD_LEFT);
                                        @endphp
                                        {{ $reg_number }}
                                    </td>
									<td><strong>{{ $studentSessionTable->getTotalForStudent($row->id) }}</strong></td>
                                    <td>
                                        <a href="{{ route('impersonate.impersonate',$row->user_id) }}">Login as {{ $row->name }}</a>
                                    </td>
									<td >
										<a href="{{ adminUrl(array('controller'=>'student','action'=>'edit','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                                        <a href="#" onclick="openModal('{{__lang('enroll')}}','{{ adminUrl(array('controller'=>'student','action'=>'enroll','id'=>$row->id)) }}')"  data-toggle="tooltip" data-placement="top" data-original-title="Enroll"   title="{{ __lang('Enroll') }}" type="button" class="btn btn-xs btn-primary btn-equal"  ><i class="fa fa-plus"></i></a>

                                        <button   data-id="{{ $row->id }}" data-toggle="modal" data-target="#simpleModal" title="@lang('default.view')" type="button" class="btn btn-xs btn-primary btn-equal viewbutton"  ><i class="fa fa-eye"></i></button>
                                        @if($has_permissions_to_delete_student == 1)
										<a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'student','action'=>'delete','id'=>$row->id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                                            @endif
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
		 'controller'=>'student',
		 'action'=>'index',
         'filter'=>$filter
     )
 );
 @endphp
					</div><!--end .box-body -->
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>


@endsection

@section('footer')
    <div class="modal fade" id="simpleModal"  tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __lang('student-details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="info">
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('Loading...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
            });
        });
    </script>
@endsection

