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



    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>

                    <th>{{  __lang('survey')  }}</th>
                    <th>{{  __lang('Questions')  }}</th>
                    <th   ></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($paginator as $row):  @endphp
                    <tr>
                        <td>{{  $row->name }}</td>
                        <td>{{  $questionTable->getTotalQuestions($row->survey_id) }}</td>
                        <td >
                               <a  href="{{  route('survey',array('hash'=>$row->hash)) }}" class="btn btn-primary " ><i class="fa fa-play"></i> {{  __lang('Take Survey')  }}</a>
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
            array(
                'route' => 'student/default',
                'controller'=>'student',
                'action'=>'surveys'
            )
        );
         @endphp
    </div>




@endsection
