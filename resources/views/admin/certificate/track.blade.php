@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>

    <form id="filterform"  role="form"  method="get" action="{{ adminUrl(['controller'=>'certificate','action'=>'track']) }}">

<div class="row">
    <div class="form-group col-md-5">
        <input placeholder="{{__lang('tracking-no-name-email')}}" class="form-control" type="text" name="query" id="query" value="{{ @$_GET['query'] }}">
    </div>

    <div class="col-md-6">
        <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i> {{__lang('search')}}</button>
        <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-inverse">{{__lang('clear')}}</button>

    </div>
</div>


    </form>
</div>
@if($paginator)


<div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{__lang('student')}}</th>
            <th>{{__lang('certificate')}}</th>
            <th>{{__lang('course-session')}}</th>
            <th>{{__lang('tracking-number')}}</th>
            <th>{{__lang('downloaded-on')}}</th>
        </tr>
        </thead>

        <tbody>

        @foreach($paginator as $student)
@php
$certificate = \App\Certificate::find($student->certificate_id);
 @endphp
            <tr>
                <td><a class="viewbutton" style="text-decoration: underline"   data-id="{{ $student->student_id }}" data-toggle="modal" data-target="#simpleModal" href="">{{ $student->name }} {{ $student->last_name }}</a></td>
                <td>{{$certificate->name}}</td>
                <td>{{$certificate->course->name}}</td>
                <td>{{ $student->tracking_number }}</td>
                <td>{{ showDate('d/M/Y',$student->created_at) }}</td>
            </tr>

        @endforeach

        </tbody>

    </table>

</div>
<div>
    @php
    // add at the end of the file after the table
    paginationControl(
    // the paginator object
        $paginator,
        // the scrolling style
        'sliding',
        // the partial to use to render the control
        null,
        // the route to link to when a user clicks a control link
        array(
            'route' => 'admin/default',
            'controller'=>'certificate',
            'action'=>'track'
        )
    );
    @endphp
</div>
@endif

@endsection

@section('footer')
    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="simpleModalLabel">{{ __lang('Student Details') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>


                </div>
                <div class="modal-body" id="info">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('Loading...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ url('admin/student/view') }}'+'/'+id);
            });
        });
    </script>

@endsection
