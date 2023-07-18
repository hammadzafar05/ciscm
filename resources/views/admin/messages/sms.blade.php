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


<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>{{__lang('message')}}</th>
        <th>{{__lang('description')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($templates as $template)
        <tr>
            <td>{{ $template->id }}</td>
            <td>{{__lang('s-template-name-'.$template->id)}}</td>
            <td>{{__lang('s-template-desc-'.$template->id)}}</td>
            <td><a class="btn btn-primary" href="{{ adminUrl(['controller'=>'messages','action'=>'editsms','id'=>$template->id]) }}"> <i class="fa fa-edit"></i> {{__lang('edit')}}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $templates->links() }}
@endsection
