@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.lesson.index')=>__lang('classes'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
			<div >
				<div class="card">
					<div class="card-header">
						<header></header>

                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addlecture">
                            <i class="fa fa-plus"></i> {{ __lang('add-lecture') }}
                        </button>

					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('sort-order') }}</th>
									<th>{{ __lang('name') }}</th>
                                    <th>{{ __lang('content') }}</th>
                                    <th>{{ __lang('downloads') }}</th>
									<th class="text-right1" >{{ __lang('actions') }}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span class="label label-success">{{ $row->sort_order }}</span></td>
								  	<td>{{ $row->title }}</td>

                                    <td><a style="text-decoration: underline" href="{{ adminUrl(['controller'=>'lecture','action'=>'content','id'=>$row->id]) }}">{{ $lecturePageTable->getTotalLecturePages($row->id) }} {{ __lang('items') }}</a></td>

                                    <td>{{ $lectureFileTable->getTotalForDownload($row->id) }} {{ __lang('files') }}</td>

									<td class="text-right1">
                                        <a href="{{ adminUrl(array('controller'=>'lecture','action'=>'content','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('manage-content') }}"><i class="fa fa-file-video"></i></a>
										<a href="{{ adminUrl(array('controller'=>'lecture','action'=>'edit','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                                        <a href="{{ adminUrl(array('controller'=>'lecture','action'=>'files','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('manage-downloads') }}"><i class="fa fa-download"></i></a>
                                        <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'lecture','action'=>'delete','id'=>$row->id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
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
		 'controller'=>'lecture',
		 'action'=>'index',
		 'id'=>$id
     )
 );
 @endphp
					</div><!--end .box-body -->
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>



@endsection

@section('footer')
    <div class="modal fade" tabindex="-1" role="dialog" id="addlecture">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'add','id'=>$lesson->id]) }}">
@csrf
                    <div class="modal-header">
                    <h5 class="modal-title">{{ __lang('add-lecture-to') }} {{ $lesson->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">{{ __lang('lecture-title') }}</label>
                                <input name="title" class="form-control " required="required" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>                            <input name="sort_order" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>

                            </div>
                        </div>
                  <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                  </div>
                    </form>
                </div>
              </div>
            </div>

@endsection
