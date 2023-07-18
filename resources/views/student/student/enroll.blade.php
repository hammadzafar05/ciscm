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
@php  if($terminal != 1){   $this->headTitle($pageTitle); } @endphp

<!--breadcrumb-section ends-->
<!--container starts-->
<div class="container" style="background-color: white; min-height: 100px;   padding-bottom:50px; margin-bottom: 10px;   " >
    <!--primary starts-->

    <div class="card-body">

        {{  $this->alert(html_entity_decode($this->flashMessenger()->render())) }}
<div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>

                <th>{{  __lang('course-session')  }}</th>
                <th>{{  __lang('Start Date')  }}</th>
                <th>{{  __lang('End Date')  }}</th>
                <th>{{  __lang('Enrollment Closes')  }}</th>
                @php  if(setting('general_show_fee')==1): @endphp
                <th>{{  __lang('Fee')  }}</th>
                @php  endif;  @endphp
                <th class="text-right1" style="width:90px">{{  __lang('Actions')  }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php  foreach($paginator as $row):  @endphp
                <tr>
                     <td>{{  $row->session_name }}</td>
                    <td>{{  showDate('d/M/Y',$row->session_date) }}</td>
                    <td>{{  showDate('d/M/Y',$row->session_end_date) }}</td>
                    <td>{{  showDate('d/M/Y',$row->enrollment_closes) }}</td>
                    @php  if(setting('general_show_fee')==1): @endphp
<td>
    @php  if(empty($row->payment_required)): @endphp
    Free
    @php  else:  @endphp
    {{  price($row->amount) }}
    @php  endif;  @endphp
</td>
                    @php  endif;  @endphp
                    <td class="text-right">
                     @php  if($row->enrollment_closes > time()):  @endphp
                        @php  if($studentSessionTable->enrolled($id,$row->session_id)):  @endphp
                            <a href="{{  $this->url('application/default',array('controller'=>'student','action'=>'removesession','id'=>$row->session_id)) }}" class="btn btn-primary " ><i class="fa fa-minus"></i> {{  __lang('un-enroll')  }}</a>

                        @php  elseif($row->enrollment_closes > time()):  @endphp
                        <a href="{{  $this->url('set-session',array('id'=>$row->session_id)) }}" class="btn btn-primary " ><i class="fa fa-plus"></i> {{  __lang('Enroll Now')  }}</a>
                  @php  endif;  @endphp
                     @php  endif;  @endphp
                    </td>
                    <td>
                        <a class="btn btn-success" href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}" ><i class="fa fa-info-circle"></i> {{  __lang('Details')  }}</a>

                    </td>
                </tr>
            @php  endforeach;  @endphp

            </tbody>
        </table>
</div>
        @php
        // add at the end of the file after the table
        if($terminal != 1) {
            echo paginationControl(
            // the paginator object
                $paginator,
                // the scrolling style
                'sliding',
                // the partial to use to render the control
                array('partial/paginator.phtml', 'Admin'),
                // the route to link to when a user clicks a control link
                array(
                    'route' => 'application/enroll',
                )
            );
        }
         @endphp
    </div>


</div>

<!--container ends-->

@endsection
