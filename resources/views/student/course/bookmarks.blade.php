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
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{  __lang('course')  }}</th>
                    <th>{{  __lang('class')  }}</th>
                    <th>{{  __lang('lecture')  }}</th>
                    <th>{{  __lang('page')  }}</th>
                    <th>{{  __lang('added-on')  }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($paginator as $row):  @endphp
                    <tr>
                        <td>{{  $row->course_name  }}</td>
                        <td>{{  $row->lesson_name  }}</td>
                        <td>{{  $row->lecture_title  }}</td>
                        <td>{{  $row->page_title  }}</td>
                        <td>{{  showDate('d/M/Y',$row->created_at)  }}</td>
                        <td><a class="btn btn-primary" href="{{  route('student.course.lecture',['lecture'=>$row->lecture_id,'course'=>$row->course_id])  }}?page={{  $row->lecture_page_id  }}">{{  __lang('view')  }}</a></td>
                    </tr>
                @php  endforeach;  @endphp
                </tbody>
            </table>


        </div>
        @php
        // add at the end of the file after the table

        echo paginationControl(
// the paginator object
            $paginator,
            // the scrolling style
            'sliding',
            // the partial to use to render the control
            null,
            // the route to link to when a user clicks a control link
            array(
                'route' => 'student/default',
                'controller'=>'course',
                'action'=>'bookmarks'
            )
        );

         @endphp
    </div>
</div>

@endsection
