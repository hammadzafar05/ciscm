@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')


<div class="container has-margin-top has-margin-bottom" style="min-height: 400px">
    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{  __lang('Class')  }}</th>
            <th>{{ __lang('course-session') }}</th>
            <th>{{  __lang('Date')  }}</th>
        </tr>
        </thead>
        @php  foreach($attendance as $row):  @endphp
            <tr>
                <td>
                    @php  if($row->lesson_type=='c'): @endphp
                        <a style="text-decoration: underline" href="{{  $this->url('view-class',['sessionId'=>$row->session_id,'classId'=>$row->lesson_id])  }}">{{  $row->lesson_name  }}</a>
                    @php  else:  @endphp
                    {{  $row->lesson_name  }}
                    @php  endif;  @endphp
                </td>
                <td>
                    @php  if($row->session_type=='c'):  @endphp
                        <a  style="text-decoration: underline"  href="{{   $this->url('course-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)])  }}">{{  $row->session_name  }}</a>
                    @php  else:  @endphp
                        <a  style="text-decoration: underline"  href="{{   $this->url('session-details',['id'=>$row->session_id])  }}">{{  $row->session_name  }}{{  $row->session_name  }}</a>

                    @php  endif;  @endphp


                </td>
                <td>{{  date('d/M/Y',$row->attendance_date)  }}</td>
            </tr>
        @php  endforeach;  @endphp
    </table>

    @php
    // add at the end of the file after the table

    echo paginationControl(
    // the paginator object
        $attendance,
        // the scrolling style
        'sliding',
        // the partial to use to render the control
        array('partial/paginator.phtml', 'Admin'),
        // the route to link to when a user clicks a control link
        array(
            'route' => 'application/classes',
        )
    );

     @endphp


</div>
@endsection
