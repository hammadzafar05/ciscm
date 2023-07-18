@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

  <style>
   .popup{
    background-color: #beb458;
    width: 70%;
    padding: 30px 40px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    bottom: 80%;
    border-radius: 8px;
    font-family: "Poppins",sans-serif;
    display: none;
    text-align: center;
}
.popup button{
    display: block;
    margin:  0 0 20px auto;
    background-color: transparent;
    font-size: 50px;
    color: #000;
    border: none;
    outline: none;
    cursor: pointer;
}
.popup p{
    font-size: 14px;
    text-align: justify;
    margin: 20px 0;
    line-height: 25px;
}
.popup a{
    display: block;
    width: 150px;
    position: relative;
    margin: 10px auto;
    text-align: center;
    background-color: #0f72e5;
    color: #ffffff;
    text-decoration: none;
    padding: 5px 0;
}

table {

	border :1px solid #ccc;
	border-collapse: collapse;
	padding: 0;
	margin: 0;
	width: 100%;
}

caption {

	font-size: 2em;
	 margin: .25em 0 .75em;
}

table tr {
  background: #f8f8f8;
  border: 1px solid #ccc;
  padding: .35em;
}

table th,
table td {
  padding: .625em;
  text-align: center;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
  text-transform: uppercase;
}

/* Media Queries*/

@media screen and (max-width: 600px) {
  table {
    border: 0;
  }
  table caption {
    font-size: 1.3em;
  }
  table thead {
    display: none;
  }
  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: .625em;
  }
  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: .8em;
    text-align: right;
  }
  table td:before {
  	content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }
  table td:last-child {
    border-bottom: 0;
  }
}

    
    </style>

@section('content')

  
<div>
			<div >
				<div class="card">
					<div class="card-header">
						<header></header>
                          <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'assignment','action'=>'add')) }}"><i class="fa fa-plus"></i> Add Homework</a>
                          
                          
                          <div class="card-body">
                          
                     <form method="get" enctype="multipart/form-data" action="{{url('admin/filter-by-course')}}">
                        <div class="col-md-6 col-sm-12">
                            <select name="course_id" id="course_id"
                                    class="form-control select2">
                                <option value="#">Serach By Course</option>
                                @foreach($form->get('course_id')->getValueOptions() as $option)
                                    <option @if(old('course_id',$form->get('course_id')->getValue()) == $option['value']) selected
                                            @endif data-type="{{ $option['attributes']['data-type'] }}" value="{{ $option['value'] }}">{{$option['label']}}</option>
                                @endforeach
                            </select>


                            <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>

                        </div>
                            <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        </form>
                      </div>

					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
                                    <th>{{ __lang('title') }}</th>
									<th>{{ __lang('session-course') }}</th>
                                    <th>{{ __lang('type') }}</th>
									<th>{{ __lang('created-on') }}</th>
                                    <th>{{ __lang('opening-date') }}</th>
                                    <th>{{ __lang('due-date') }}</th>
                                    <th>{{ __lang('submissions') }}</th>
                                    @php if(GLOBAL_ACCESS): @endphp
                                    <th>{{ __lang('created-by') }}</th>
                                    @php endif;  @endphp
									<th   >{{ __lang('actions') }}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td>{{ $row->title }}</td>
                                    <td><span >{{ $row->course_name??$row->name }}</span></td>
                                    <td>{{($row->schedule_type=='s')? __lang('scheduled'):__lang('post-class') }}</td>
									<td>{{ showDate('d/m/Y',$row->created_at) }}</td>
                                    <td>{{ showDate('d/m/Y',$row->opening_date) }}</td>
                                    <td>{{ showDateTime('d/m/Y h:i A',$row->due_date) }}</td>
								    <td>
                                        {{ $submissionTable->getTotalForAssignment($row->id) }} <a class="btn btn-primary btn-sm" href="{{ adminUrl(['controller'=>'assignment','action'=>'submissions','id'=>$row->id]) }}">{{ __lang('view-all') }}</a>
                                        </td>
                                    @php if(GLOBAL_ACCESS): @endphp
                                        <td>{{ adminName($row->admin_id) }}</td>
                                    @php endif;  @endphp
									<td  >
                                        <div class="btn-group dropleft">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __lang('actions') }}
                                            </button>
                                            <div class="dropdown-menu dropleft wide-btn">
                                                <a href="{{ adminUrl(array('controller'=>'assignment','action'=>'edit','id'=>$row->id)) }}" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title=""><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>

                                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'assignment','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
                                                <a onclick="openModal('{{__lang('homework-info')}}','{{ adminUrl(['controller'=>'assignment','action'=>'view','id'=>$row->id]) }}')" href="#" class="dropdown-item"  ><i class="fa fa-info"></i> {{ __lang('info') }}</a>

                                            </div>
                                        </div>



                                    </td>
								</tr>
								  @php endforeach;  @endphp

							</tbody>
						</table>

                      {{$paginator->links()}}			</div><!--end .box-body -->
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>

    <div class="popup">
        <button id="close">&times;</button>
        <h2>Ugraded Assignments Its Comming plZ wait</h2>
       <table>
            <thead>
              <tr>
                <th>{{ __lang('title') }}</th>
									<th>{{ __lang('session-course') }}</th>
                                    <th>{{ __lang('type') }}</th>
									<th>{{ __lang('created-on') }}</th>
                                    <th>{{ __lang('opening-date') }}</th>
                                    <th>{{ __lang('due-date') }}</th>
                                    <th>{{ __lang('submissions') }}</th>
                                    @php if(GLOBAL_ACCESS): @endphp
                                    <th>{{ __lang('created-by') }}</th>
                                    @php endif;  @endphp
									<th   >{{ __lang('actions') }}</th>
              </tr>
            </thead>
            <tbody>
            <tr>
             <td data-label="name">ahmed</td>
             <td data-label="email">ahmed@yahoo.com</td>
             <td data-label="fullName">ahmed hassan</td> 
            </tr>
            <tr>
             <td data-label="name">mohamed</td>
             <td data-label="email">mohamed@yahoo.com</td>
             <td data-label="fullName">mohamed ahmed</td>   
            </tr>
            <tr>
             <td data-label="name">abdo</td>
             <td data-label="email">abdo@yahoo.com</td>
             <td data-label="fullNme">abdo ali</td>
            </tr>
            <tr>
             <td data-label="name">karim</td>
             <td data-label="email">karim@yahoo.com</td>
             <td data-label="fullName">karim hassan</td>
            </tr>
            </tbody>
         </table>
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

 <script>
            $(".select2insidemodal").select2({
               
            });
        </script>
        
        <script type="text/javascript">
             window.addEventListener("load", function(){
                    setTimeout(
                        function open(event){
                            document.querySelector(".popup").style.display = "block";
                        },
                        1000
                    )
                });
                document.querySelector("#close").addEventListener("click", function(){
                    document.querySelector(".popup").style.display = "none";
                });
        </script>
@endsection
