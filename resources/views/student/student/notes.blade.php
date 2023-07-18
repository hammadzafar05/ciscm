@extends('layouts.student')
@section('pageTitle',__lang('revision-notes'))
@section('innerTitle',__lang('revision-notes'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>__lang('revision-notes')
        ]])
@endsection

@section('content')


<!--breadcrumb-section ends-->
<!--container starts-->
<div class="container box"   >
    <!--primary starts-->

    <div class="card-body">

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>

                <th>{{  __lang('course-session')  }}</th>
                <th>{{  __lang('course-session-start')  }}</th>
                <th>{{  __lang('total-notes')  }}</th>
                <th class="text-right1" style="width:90px">{{  __lang('Actions')  }}</th>
            </tr>
            </thead>
            <tbody>
            @php  foreach($paginator as $row):  @endphp
            <tr>

                {{--MARUF START--}}
                <td>
                    @php
                        $course_name = explode(' - Batch ',$row->name);
                        $course_name = @$course_name[0];
                    @endphp
                    {{  $course_name }}
                </td>
                {{--MARUF END--}}

                <td>{{  showDate('d/M/Y',$row->start_date) }}</td>
                    <td>{{  $homeworkTable->getTotalForCategory($row->course_id)  }}</td>

                    <td class="text-right">

                            <a href="{{  route('student.student.sessionnotes',array('id'=>$row->course_id)) }}" class="btn btn-primary " ><i class="fa fa-eye"></i> {{  __lang('view-notes')  }}</a>

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

             route('student.student.notes')

        );
         @endphp
    </div>


</div>

<!--container ends-->

@endsection
