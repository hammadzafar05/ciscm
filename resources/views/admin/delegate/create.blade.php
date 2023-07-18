@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('admin.view-our-delegats')=>'View Delegates',
            '#'=>isset($pageTitle)?$title:''
        ]])
@endsection


@section('content')
    <div ng-app="myApp" ng-controller="myCtrl">
        <div>
            <div class="card">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" @if(empty($delegate->id)) action="{{url('admin/our-delegats/')}}" @else action="{{url('admin/our-delegats/'.$delegate->id)}}" @endif>
                        @csrf
                      
                    <div class="form-group">
                        <label>Certificate Image</label>
                        <div class="input-group mb-3">
                           <input type="file" name="image" id="assignment_file">
                        </div>
                        @if($delegate->image)
                        <div class="get_image">
                            <img src="{{asset('public/usermedia/delegate/'.$delegate->image)}}" width="50">
                        </div>
                        @endif
                    </div>
                    <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </form>
                </div>
            </div><!--end .box -->
           
        </div><!--end .col-lg-12 -->
    </div>

@endsection


