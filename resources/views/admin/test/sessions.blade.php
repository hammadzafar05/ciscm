@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.test.index')=>__lang('tests'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

    <div class="card">
     <div class="card-header">
         @if($rowset->count() == 0)
             <a class="btn btn-primary float-right custom-shomvabona"
                href="{{ adminUrl(array('controller'=>'test','action'=>'addsession','id'=>$id)) }}">
                 <i class="fa fa-plus"></i> Add to Session/Course
             </a>
         @endif
     </div>
    <div class="card-body">
        <table class="table table-stripped">
            <thead>
            <tr>
                <th>{{ __lang('session-course') }}</th>
                <th>{{ __lang('opening-date') }}</th>
                <th>{{ __lang('closing-date') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php foreach($rowset as $row):  @endphp
            <tr>
                <td>{{ $row->course_name }}</td>
                <td>@php if(!empty($row->opening_date)) echo showDateTime('d/M/Y h:i A',$row->opening_date);  @endphp</td>
                <td>@php if(!empty($row->closing_date))  echo showDateTime('d/M/Y h:i A',$row->closing_date);  @endphp</td>
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ adminUrl(['controller'=>'test','action'=>'editsession','id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                    <a class="btn btn-sm btn-danger" href="{{ adminUrl(['controller'=>'test','action'=>'deletesession','id'=>$row->id]) }}"  onclick="return confirm('{{__lang('delete-confirm')}}')"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

                </td>
            </tr>
            @php endforeach;  @endphp
            </tbody>

        </table>
        @php if($rowset->count()==0): @endphp
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {{ __lang('test-session-help') }}

        </div>

        @php endif;  @endphp
    </div>
    </div>




@endsection
