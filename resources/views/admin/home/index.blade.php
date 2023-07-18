@extends('layouts.admin')
@section('innerTitle',__lang('dashboard'))
@section('pageTitle',__lang('dashboard'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard')
        ]])
@endsection

@section('content')
<div class="row">

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            @can('access','view_students')
            <a href="{{ route('admin.student.index') }}">
                @endcan
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fa fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{__lang('students')}}</h4>
                    </div>
                    <div class="card-body">
                        {{$totalStudents}}
                    </div>
                </div>
            </div>
                @can('access','view_students')
            </a>
            @endcan
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            @can('access-group','course')
            <a href="{{ route('admin.student.sessions') }}?type=c">
                @endcan
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ __lang('online-courses') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalCourses }}
                    </div>
                </div>
            </div>
                @can('access-group','course')
            </a>
            @endcan
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            @can('access-group','course')
            <a href="{{ route('admin.student.sessions') }}">
                @endcan
                <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-calendar"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ __lang('active-sessions') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalSessions }}
                    </div>
                </div>
            </div>
                @can('access-group','course')
            </a>
            @endcan
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            @can('access','view_classes')
            <a href="{{ route('admin.lesson.index') }}">
                @endcan
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-desktop"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ __lang('classes') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalClasses }}
                    </div>
                </div>
            </div>
                @can('access','view_classes')
            </a>
            @endcan
        </div>
    </div>
    <div class="row">
        @can('access','view_payments')
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __lang('sales') }}</h4>
                    <div class="card-header-action">
                                <i class="fa fa-chart-bar"></i>

                    </div>
                </div>
                <div class="card-body">
                    <canvas id="myChart" height="182"></canvas>
                    <div class="statistic-details mt-sm-4">
                        <div class="statistic-details-item">
                            <span class="text-muted">{{ $todaySales }}</span>
                            <div class="detail-value">{{ price($todaySum) }}</div>
                            <div class="detail-name">{{ __lang('today-sales') }}</div>
                        </div>
                        <div class="statistic-details-item">
                            <span class="text-muted">{{ $weekSales }}</span>
                            <div class="detail-value">{{ price($weekSum) }}</div>
                            <div class="detail-name">{{ __lang('week-sales') }}</div>
                        </div>
                        <div class="statistic-details-item">
                            <span class="text-muted">{{ $monthSales }}</span>
                            <div class="detail-value">{{ price($monthSum) }}</div>
                            <div class="detail-name">{{ __lang('month-sales') }}</div>
                        </div>
                        <div class="statistic-details-item">
                            <span class="text-muted">{{ $yearSales }}</span>
                            <div class="detail-value">{{ price($yearSum) }}</div>
                            <div class="detail-name">{{ __lang('year-sales') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('access','view_discussions')
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{__lang('discussions')}}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach($discuss['paginator'] as $row)
                        <li class="media">
                            <img class="mr-3 rounded-circle" width="50" src="{{ profilePictureUrl($row->picture) }}" alt="avatar">
                            <div class="media-body">
                                <div class="float-right text-primary">{{ \Illuminate\Support\Carbon::parse($row->created_at)->diffForHumans() }}</div>
                                <div class="media-title">{{ $row->name }} {{ $row->last_name }}</div>
                                <span class="text-small text-muted">{{ limitLength($row->subject,200) }}</span>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                    <div class="text-center pt-1 pb-1">
                        <a href="{{ route('admin.discuss.index') }}" class="btn btn-primary btn-lg btn-round">
                            {{ __lang('view-all') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
            @endcan
    </div>

    @can('access','view_students')
    <div class="row">

        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __lang('latest-users') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row pb-2">
                        @foreach($latestUsers as $user)
                        <div class="col-4 col-sm-3 col-lg-2 mb-4 mb-md-0">
                            <div class="avatar-item mb-0  viewbutton @if($user->student) int_curpoin @endif"  @if($user->student)   data-id="{{ $user->student->id }}" data-toggle="modal" data-target="#simpleModal" title="@lang('default.view')"   @endif >
                                <img  alt="image" src="{{ profilePictureUrl($user->picture) }}" class="img-fluid" data-toggle="tooltip" title="{{  $user->name }} {{ $user->last_name }}">
                                <div class="avatar-badge" title="{{ __lang($user->role->name) }}" data-toggle="tooltip">
                                    @if($user->role_id==1)
                                    <i class="fas fa-wrench"></i>
                                    @else
                                        <i class="fas fa-graduation-cap"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <div class="row">
        @can('access','view_payments')
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="d-inline">{{ __lang('invoices') }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.student.invoices') }}" class="btn btn-primary">{{ __lang('View All') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                    @foreach($invoices as $invoice)
                        @if($invoice->user)
                        <li class="media">
                            <img class="mr-3 rounded-circle" width="50" src="{{ profilePictureUrl($invoice->user->picture) }}" alt="avatar">
                            <div class="media-body">
                                @if($invoice->paid==1)
                                <div class="badge badge-pill badge-success mb-1 float-right">{{ __lang('paid') }}</div>
                                @else
                                <div class="badge badge-pill badge-danger mb-1 float-right">{{ __lang('unpaid') }}</div>
                                @endif

                                <h6 class="media-title"><a class=" viewbutton "  @if($invoice->user->student)   data-id="{{ $invoice->user->student->id }}" data-toggle="modal" data-target="#simpleModal" title="@lang('default.view')"   @endif href="#">{{ $invoice->user->name }} {{ $invoice->user->last_name }}</a></h6>
                                <div class="text-small text-muted">{{ price($invoice->amount) }}  @if(empty($invoice->paid)) <div class="bullet"></div>
                                    <a href="{{ adminUrl(array('controller'=>'student','action'=>'approvetransaction','id'=>$invoice->id)) }}">{{ __lang('approve') }}</a> @endif <div class="bullet"></div> <span class="text-primary">Now</span></div>
                            </div>
                        </li>
                        @endif
                    @endforeach

                    </ul>
                </div>
            </div>


        </div>
        @endcan

        @can('access','view_sessions')
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __lang('recent-courses') }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.student.sessions') }}" class="btn btn-primary">{{ __lang('View All') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="summary">
                        <div class="summary-item">
                            <ul class="list-unstyled list-unstyled-border">

                                @foreach($session['paginator'] as $row)
                                    @php
                                        $course = \App\Course::find($row->id);
                                    @endphp
                                <li class="media">
                                    <a @if($course->type != 'c')  href="{{ route('admin.student.editsession',['id'=>$row->id]) }}"  @else  href="{{ route('admin.session.editcourse',['id'=>$row->id]) }}"  @endif >
                                        <img class="mr-3 rounded" width="50" @if(!empty($course->picture) && file_exists($course->picture)) src="{{ asset($course->picture) }}"  @else src="{{ asset('client/themes/admin/assets/img/products/product-2-50.png') }}" @endif alt="product">
                                    </a>
                                    <div class="media-body">
                                        <div class="media-right">{{ price($course->fee) }}</div>
                                        <div class="media-title"><a  @if($course->type != 'c')  href="{{ route('admin.student.editsession',['id'=>$row->id]) }}"  @else  href="{{ route('admin.session.editcourse',['id'=>$row->id]) }}"  @endif >{{ $course->name }}</a></div>
                                        <div class="text-muted text-small">@if($course->admin)<span class="text-primary">{{ $course->admin->user->name }} {{ $course->admin->user->last_name }}</span> <div class="bullet"></div> @endif  {{ \Illuminate\Support\Carbon::parse($course->created_at)->diffForHumans() }}</div>
                                    </div>
                                </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
            @endcan
    </div>

@endsection
@section('footer')
    <!-- Page Specific JS File -->
    <script src="{{ asset('client/themes/admin/assets/js/page/index-0.js_') }}"></script>

    <script type="text/javascript">
        "use strict";

        var statistics_chart = document.getElementById("myChart").getContext('2d');

        var myChart = new Chart(statistics_chart, {
            type: 'line',
            data: {
                labels: {!! clean($monthList) !!},
                datasets: [{
                    label: '{{ __lang('sales') }}',
                    data: {!! clean($monthSaleData) !!},
                    borderWidth: 5,
                    borderColor: '#6777ef',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6777ef',
                    pointRadius: 4
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            stepSize: 1000
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#fbfbfb',
                            lineWidth: 2
                        }
                    }]
                },
            }
        });

        $('#visitorMap').vectorMap(
            {
                map: 'world_en',
                backgroundColor: '#ffffff',
                borderColor: '#f2f2f2',
                borderOpacity: .8,
                borderWidth: 1,
                hoverColor: '#000',
                hoverOpacity: .8,
                color: '#ddd',
                normalizeFunction: 'linear',
                selectedRegions: false,
                showTooltip: true,
                pins: {
                    id: '<div class="jqvmap-circle"></div>',
                    my: '<div class="jqvmap-circle"></div>',
                    th: '<div class="jqvmap-circle"></div>',
                    sy: '<div class="jqvmap-circle"></div>',
                    eg: '<div class="jqvmap-circle"></div>',
                    ae: '<div class="jqvmap-circle"></div>',
                    nz: '<div class="jqvmap-circle"></div>',
                    tl: '<div class="jqvmap-circle"></div>',
                    ng: '<div class="jqvmap-circle"></div>',
                    si: '<div class="jqvmap-circle"></div>',
                    pa: '<div class="jqvmap-circle"></div>',
                    au: '<div class="jqvmap-circle"></div>',
                    ca: '<div class="jqvmap-circle"></div>',
                    tr: '<div class="jqvmap-circle"></div>',
                },
            });
    </script>
    <div class="modal fade" id="simpleModal"  tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __lang('student-details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="info">
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('Loading...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
            });
        });
    </script>
@endsection
