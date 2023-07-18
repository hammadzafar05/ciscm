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


<div class="container"   >

    <div class="row">

        @if(!empty(setting('general_discussion_instructions')))
        <div  class="col-md-4">
        {{  setting('general_discussion_instructions')  }}
        </div>
        @endif
        <div @if(!empty(setting('general_discussion_instructions'))) class="col-md-8" @else class="col-md-12"  @endif >

            <div class="card">
             <div class="card-header">
                 <h4>{{ __lang('new-question') }}</h4>
            </div>
            <div class="card-body">
                <form class="form" method="post" action="{{  route('student.student.adddiscussion') }}">

                    @csrf


                    <div class="form-group">
                        {{  formLabel($form->get('admin_id[]')) }}
                        {{  formElement($form->get('admin_id[]')) }}   <p class="help-block">{{  formElementErrors($form->get('admin_id[]')) }}</p>

                    </div>

                    <div class="form-group">
                        {{  formLabel($form->get('course_id')) }}
                        {{  formElement($form->get('course_id')) }}   <p class="help-block">{{  formElementErrors($form->get('course_id')) }}</p>

                    </div>

                    <div class="form-group">
                        {{  formLabel($form->get('subject')) }}
                        {{  formElement($form->get('subject')) }}   <p class="help-block">{{  formElementErrors($form->get('subject')) }}</p>

                    </div>




                    <div class="form-group">
                        {{  formLabel($form->get('question')) }}
                        {{  formElement($form->get('question')) }}   <p class="help-block">{{  formElementErrors($form->get('question')) }}</p>

                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">{{  __lang('Submit')  }}</button>
                    </div>
                </form>
            </div>
            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-md-12"  >

            <div class="card">
             <div class="card-header">
                <h4>{{  __lang('your-questions')  }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{  __lang('Subject')  }}</th>
                        <th>{{  __lang('Created On')  }}</th>
                        <th>{{  __lang('Recipients')  }}</th>
                        <th>{{ __lang('course-session') }}</th>
                        <th>{{  __lang('Replied')  }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php  foreach($paginator as $row):  @endphp
                    <tr>
                        <td>{{  $row->subject }}
                        </td>

                        <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                        <td>
                            <ul class="comma-list">
                                @php  if($row->admin==1): @endphp
                                <li>{{  __lang('Administrators')  }}</li>
                                @php  endif;  @endphp

                                @php  foreach($accountTable->getDiscussionAccounts($row->id) as $row2):  @endphp
                                <li>{{  $row2->name.' '.$row2->last_name }}</li>
                                @php  endforeach;  @endphp

                            </ul>










                        </td>
                        <td>
                            @php  if(!empty($row->course_id) && $sessionTable->recordExists($row->course_id)): @endphp
                            {{  $sessionTable->getRecord($row->course_id)->name }}
                            @php  endif;  @endphp
                        </td>
                        <td>{{  boolToString($row->replied)  }}</td>

                        <td>
                            <a href="{{  route('student.student.viewdiscussion',array('id'=>$row->id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('View')  }}"><i class="fa fa-eye"></i> {{  __lang('View')  }}</a>

                        </td>
                    </tr>
                    @php  endforeach;  @endphp

                    </tbody>
                </table>

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
                        route('student.student.discussion')
                    );
                @endphp
            </div>
            </div>



        </div>

    </div>

</div>

@endsection
