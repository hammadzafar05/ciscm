@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ adminUrl(array('controller'=>'survey','action'=>'index')) }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}"   name="filter" class="form-control" type="search" placeholder="{{ __lang('search') }}" aria-label="{{ __lang('search') }}" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection



@section('content')
    <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'survey','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('add-survey') }}</a>
    <br> <br>
<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ __lang('id') }}</th>
        <th>{{ __lang('name') }}</th>
        <th>{{ __lang('enabled') }}</th>
        <th>{{ __lang('private') }}</th>
        <th>{{ __lang('questions') }}</th>
        <th>{{ __lang('attempts') }}</th>

        @php if(GLOBAL_ACCESS): @endphp
        <th>{{ __lang('created-by') }}</th>
        @php endif;  @endphp
        <th>{{__lang('actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @php foreach($paginator as $row):  @endphp
    <tr>
        <td><span class="label label-success">{{ $row->id }}</span></td>
        <td>{{ $row->name }}</td>
        <td>{{ boolToString($row->enabled) }}</td>
        <td>{{ boolToString($row->private) }}</td>
        <td>{{ $questionTable->getTotalQuestions($row->id) }}</td>
        <td> <a class="btn btn-sm btn-primary" href="{{ adminUrl(['controller'=>'survey','action'=>'results','id'=>$row->id]) }}">{{ $studentSurveyTable->getTotalForTest($row->id) }} ({{ __lang('view') }})</a></td>
        @php if(GLOBAL_ACCESS): @endphp
        <td>{{ adminName($row->admin_id) }}</td>
        @php endif;  @endphp
        <td >
            <div class="btn-group dropleft ">
                <button type="button" class="btn btn-sm btn-primary dropdown-toggle " data-toggle="dropdown">
                    <i class="fa fa-cogs"></i> {{ __lang('options') }}
                </button>
                <ul class="dropdown-menu wide-btn  float-right animation-slide" role="menu"  >
                    <li><a class="dropdown-item" href="#"  data-toggle="modal" data-target="#myModal{{$row->id}}"><i class="fa fa-link"></i> {{ __lang('link') }}</a></li>
                    <li><a class="dropdown-item" href="{{ adminUrl(array('controller'=>'survey','action'=>'questions','id'=>$row->id)) }}"  ><i class="fa fa-question-circle"></i> {{ __lang('manage-questions') }}</a></li>
                    <li><a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'survey','action'=>'exportquestions','id'=>$row->id)) }}"  ><i class="fa fa-download"></i> {{ __lang('export-questions') }}</a></li>
                    <li><a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'survey','action'=>'edit','id'=>$row->id)) }}"  ><i class="fa fa-edit"></i> {{ __lang('edit') }}</a></li>
                    <li><a class="dropdown-item"  href="{{ adminUrl(['controller'=>'survey','action'=>'sessions','id'=>$row->id]) }}"><i class="fa fa-calendar"></i> {{ __lang('manage-sessions-courses') }}</a></li>
                    <li><a class="dropdown-item"  onclick="return confirm('{{__lang('duplicate-confirm')}}')"  href="{{ adminUrl(array('controller'=>'survey','action'=>'duplicate','id'=>$row->id)) }}" ><i class="fa fa-copy"></i> {{ __lang('duplicate') }}</a></li>
                    <li><a class="dropdown-item"  onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'survey','action'=>'delete','id'=>$row->id)) }}"   ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
                    </li>
                </ul>
            </div>

            <a class="btn  btn-sm btn-success" href="{{ adminUrl(['controller'=>'survey','action'=>'report','id'=>$row->id]) }}"  ><i class="fa fa-chart-bar"></i> {{ __lang('reports') }}</a>

        @section('footer')
        @parent
            <!-- Modal -->
            <div class="modal fade" id="myModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{{$row->id}}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel{{$row->id}}">{{ $row->name }}</h4>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        </div>
                        <div class="modal-body">
                            <a target="_blank" href="{{ route('survey',['hash'=>$row->hash])}}">{{ route('survey',['hash'=>$row->hash])}}</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        @endsection
        </td>
    </tr>
    @php endforeach;  @endphp

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
        array(
            'route' => 'admin/default',
            'controller'=>'survey',
            'action'=>'index',
        )
    );
@endphp


@endsection
