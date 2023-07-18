{{----Starting WU-38 : Result Publication in website ----}}
@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')

    <div class="row">
        <table class="table table-striped">
            <thead>
            <tr>
                <th align="center" width="5%">Sl</th>
                <th align="center" width="40%">Program Name</th>
                <th align="center" width="19%">Type</th>
                <th align="center" width="12%">Classes</th>
                <th align="center" width="12%">Results</th>
            </tr>
            </thead>
            <tbody>

            @foreach($paginator as $row)
                @php  if($row->type=='c'): @endphp
                @php  $type='course';  @endphp
                @php  else: @endphp
                @php  $type='session';  @endphp
                @php  endif;  @endphp
                @php
                    $course = \App\Course::find($row->course_id);
                @endphp
                @php
                    $course_name = explode(' - Batch ',$row->name);
                    $course_name = @$course_name[0];
                @endphp
                <tr>
                    <td width="50">
                        {{ $loop->iteration }}
                    </td>
                    <td width="150">
                        <a href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}">{{ $course_name }}</a>
                    </td>
                    <td width="100">
                        <a href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}">{{ courseType($row->type) }}
                        </a>
                    </td>
                    <td width="100">
                        <a href="{{  route('student.'.$type.'-details',['id'=>$row->id,'slug'=>safeUrl($row->name)]) }}">{{ $course->lessons()->count() }} {{ __lang('classes') }}</a>
                    </td>
                    <td>
                        {!! $row->result_description == '' ? 'Pending' : $row->result_description !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('header')
    <style>
        .pagination {
            display: flex;
            justify-content: center;
        }

        .pagination li {
            display: block;
        }
    </style>
@endsection
{{----Ending WU-38 : Result Publication in website ----}}