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


<!--breadcrumb-section ends-->
<!--container starts-->
<div class="card">
<div class="card-body">
    <div>
        <form method="post" action="{{ adminUrl(['controller'=>'account','action'=>'profile']) }}">
            @csrf

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label for="password1" class="control-label">{{ formLabel($form->get('name')) }}</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label for="password1" class="control-label">{{ formLabel($form->get('last_name')) }}</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {{ formElement($form->get('last_name')) }}   <p class="help-block">{{ formElementErrors($form->get('last_name')) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label for="password1" class="control-label">{{ formLabel($form->get('about')) }}</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {{ formElement($form->get('about')) }}   <p class="help-block">{{ formElementErrors($form->get('about')) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label for="password1" class="control-label">{{ formLabel($form->get('notify')) }}</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {{ formElement($form->get('notify')) }}   <p class="help-block">{{ formElementErrors($form->get('notify')) }}</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"  >
                        <div class="col-lg-8 col-md-8 col-sm-6">
                        <label for="image" class="control-label">{{ __lang('profile-picture') }}</label><br />


                        <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                            {{ formElement($form->get('picture')) }}
                            <a class="pointer" onclick="image_upload('image', 'thumb');">{{ __lang('browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"  >
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>
</div>





<!--container ends-->
<script type="text/javascript"><!--
    function image_upload(field, thumb) {
        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: '{{addslashes(__lang('Image Manager'))}}',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: '{{ basePath() }}/admin/filemanager/image?&image=' + encodeURIComponent($('#' + field).val()),
                        dataType: 'text',
                        success: function(data) {
                            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 800,
            height: 570,
            resizable: true,
            modal: false,
            position: "center"
        });
    };

    //--></script>
@endsection
