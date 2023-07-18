@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.survey.index')=>__lang('surveys'),
            '#'=>__lang('courses')
        ]])
@endsection

@section('content')
<a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'survey','action'=>'addsession','id'=>$id)) }}"><i class="fa fa-plus"></i> Add to Session/Course</a>
<br><br>
<table class="table table-stripped">
    <thead>
    <tr>
        <th>{{ __lang('session-course') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
        <tr>
            <td>{{ $row->course_name }}</td>
            <td>
                <a class="btn btn-sm btn-primary" href="{{ adminUrl(['controller'=>'survey','action'=>'editsession','id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                <a class="btn btn-sm btn-success" href="{{ adminUrl(['controller'=>'survey','action'=>'send','id'=>$row->id]) }}"><i class="fa fa-envelope"></i> {{ __lang('send-to-students') }}</a>
                <a class="btn btn-sm btn-danger" href="{{ adminUrl(['controller'=>'survey','action'=>'deletesession','id'=>$row->id]) }}"  onclick="return confirm('{{__lang('delete-confirm')}}')"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

            </td>
        </tr>
    @php endforeach;  @endphp
    </tbody>

</table>
@php if($rowset->count()==0): @endphp
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {{ __lang('survey-session-help') }}

    </div>

@php endif;  @endphp

@endsection
