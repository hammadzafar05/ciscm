@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.discuss.index')=>__lang('instructor-chat'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')


<div >
    <div class="row">
        <div class="col-md-12">
            <h3>{{ __lang('question') }}</h3>
            <div class="card">
                <div class="card-header">
                    {{ __lang('on') }} {{ showDate('r',$row->created_at) }} {{ __lang('by') }} <a class="viewbutton" style="text-decoration: underline" href="#"  data-id="{{ $row->student_id }}" data-toggle="modal" data-target="#simpleModal">{{ $row->name.' '.$row->last_name }}</a>
                    . {{ __lang('recipients') }}:
                    @php if($row->admin==1): @endphp
                    {{ __lang('administrators') }},
                    @php endif;  @endphp

                    @php foreach($accounts as $row2):  @endphp
                    {{ $row2->name.' '.$row2->last_name }},
                    @php endforeach;  @endphp

                </div>
                <div class="card-body">

                    <h4> {{ $row->subject }}</h4>

                    <blockquote>
                        {!! clean(nl2br($row->question)) !!}
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">


            <form method="post" class="form" action="{{ adminUrl(['controller'=>'discuss','action'=>'addreply','id'=>$row->id]) }}">
             @csrf   <div class="form-group">
                    <textarea required="required" placeholder="{{ __lang('reply-here') }}" class="form-control" name="reply" id="reply"  rows="3">{{ old('reply') }}</textarea>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-lg btn-primary">{{ __lang('reply') }}</button>
                </div>
            </form>

        </div>
    </div>
@php if(!empty($total)): @endphp
    <div class="row">
        <div class="col-md-12">
            <h3>{{ __lang('replies') }}</h3>
            @php foreach($paginator as $row):  @endphp

            <div class="card">
                <div class="card-header">
                    {{ __lang('by') }} <strong> {{ $row->name }} {{ $row->last_name }}  @if($row->role_id==1)({{ __lang('Admin') }})@endif
                   </strong> {{ __lang('on') }} {{ showDate('r',$row->created_at) }}
                </div>
                <div class="card-body">
                    <p>{!! clean(nl2br($row->reply)) !!}</p>
                </div>

            </div>
            @php endforeach;  @endphp


        </div>
    </div>
@php endif;  @endphp






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
            'controller'=>'discuss',
            'action'=>'viewdiscussion',
            'id'=>$row->id

        )
    );
    @endphp
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
