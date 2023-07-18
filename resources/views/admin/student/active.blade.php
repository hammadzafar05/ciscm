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
<div >
    <div >
        <div class="card">
            <div class="card-header">
                <header>

                    <p class="well">{{ __lang('active-student-def') }}</p>
                </header>

            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ __lang('id') }}</th>
                            <th></th>
                            <th>{{ __lang('first-name') }}</th>
                            <th>{{ __lang('last-name') }}</th>
                            <th>{{ __lang('enrolled-courses') }}</th>
                            <th>{{ __lang('last-seen') }}</th>
                            <th class="text-right1"  >{{__lang('actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php foreach($paginator as $row):  @endphp
                            <tr>
                                <td><span class="label label-success">{{ $row->student_id }}</span></td>
                                <td>
                                    <img  class="mr-3 rounded-circle"    width="50" src="{{ profilePictureUrl($row->picture) }}" />
                                </td>
                                <td>{{ htmlentities($row->name) }}</td>
                                <td>{{ htmlentities($row->last_name) }}</td>
                                <td><strong>{{ $studentSessionTable->getTotalForStudent($row->student_id) }}</strong>

                                </td>
                                <td>{{showDate('d/M/Y',$row->last_seen)}}</td>

                                <td >
                                    <a href="{{ adminUrl(array('controller'=>'student','action'=>'edit','id'=>$row->student_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                                    <a href="#" onclick="openModal('{{__lang('enroll')}}','{{ adminUrl(array('controller'=>'student','action'=>'enroll','id'=>$row->student_id)) }}')"  data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('Enroll') }}"   title="{{ __lang('Enroll') }}" type="button" class="btn btn-xs btn-primary btn-equal"  ><i class="fa fa-plus"></i></a>

                                    <button   data-id="{{ $row->student_id }}" data-toggle="modal" data-target="#simpleModal" title="View" type="button" class="btn btn-xs btn-primary btn-equal viewbutton"  ><i class="fa fa-eye"></i></button>

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
                        'controller'=>'student',
                        'action'=>'active'
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>
@endsection

@section('footer')

    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="simpleModalLabel">{{ __lang('student-details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="info">

                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('{{__lang('loading')}}...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
            });
        });
    </script>

@endsection
