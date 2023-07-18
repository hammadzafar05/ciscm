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
<tr>
    <td>{{ formElement($form->get('lesson_'.$lesson_id)) }} </td>
    <td><span id="{{ 'lesson_name_'.$lesson_id }}">{{ $row->lesson_name }}</span></td>
    <td style="display: none"></td>

    <td>

        {{ formElement($form->get('lesson_date_'.$lesson_id)) }}</td>

    <td>

        {{ formElement($form->get('sort_order_'.$lesson_id)) }}</td>
</tr>
@endsection
