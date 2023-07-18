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
    @php if($type!='s'): @endphp
    <td  id="{{ 'lesson_type_'.$row->lesson_id }}">{{ ($row->lesson_type=='s')? 'Physical Location':'Online' }}</td>
@php endif;  @endphp
    <td>

        {{ formElement($form->get('lesson_date_'.$lesson_id)) }}</td>

    <td>
        @php if($row->lesson_type=='s'): @endphp
        {{ formElement($form->get('lesson_start_'.$lesson_id)) }}
        @php endif;  @endphp
    </td>
    <td>
        @php if($row->lesson_type=='s'): @endphp
        {{ formElement($form->get('lesson_end_'.$lesson_id)) }}
        @php endif;  @endphp
    </td>

    <td>
        @php if($row->lesson_type=='s'): @endphp
        {{ formElement($form->get('lesson_venue_'.$lesson_id)) }}
        @php endif;  @endphp
    </td>
</tr>

@endsection
