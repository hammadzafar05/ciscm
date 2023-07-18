@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=> __lang('Student Forum')
        ]])
@endsection

@section('content')



<!--container starts-->
<div >
    <!--primary starts-->

    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <form id="filterform" class="form-inline" role="form"  method="get" action="{{adminUrl(['controller'=>'forum','action'=>'index'])}}">


                    <div class="form-group" style="min-width: 200px">
                        <label class="sr-only" for="session_id">{{ __lang('session-course') }}</label>
                        @if(false)
                        {{ formElement($select) }}
                        @endif
                        <select name="course_id" id="course_id"
                                class="form-control select2">
                            <option value=""></option>
                            @foreach($form->get('course_id')->getValueOptions() as $option)
                                <option @if(old('course_id',$form->get('course_id')->getValue()) == $option['value']) selected @endif data-type="{{ $option['attributes']['data-type'] }}" value="{{ $option['value'] }}">{{$option['label']}}</option>
                            @endforeach
                        </select>



                    </div> &nbsp;

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{ __lang('filter') }}</button>

                </form>
            </div>
            <div class="col-md-6">
                <div class="btn-group float-right">

                    <a class="btn btn-primary" href="{{adminUrl(['controller'=>'forum','action'=>'addtopic'])}}"><i class="fa fa-plus"></i> {{ __lang('add-topic') }}</a>

                </div>
            </div>
        </div>

        <div class="table-responsive_ pt-2">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>{{ __lang('Topic') }}</th>
                    <th>{{ __lang('Session/Course') }}</th>
                    <th>{{ __lang('Created By') }}</th>
                    <th>{{ __lang('Added On') }}</th>
                    <th >{{ __lang('Replies') }}</th>
                    <th>{{ __lang('Last Reply') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php foreach($topics as $row):  @endphp
                    @php $topic = \App\ForumTopic::find($row->id);  @endphp
                    <tr>
                        <td>{{ $row->title }}</td>
                        <td>{{$row->course_name}}</td>
                        <td>
                            {{ $topic->user->name }}
                        </td>
                        <td>{{showDate('d/M/Y',$row->created_at)}}</td>
                        <td>{{($topic->forumPosts->count()-1) }}</td>
                        <td>@php if($topic->forumPosts->count()-1 > 0): @endphp
                                {{showDate('D, d M Y g:i a',$topic->forumPosts()->orderBy('id','desc')->first()->created_at) }}
                            @php endif;  @endphp
                        </td>
                        <td >
                             <div class="button-group dropup">
                                                   <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     {{ __lang('actions') }}
                                                   </button>
                                                   <div class="dropdown-menu wide-btn">
                                                       <a   class="dropdown-item"  href="{{adminUrl(['controller'=>'forum','action'=>'topic','id'=>$row->id])}}">{{ __lang('View') }}</a>

                                                       <a class="dropdown-item"  onclick="return confirm('Are you sure you want to delete this topic and all its posts?')"  href="{{  adminUrl(['controller'=>'forum','action'=>'deletetopic','id'=>$row->id]) }}">{{ __lang('Delete Topic') }}</a>

                                                   </div>
                                                 </div>

                        </td>
                    </tr>

                @php endforeach;  @endphp

                </tbody>
            </table>
        </div>
        @php
        // add at the end of the file after the table
        echo paginationControl(
        // the paginator object
            $topics,
            // the scrolling style
            'sliding',
            // the partial to use to render the control
            null,
            // the route to link to when a user clicks a control link
            array(
                'route' => 'admin/default',
                'controller'=>'forum',
                'action'=>'index'
            )
        );

        @endphp
    </div>


</div>

<!--container ends-->

@endsection
