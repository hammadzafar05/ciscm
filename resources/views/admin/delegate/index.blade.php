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
                <a class="btn btn-primary float-right" href="{{url('admin/our-delegats')}}"><i class="fa fa-plus"></i> Add More</a>



            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>{{ __lang('actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($delegate as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td><img src="{{asset('public/usermedia/delegate/'.$row->image)}}" width="50"></td>
                            <td>
                                <div class="btn-group dropleft">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __lang('actions') }}
                                            </button>
                                    <div class="dropdown-menu dropleft wide-btn">
                                                <a href="{{ url('admin/our-delegats/'.$row->id) }}" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title=""><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>

                                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'assignment','action'=>'delete','id'=>$row->id)) }}"  class="dropdown-item"  ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

                                    </div> 
                                </div> 
                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>

                {{$delegate->links()}}
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->


@endsection
