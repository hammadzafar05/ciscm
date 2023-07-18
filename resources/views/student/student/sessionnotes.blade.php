@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.student.notes')=>__lang('revision-notes'),
            '#'=>__lang('course-notes')
        ]])
@endsection

@section('content')

            <!--container starts-->
           <div class="container box"  >
           	<!--primary starts-->

    		<div class="card-body">
                <div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									 <th>{{  __lang('Title')  }}</th>
                                    <th>{{  __lang('Class')  }}</th>
									<th>{{  __lang('Created On')  }}</th>
									<th   >{{  __lang('Actions')  }}</th>
								</tr>
							</thead>
							<tbody>
                            @php  foreach($paginator as $row):  @endphp
								<tr>
									 <td>{{  $row->title }}
                                     @php  if(!empty($row->description)): @endphp
                                            <p><small>{{  limitLength($row->description,200) }}</small></p>
                                    @php  endif;  @endphp
                                     </td>
                                    <td>{{  $row->lesson_name }}</td>
									<td>{{  showDate('d/M/Y',$row->created_at) }}</td>


									<td  >
										<a href="{{  route('student.student.viewnote',array('id'=>$row->id)) }}" class="btn btn-primary"  ><i class="fa fa-eye"></i> {{  __lang('View')  }}</a>

								 	</td>
								</tr>
								  @php  endforeach;  @endphp

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
     route('student.student.sessionnotes',['id'=>$id])

 );
  @endphp
					</div><!--end .box-body -->



           </div>

             <!--container ends-->

@endsection
