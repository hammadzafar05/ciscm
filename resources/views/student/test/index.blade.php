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
<div class="card"   >
    <!--primary starts-->

    <div class="card-body">

        <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>

                <th>{{  __lang('Test')  }}</th>
                <th>{{  __lang('Questions')  }}</th>
                <th>{{  __lang('Exam Date')  }}</th>
                <th>{{  __lang('Minutes Allowed')  }}</th>
                <th>{{  __lang('multiple-attempts-allowed')  }}</th>
                <th>{{  __lang('passmark')  }}</th>
                <th  >{{  __lang('Actions')  }}</th>
            </tr>
            </thead>
            <tbody>
            @php  foreach($paginator as $row):  @endphp

                <tr>
                    <td>{{  $row->name }}</td>
                    
                    <td>{{  $row->number_of_questions }} {{  $questionTable->getTotalQuestions($row->test_id) }}</td>
                    <td>
                        @php
                            $test_details = $sessionTestTable->getTestDate($row->test_id);
                        @endphp
                        @if ($test_details['opening_date'] && $test_details['closing_date'])
                            Between {{ showDateTime('d/m/y h:i A',$test_details['opening_date']) }} <br>-<br> {{ showDateTime('d/m/y h:i A',$test_details['closing_date']) }}
                        @else
                            @if($test_details['closing_date'] == '')
                                {{ showDateTime('d/m/y h:i A',$test_details['opening_date']) }}
                            @else
                                {{ showDateTime('d/m/y h:i A',$test_details['closing_date']) }}
                            @endif
                        @endif
                    </td>
                    <td>{{  empty($row->minutes)?__lang('Unlimited'):$row->minutes }}</td>
                    <td>{{  boolToString($row->allow_multiple) }}</td>
                    <td>{{  ($row->passmark > 0)? $row->passmark.'%':__lang('Ungraded') }}</td>
                    <td >
                    @php
                        if(!$studentTest->hasTest($row->test_id,$id) || !empty($row->allow_multiple)):  @endphp
                        <a href="{{  route('student.test.taketest',array('id'=>$row->test_id)) }}" class="btn btn-primary " ><i class="fa fa-play"></i> {{  __lang('Take Test')  }}</a>
                    @php
                        endif;
                    @endphp

                    @php
                        if($studentTest->hasTest($row->test_id,$id) && $row->show_result==1):
                    @endphp
                        {{--
                        <a href="{{ route('student.test.testresults',array( 'id'=>$row->test_id)) }}"
                           class="btn btn-success ">
                            <i class="fa fa-list-ul"></i> {{  __lang('Your Results')  }}
                        </a>
                        WU-115 : Hide Student Results on Test Management
                        --}}
                    @php
                        endif;
                    @endphp

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
            route('student.test.index')
        );
         @endphp
    </div>


</div>

<!--container ends-->

@endsection
