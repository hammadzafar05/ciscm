@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>$title
        ]])
@endsection

@section('content')
<div>
			<div >
				<div class="card">
					<div class="card-header">
						<header>{{$title}}</header>
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
                                    <th>ID</th>
                                    <th>{{ __lang('title') }}</th>
									<th>{{ __lang('session-course') }}</th>
                                    <th>{{ __lang('type') }}</th>
									<th>{{ __lang('created-on') }}</th>
                                    <th>{{ __lang('opening-date') }}</th>
                                    <th>{{ __lang('due-date') }}</th>
                                    <th>{{ __lang('submissions') }}</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
                            
                            
                            <?php
                            $getAssignment=App\Assignment::get()->pluck('id');
                                $getEditable=App\AssignmentSubmission::whereIn('assignment_id',$getAssignment)->where('editable',1)->orderBy("id", "desc")->paginate(100);
                               
                                ?>
                         
                            @foreach($getEditable as $key=>$row)
                            <?php
                              
                            ?>
								<tr>
									<td>{{ $row->id }}</td>
									<td>{{ $row->assignment->title??"" }}</td>
                                    <td><span >{{ $row->assignment->course->name ??""}}</span></td>
                                    <td>@if($row->assignment->schedule_type=='s') scheduled @elseif($row->assignment->schedule_type=='c') post-class @else Individual @endif</td>
									<td>{{ showDate('d/m/Y',$row->assignment->created_at) }}</td>
                                    <td>{{ showDate('d/m/Y',$row->assignment->opening_date) }}</td>
                                    <td>{{ showDateTime('d/m/Y h:i A',$row->assignment->due_date) }}</td>
								    <td>
								      
                                         <a class="btn btn-primary btn-sm" href="{{ adminUrl(['controller'=>'assignment','action'=>'submissions','id'=>$row->assignment->id]) }}">{{ __lang('view-all') }}</a>
                                    </td>
							        <td>
							            Pending...
							        </td>
                                   
								</tr>
						    @endforeach
							</tbody>
							
							 
						</table>
						<div>{{$getEditable->links()}}</div>
					</div><!--end .box-body -->
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
			
		</div>
	

 
        <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->
 <!-- Modal for Eligible-->

@endsection
