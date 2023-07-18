@extends('layouts.admin')
@section('page-title',__lang('verify-code'))
@section('pageTitle',__lang('verify-code'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('verify-code')
        ]])
@endsection

@section('content')
    <div class="card">
     <div class="card-header">

         <form action="{{ route('admin.student.code') }}" method="get">
             <input type="search" class="form-control" value="{{ request()->code }}"  name="code" placeholder="{{ __lang('enter-enrollment-code') }}">

         </form>

    </div>
    <div class="card-body">
        @if($students)
            <div >
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('code') }}</th>
                        <th>{{ __lang('student') }}</th>
                        <th>{{ __lang('course') }}</th>
                        <th>{{ __lang('enrolled-on') }}</th>
                        <th  >{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $row)
                    <tr>
                        <td><span class="label label-success">{{ $row->reg_code }}</span></td>
                        <td>{{ $row->student->user->name }} {{ $row->student->user->last_name }}</td>
                        <td>
                            @php
                                $course_name = explode(' - Batch ',$row->course->name);
                                $course_name = @$course_name[0];
                            @endphp
                            {{ $course_name }}
                        </td>
                        <td> {{ getDateString($row->created_at) }}
                        </td>
                        <td >
                            <a href="{{ adminUrl(array('controller'=>'session','action'=>'stats','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('student-progress') }}"><i class="fa fa-chart-bar"></i></a>


                            <button   data-id="{{ $row->student_id }}" data-toggle="modal" data-target="#simpleModal" title="Student Details" type="button" class="btn btn-xs btn-primary btn-equal viewbutton"  ><i class="fa fa-user"></i></button>

                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>

                {{ $students->appends(request()->input())->links() }}
            </div><!--end .col-lg-12 -->
        @endif
    </div>
    </div>



@endsection

@section('footer')

    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="simpleModalLabel">{{ __lang('student-details') }}</h4>

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
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
            });
        });
    </script>
@endsection
