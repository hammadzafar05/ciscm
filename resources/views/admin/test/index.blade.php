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
    <form class="form-inline mr-auto" method="get" action="{{ adminUrl(array('controller'=>'test','action'=>'index')) }}">
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
            <div class="card-header">

                <a class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'test','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('add-test') }}</a>


            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('id') }}</th>
                        <th>{{ __lang('name') }}</th>
                        <th>{{ __lang('shomvabona-type') }}</th>
                        <th>{{ __lang('enabled') }}</th>
                        <th>{{ __lang('private') }}</th>
                        <th>{{ __lang('questions') }}</th>
                        <th>{{ __lang('attempts') }}</th>

                        @php if(GLOBAL_ACCESS): @endphp
                        <th>{{ __lang('created-by') }}</th>
                        @php endif;  @endphp
                        <th class="text-right">{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td>{{ $row->name }}</td>
                            <td>{{ ($row->exam_type == 1 ? __lang('shomvabona-written') : __lang('shomvabona-mcq')) }}</td>
                            <td>{{ boolToString($row->enabled) }}</td>
                            <td>{{ boolToString($row->private) }}</td>
                            <td>{{ $questionTable->getTotalQuestions($row->id) }}</td>
                            <td> <a class="btn btn-sm btn-primary" href="{{ adminUrl(['controller'=>'test','action'=>'results','id'=>$row->id]) }}">{{ $studentTestTable->getTotalForTest($row->id) }} ({{ __lang('view') }})</a></td>
                        @php if(GLOBAL_ACCESS): @endphp
                            <td>{{ adminName($row->admin_id) }}</td>
                        @php endif;  @endphp
                            <td >
                                <div class="btn-group dropleft ">
                                    <button type="button" class="btn btn-primary dropdown-toggle " data-toggle="dropdown">
                                        <i class="fa fa-gears"></i> {{ __lang('options') }}
                                    </button>
                                    <ul class="dropdown-menu wide-btn float-right animation-slide" role="menu" style="text-align: left;">
                                        <li><a class="dropdown-item" href="{{ adminUrl(array('controller'=>'test','action'=>'questions','id'=>$row->id)) }}"  ><i class="fa fa-question-circle"></i> {{ __lang('manage-questions') }} </a></li>
                                        <li><a  class="dropdown-item" href="{{ adminUrl(array('controller'=>'test','action'=>'exportquestions','id'=>$row->id)) }}"  ><i class="fa fa-download"></i> {{ __lang('export-questions') }}</a> </li>
                                         <li><a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'test','action'=>'edit','id'=>$row->id)) }}"  ><i class="fa fa-edit"></i> {{ __lang('edit') }}</a></li>
                                        <li><a class="dropdown-item"  href="{{ adminUrl(['controller'=>'test','action'=>'sessions','id'=>$row->id]) }}"><i class="fa fa-calendar"></i> {{ __lang('manage-sessions-courses') }}</a></li>
                                        <li><a  class="dropdown-item" onclick="return confirm('{{__lang('test-duplicate-confirm')}}')"  href="{{ adminUrl(array('controller'=>'test','action'=>'duplicate','id'=>$row->id)) }}" ><i class="fa fa-copy"></i> {{ __lang('duplicate') }}</a></li>
                                        <li><a  class="dropdown-item" onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'test','action'=>'delete','id'=>$row->id)) }}"   ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
                                        </li>
                                    </ul>
                                </div>

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
                        'controller'=>'test',
                        'action'=>'index',
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->


@endsection
