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

<div class="row">

    <div class="col-md-9">

        @php  foreach($paginator as $row):  @endphp

            <div class="panel panel-default paper-shadow" data-z="0.5">
                <div class="panel-body">

                    <div class="media media-clearfix-xs">
                        <div class="media-left text-center">
                            <div class="cover width-150 width-100pc-xs overlay cover-image-full hover margin-v-0-10">
                                <span class="img icon-block height-130 bg-default"></span>
                                <span class="overlay overlay-full padding-none icon-block bg-default">
                        <span class="v-center">


                            @php  if(!empty($row->picture)):  @endphp

                                <a href="{{  $this->url('session-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}" class="thumbnail" style="border: none; margin-bottom: 0px">
                                                <img src="{{  resizeImage($row->picture,150,130,url('/')) }}" >
                                            </a>
                            @php  else:  @endphp
                                <i class="fa fa-chalkboard-teacher"></i>
                            @php  endif;  @endphp
                        </span>
                    </span>
                                <a href="{{  $this->url('session-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}" class="overlay overlay-full overlay-hover overlay-bg-white">

                                </a>
                            </div>
                        </div>
                        <div class="media-body">
                            <h4 class="text-headline margin-v-5-0"><a href="{{  $this->url('session-details',['id'=>$row->session_id,'slug'=>safeUrl($row->session_name)]) }}">{{  $row->session_name  }}</a></h4>

                            <p>{{  limitLength($row->short_description,300) }}</p>
                            <p><table class="table">
                                <thead>
                                <tr>
                                    <th>{{  __lang('start-date')  }}</th>
                                    <th>{{  __lang('end-date')  }}</th>
                                    <th>{{  __lang('enrollment-closes')  }}</th>
                                    @php  if(setting('general_show_fee')==1): @endphp
                                        <th>{{  __lang('fee')  }}</th>
                                    @php  endif;  @endphp
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{  showDate('d/M/Y',$row->session_date) }}</td>
                                    <td>{{  showDate('d/M/Y',$row->session_end_date) }}</td>
                                    <td>{{  showDate('d/M/Y',$row->enrollment_closes) }}</td>
                                    @php  if(setting('general_show_fee')==1): @endphp
                                        <td>    @php  if(empty($row->payment_required)): @endphp
                                                {{  __lang('free')  }}
                                            @php  else:  @endphp
                                                {{  price($row->amount) }}
                                            @php  endif;  @endphp</td>
                                    @php  endif;  @endphp
                                </tr>

                                </tbody>
                            </table></p>


                            @php  $session = \Application\Entity\Session::find($row->session_id);  @endphp
                            <hr class="margin-v-8" />

                            <div class="row">
                                @php  foreach($session->sessionInstructors as $instructor): @endphp
                                    <div class="col-md-4">
                                        <div class="media v-middle">
                                            <div class="media-left">
                                                <img src="{{ profilePictureUrl($instructor->account->picture,url('/')) }}" alt="People" class="img-circle width-40"/>
                                            </div>
                                            <div class="media-body">
                                                <h4><a href="#"  data-toggle="modal" data-target="#instructorModal{{ $instructor->session_instructor_id }}">{{ $instructor->account->name }} {{ $instructor->account->last_name }}</a><br/></h4>
                                                {{  __lang('instructor')  }}
                                            </div>
                                        </div>
                                    </div>





                                @php  endforeach;  @endphp

                            </div>
                            <a href="{{  $this->url('session-details',array('id'=>$row->session_id)) }}" class="btn btn-primary float-right"><i class="fa fa-info-circle"></i> {{  __lang('details')  }}</a>



                        </div>
                    </div>

                </div>
            </div>

            @php  foreach($session->sessionInstructors as $instructor): @endphp

                <div class="modal fade" id="instructorModal{{ $instructor->session_instructor_id }}" tabindex="-1" role="dialog" aria-labelledby="instructorModal{{ $instructor->session_instructor_id }}Label">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="instructorModal{{ $instructor->session_instructor_id }}Label">{{ $instructor->account->name }} {{ $instructor->account->last_name }}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="media v-middle">
                                    <div class="media-left">
                                        <img src="{{ profilePictureUrl($instructor->account->picture,url('/')) }}" alt="People" class="img-circle width-200"/>
                                    </div>
                                    <div class="media-body">
                                        {{ $instructor->account->account_description }}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">{{  __lang('close')  }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @php  endforeach;  @endphp


        @php  endforeach;  @endphp


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
                'route' => 'courses',
            )
        );

         @endphp

        <br/>
        <br/>


    </div>
    <div class="col-md-3">

           <div class="panel panel-default" data-toggle="panel-collapse" data-open="true">
            <div class="panel-heading panel-collapse-trigger">
                <h4 class="panel-title">{{  __lang('filter')  }}</h4>
            </div>
            <div class="panel-body">
                <form id="filterform" class="form" role="form"  method="get" action="{{  $this->url('sessions') }}">
                    <div class="form-group input-group margin-none">
                        <div class="row margin-none">
                            <input type="hidden" name="group" value="{{  $group  }}"/>

                            <div class="form-group">
                                <label  for="filter">{{  __lang('search')  }}</label>
                                {{  formElement($text)  }}
                            </div>
                            <div  class="form-group">
                                <label  for="group">{{  __lang('sort')  }}</label>
                                {{  formElement($sortSelect)  }}
                            </div>

                            <div style="padding-top: 35px">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{  __lang('filter')  }}</button>
                                <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-inverse">{{  __lang('clear')  }}</button>

                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>


    </div>

</div>
@endsection
