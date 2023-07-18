@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.assignment.index')=>__lang('homework'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('content')
    <div ng-app="myApp" ng-controller="myCtrl">
        <div>
            <div class="card">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{url('admin/student/add-certificate-badge/'.$course->id)}}">
                        @csrf
                      
                    <div class="form-group">
                        <label>Certificate Image</label>
                        <div class="input-group mb-3">
                           <input type="file" name="certificate_image" id="assignment_file">
                        </div>
                        <div class="get_image">
                            <img src="{{asset('public/usermedia/certificateandbadge/'.$course->certificate_image)}}" width="50">
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label>Badge Image</label>
                        <div class="input-group mb-3">
                           <input type="file" name="badge_image" id="assignment_file">
                        </div>
                        <div class="get_image">
                            <img src="{{asset('public/usermedia/certificateandbadge/'.$course->badge_image)}}" width="50">
                        </div>
                    </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </form>
                </div>
            </div><!--end .box -->
            <div class="card">
                <div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Course Name</th>
                                    <th>Course Certificate</th>
									<th>Course Badge</th>
								
								</tr>
							</thead>
							<tbody>
                           
								<tr>
									<td><span class="label label-success">{{ $course->name }}</span>
									</td>
                                    <td>
                                        <img width="50" src="{{asset('public/usermedia/certificateandbadge/'.$course->certificate_image)}}" />
                                    </td>
                                    <td>
                                        <img width="50" src="{{asset('public/usermedia/certificateandbadge/'.$course->badge_image)}}" />
									</td>
								</tr>
							</tbody>
						</table>
                        </div>
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>

@endsection


