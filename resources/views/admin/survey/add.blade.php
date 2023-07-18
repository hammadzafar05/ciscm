@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.survey.index')=>__lang('surveys'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <div >
        <div class="card">

            <div class="card-body">

                <form method="post" action="{{ adminUrl(array('controller'=>'survey','action'=>$action,'id'=>$id)) }}">
@csrf

                <div class="form-group">
                    {{ formLabel($form->get('name')) }}
                    {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

                </div>
                <div class="form-group">
                    {{ formLabel($form->get('description')) }}
                    {{ formElement($form->get('description')) }}   <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

                </div>


                <div class="form-group">
                    {{ formLabel($form->get('enabled')) }}
                    {{ formElement($form->get('enabled')) }}   <p class="help-block">{{ formElementErrors($form->get('enabled')) }}</p>

                </div>

                <div class="form-group">
                    {{ formLabel($form->get('private')) }}
                    {{ formElement($form->get('private')) }}   <p class="help-block">{{ formElementErrors($form->get('private')) }}</p>

                    <p class="help-block">{{ __lang('private-survey-msg') }}</p>
                </div>




                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
                </div>
                 </form>
            </div>
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>
@endsection
