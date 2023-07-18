@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__lang('courses'),
            '#'=>__lang('course-tests')
        ]])
@endsection

@section('content')
    <div class="card">
     <div class="card-header">
         <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'session','action'=>'addtest','id'=>$id)) }}"><i class="fa fa-plus"></i> {{ __lang('add-test') }}</a>

     </div>
    <div class="card-body">
        <table class="table table-stripped">
            <thead>
            <tr>
                <th>{{ __lang('test') }}</th>
                <th>{{ __lang('opening-date') }}</th>
                <th>{{ __lang('closing-date') }}</th>
                <th>{{ __lang('author') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php foreach($rowset as $row):  @endphp
            <tr>
                <td>{{ $row->name }}</td>
                <td>@php if(!empty($row->opening_date)) echo showDate('d/M/Y',$row->opening_date);  @endphp</td>
                <td>@php if(!empty($row->closing_date))  echo showDate('d/M/Y',$row->closing_date);  @endphp</td>
                <td>
                    {{ adminName($row->admin_id) }}

                </td>
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ adminUrl(['controller'=>'session','action'=>'edittest','id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                    <a class="btn btn-sm btn-danger" href="{{ adminUrl(['controller'=>'test','action'=>'deletesession','id'=>$row->id]) }}"  onclick="return confirm('{{__lang('delete-confirm')}}')"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

                </td>
            </tr>
            @php endforeach;  @endphp
            </tbody>

        </table>
        @php if($rowset->count()==0): @endphp
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {{ __lang('specify-test-help') }}

        </div>

        @php endif;  @endphp

    </div>
    </div>


@endsection
