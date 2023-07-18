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
    <form class="form-inline mr-auto" method="get" action="{{ adminUrl(array('controller'=>'report','action'=>'index')) }}">
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
<div>
    <div >

        <div class="card">

            <div class="card-body">


                <button class="btn btn-success"  data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter"><i class="fa fa-filter"></i> {{ __lang('filter') }}</button>
                <br> <br>
                <div class="collapse" id="collapseFilter">
                    <div class="card card-body">
                        <form id="filterform"   role="form"  method="get" action="{{ adminUrl(array('controller'=>'report','action'=>'index')) }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="filter">{{ __lang('filter') }}</label>
                                        {{ formElement($text) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="group">{{ __lang('category') }}</label>
                                        {{ formElement($select) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="group">{{ __lang('sort') }}</label>
                                        {{ formElement($sortSelect) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="group">{{ __lang('type') }}</label>
                                        {{ formElement($typeSelect) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{ __lang('filter') }}</button>
                                    <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-success"><i class="fa fa-redo"></i> {{ __lang('clear') }}</button>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>



<div class="table-responsive_">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('id') }}</th>
                        <th>{{ __lang('session-course') }}</th>
                        <th>{{ __lang('type') }}</th>
                        <th>{{ __lang('enrolled-students') }}</th>
                        <th>{{ __lang('reports') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td>{{ $row->name }}</td>
                            <td>@php
                                switch($row->type){
                                    case 'b':
                                        echo __lang('training-online');
                                        break;
                                    case 's':
                                        echo __lang('training-session');
                                        break;
                                    case 'c':
                                        echo __lang('online-course');
                                        break;
                                }
                                @endphp</td>

<td>
    @php $session = \App\Course::find($row->id); echo $session->studentCourses()->count()  @endphp
</td>

                            <td>

                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-chart-bar"></i> {{ __lang('reports') }}
                                    </button>
                                    <ul class="dropdown-menu wide-btn float-right animation-slide" role="menu" style="text-align: left;">
                                      <li><a class="dropdown-item" href="{{adminUrl(['controller'=>'report','action'=>'classes','id'=>$row->id])}}"><i class="fa fa-desktop"></i> {{ __lang('classes') }}</a></li>
                                        <li><a  class="dropdown-item" href="{{adminUrl(['controller'=>'report','action'=>'students','id'=>$row->id])}}"><i class="fa fa-users"></i>  {{ __lang('students') }}</a></li>
                                        <li><a class="dropdown-item"  href="{{adminUrl(['controller'=>'report','action'=>'tests','id'=>$row->id])}}"><i class="fa fa-check-circle"></i> {{ __lang('tests') }}</a></li>
                                        <li><a  class="dropdown-item" href="{{adminUrl(['controller'=>'report','action'=>'homework','id'=>$row->id])}}"><i class="fa fa-edit"></i> {{ __lang('homework') }}</a></li>

                                    </ul>
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
                    $paginator,
                    // the scrolling style
                    'sliding',
                    // the partial to use to render the control
                    null,
                    // the route to link to when a user clicks a control link
                    array(
                        'route' => 'admin/default',
                        'controller'=>'report',
                        'action'=>'index',
                        'filter'=>$filter,
                        'group'=>$group,
                        'sort'=>$sort,
                        'type'=>$type
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

@endsection
