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



<!--breadcrumb-section ends-->
<!--container starts-->
<div class="card" >
    <!--primary starts-->
    <div class="card-header">
        {{  __lang('forum-page-intro')  }}
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>{{ __lang('course-session') }}</th>
                    <th>{{  __lang('Topics')  }}</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($paginator as $row):  @endphp

                    <tr>
                         <td>{{  $row->name }}</td>
                        <td>{{ \App\Course::find($row->course_id)->forumTopics->count()  }}</td>

                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('student.forum.topics',['id'=>$row->course_id]) }}">{{  __lang('View Topics')  }}</a>
                        </td>
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
            route('student.forum.index')
        );
         @endphp
    </div>


</div>

<!--container ends-->

@endsection
