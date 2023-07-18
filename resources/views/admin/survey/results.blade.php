@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
             route('admin.survey.index')=>__lang('surveys'),
            '#'=>__lang('results')
        ]])
@endsection

@section('content')
<div>
    <div >
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('student') }}</th>
                        <th>{{ __lang('date-taken') }}</th>
                        <th>{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>
                                 @php if(!empty($row->student_id) && \App\Student::find($row->student_id)):  @endphp
                                     @php $student = \App\Student::find($row->student_id)  @endphp
                                     {{ $student->user->name }} {{ $student->user->last_name }}

                                     @php else:  @endphp
                                     {{ __lang('anonymous') }}
                                @php endif;  @endphp

                            </td>
                            <td>{{ showDate('d/M/Y',$row->created_at) }}</td>


                            <td >
                                 <a onclick="openModal('#{{ $row->id }}','{{ adminUrl(array('controller'=>'survey','action'=>'result','id'=>$row->id)) }}')"  href="javascript:;" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('view-result') }}"><i class="fa fa-eye"></i></a>
                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'survey','action'=>'deleteresult','id'=>$row->id)) }}"  class="btn btn-xs btn-danger btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
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
                        'action'=>'results',
                        'id'=>$row->id
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/datatables/media/css/jquery.dataTables.min.css') }}">
@endsection
@section('footer')
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script type="text/javascript">
        jQuery('.date').pickadate({
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection
