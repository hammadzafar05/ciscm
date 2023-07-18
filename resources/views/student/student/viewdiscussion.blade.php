@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.student.discussion')=>__lang('instructor-chat'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')


<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>{{  $row->subject }}</h4>
                    <br>
                     <div class="card-header-action"><small> {{  __lang('on')  }} {{  showDate('D, d M Y',$row->created_at) }}</small></div>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(clean($row->question)) !!}   </p>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


            <form method="post" class="form" action="{{  route('student.student.addreply',['id'=>$row->id]) }}">
             @csrf
                <div class="form-group">
                    <textarea required="required" placeholder="{{ __lang('reply-here') }}" class="form-control" name="reply" id="reply"  rows="3"></textarea>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">{{  __lang('Reply')  }}</button>
                </div>
            </form>

        </div>
    </div>


    @php  if(!empty($total)): @endphp
    <div class="row mt-5">
        <div class="col-md-12">
            <h4>{{  __lang('Replies')  }}</h4>
            @php  foreach($paginator as $row):  @endphp

            <div class="card card-success">
                <div class="card-header">
                    <h4>{{ $row->name }} {{ $row->last_name }}</h4> <br>
                     <small>{{  __lang('on')  }} {{  showDate('r',$row->created_at) }}</small>
                </div>
                <div class="card-body">
                    <p>{!!  nl2br(clean($row->reply)) !!}  </p>
                </div>

            </div>
            @php  endforeach;  @endphp


        </div>
    </div>
@php  endif;  @endphp




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
        route('student.student.viewdiscussion',['id'=>$row->id])

    );
     @endphp
</div>
@endsection
