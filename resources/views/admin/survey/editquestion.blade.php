@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
         {{ __lang('edit-question') }}
    </div>
    <div class="card-body">

        <form method="post" action="{{ adminUrl(array('controller'=>'survey','action'=>'editquestion','id'=>$id)) }}">
        @csrf
        <div class="form-group">
            {{ formLabel($form->get('question')) }}
            {{ formElement($form->get('question')) }}   <p class="help-block">{{ formElementErrors($form->get('question')) }}</p>

        </div>

        <div class="form-group">
            {{ formLabel($form->get('sort_order')) }}
            {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
        </div>
         </form>

    </div>
</div>

<div class="card">
    <div class="card-header">
         {{ __lang('Edit Options') }}
    </div>
    <div class="card-body">
        <button data-toggle="modal" data-target="#myModal" class="btn btn-primary float-right"><i class="fa fa-plus"></i>  {{ __lang('Add Options') }}</button>
        <br><br>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ __lang('option') }}</th>
                <th>{{__lang('actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @php foreach($rowset as $row):  @endphp
                <tr>
                    <td>{{ $row->option }}</td>


                    <td>

                        <a href="#" onclick="openModal('{{__lang('edit-option')}}','{{ adminUrl(array('controller'=>'survey','action'=>'editoption','id'=>$row->id)) }}');"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>

                        <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'survey','action'=>'deleteoption','id'=>$row->id)) }}"  class="btn btn-xs btn-danger btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @php endforeach;  @endphp

            </tbody>
        </table>

    </div>
</div>

@endsection
@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
@endsection

@section('footer')
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ __lang('add-options') }}</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __lang('close') }}"><span aria-hidden="true">&times;</span></button>

                </div>
                <form id="questionform" method="post" action="{{ adminUrl(['controller'=>'survey','action'=>'addoptions','id'=>$id]) }}">
@csrf
                    <div class="modal-body">

                        <p><small>{{ __lang('edit-question-help') }}</small></p>
                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>{{ __lang('option') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php for($i=1;$i<=5;$i++): @endphp
                            <tr>
                                <td><input name="option_{{ $i }}" class="form-control" type="text"/></td>
                            </tr>
                            @php endfor;  @endphp
                            </tbody>
                        </table>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('cancel') }}</button>
                        <button  type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function(){

            $('.summernote').summernote({
                height: 200
            } );
        });
    </script>

@endsection
