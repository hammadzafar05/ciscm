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

    <div class="card">
     <div class="card-header">
         <a class="btn btn-primary" href="{{ adminUrl(['controller'=>'setting','action'=>'addtestgrade']) }}"><i class="fa fa-plus"></i> {{__lang('Add Grade')}}</a>

     </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>{{__lang('Grade')}}</th>
                <th>{{__lang('Minimum')}}</th>
                <th>{{__lang('Maximum')}}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($grades as $grade)
                <tr>
                    <td>{{ $grade->grade }}</td>
                    <td>{{ $grade->min }}</td>
                    <td>{{ $grade->max }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ adminUrl(['controller'=>'setting','action'=>'edittestgrade','id'=>$grade->id]) }}"><i class="fa fa-edit"></i> {{__lang('Edit')}}</a>
                        <a class="btn btn-danger" onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(['controller'=>'setting','action'=>'deletetestgrade','id'=>$grade->id]) }}"><i class="fa fa-trash"></i> {{__lang('Delete')}}</a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>


@endsection
