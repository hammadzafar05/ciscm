@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.assignment.index')=>__lang('homework'),
            '#'=>__lang('submissions')
        ]])
@endsection

@section('content')
<div>
    <div >
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-thumbs-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __lang('passed') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $passed }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-thumbs-down"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __lang('failed') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $failed }}
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-chart-bar"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __lang('average-score') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $average }}%
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-user-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Enrolled</h4>
                            </div>
                            <div class="card-body">
                                {{ $all_enrolled_students->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-user-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Not Submitted</h4>
                            </div>
                            <div class="card-body">
                                {{ $not_submitted_students }}
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="dropdown d-inline mr-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-download"></i>   {{ __lang('export') }}
                    </button>
                    <div class="dropdown-menu wide-btn">
                        <a class="dropdown-item" href="{{ adminUrl(['controller'=>'assignment','action'=>'exportresult','id'=>$row->id]) }}?type=pass" ><i class="fa fa-thumbs-up"></i> {{ __lang('export-passed') }}</a>
                        <a class="dropdown-item"  href="{{ adminUrl(['controller'=>'assignment','action'=>'exportresult','id'=>$row->id]) }}?type=fail"><i class="fa fa-thumbs-down"></i> {{ __lang('export-failed') }}</a>

                    </div>
                </div>

                <div class="float-right">
                    <a href="{{ adminUrl(['controller'=>'assignment','action'=>'export_file','id'=>$row->id]) }}"
                       id=""
                       class="btn btn-danger" target="_blank">
                        <i class="fa fa-download"></i> Export Submitted File
                    </a>
                </div>
            </div>
            <div class="card-body">



                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="30">#</th>
                        <th>{{ __lang('student') }}</th>
                        <th>{{ __lang('Submission Date') }}</th>
                        <th>{{ __lang('grade') }}</th>
                        <th>{{ __lang('status') }}</th>
                        <th class="text-right1" >{{ __lang('actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0; @endphp
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>@php $i++; echo $i; @endphp</td>
                            <td>{{ $row->first_name.' '.$row->last_name }}</td>
                            <td><span >{{ showDate('d/m/Y',$row->created_at) }}</span></td>
                            <td>
                                @php if(!is_null($row->grade)): @endphp
                                {{ $row->grade }}%
                            @php endif;  @endphp
                            </td>
                            <td>
                               {{ ($row->editable==1)? __lang('ungraded'):__lang('graded') }}
                            </td>

                            <td class="text-right1">
                                <a class="btn btn-primary" href="{{ adminUrl(['controller'=>'assignment','action'=>'viewsubmission','id'=>$row->id]) }}"><i class="fa fa-info-circle"></i> {{ __lang('view-entry') }}</a>
                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>

                {{--@php
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
                        'controller'=>'assignment',
                        'action'=>'submissions',
                        'id'=>$row->id
                    )
                );
                @endphp--}}
            </div><!--end .box-body -->
        </div><!--end .box -->

        <div class="card">
            <div class="card-header">
                <h4>Not Submitted</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="30">#</th>
                        <th>{{ __lang('student') }}</th>

                        <th>{{ __lang('email') }}</th>
                        <th>{{ __lang('Mobile') }}</th>
                        <th></th>
                        <th>Send Reminder</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0; @endphp
                    @php foreach($all_enrolled_students as $row):  @endphp
                    @if(in_array($row->student_id,$submitted_students) == FALSE)
                    <tr>
                        <td>@php $i++; echo $i; @endphp</td>
                        <td>{{ $row->name.' '.$row->last_name }}</td>

                        <td>{{ $row->email }}</td>
                        <td class="text-right1">
                            {{ $row->mobile_number }}
                        </td>
                        <td>
                            <a href="{{ route('impersonate.impersonate',$row->user_id) }}">Login as {{ $row->name }}</a>
                        </td>
                        <td>
                            <a class="dropdown-item"
                               onclick="return confirm('Are you sure?')"
                               href="{{ route('admin.assignment.send_email', ['id'=>$assignment_id, 'email'=>$row->email]) }}">
                                <i class="fa fa-envelope"></i> Resend
                            </a>
                        </td>
                    </tr>
                    @endif

                    @php endforeach;  @endphp

                    </tbody>
                </table>
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->

@endsection
