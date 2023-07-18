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
<div class="card">


<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>{{ __lang('course-session') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($sessions as $row)
        @php
            $course_name = explode(' - Batch ',$row->course->name);
            $course_name = @$course_name[0];
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
             <td>{{ $course_name }}</td>
            <td><a class="btn btn-primary" href="{{ route('student.test.reportcard',['id'=>$row->course_id]) }}"><i class="fa fa-download"></i> {{ __lang('download-statement') }}</a></td>
        </tr>
    @endforeach
    </tbody>


</table>
</div>
{!! $sessions->links() !!}
@endsection
