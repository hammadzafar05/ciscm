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

<div  >
    @php
    $form->prepare();
    $form->setAttribute('action', adminUrl(['controller'=>'setting','action'=>$action.'admin','id'=>$id]));
    $form->setAttribute('method', 'post');
    $form->setAttribute('role', 'form');
    $form->setAttribute('class', 'form-horizontal');

    echo $this->form()->openTag($form);
    @endphp




    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('first_name')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('first_name')) }}   <p class="help-block">{{ formElementErrors($form->get('first_name')) }}</p>
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
                    <label for="password1" class="control-label">{{ formLabel($form->get('email')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('email')) }}   <p class="help-block">{{ formElementErrors($form->get('email')) }}</p>
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
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('password')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('password')) }}   <p class="help-block">{{ formElementErrors($form->get('password')) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('confirm_password')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('confirm_password')) }}   <p class="help-block">{{ formElementErrors($form->get('confirm_password')) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('role_id')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('role_id')) }}   <p class="help-block">{{ formElementErrors($form->get('role_id')) }}</p>
                </div>
            </div>
        </div>


        @php if($action='add'): @endphp
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ __lang('send-acc-details') }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    <input type="checkbox" value="1" name="senddetails" checked/>  </div>
            </div>
        </div>
        @php endif;  @endphp
    </div>



    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('account_description')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('account_description')) }}   <p class="help-block">{{ formElementErrors($form->get('account_description')) }}</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password1" class="control-label">{{ formLabel($form->get('account_status')) }}</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    {{ formElement($form->get('account_status')) }}   <p class="help-block">{{ formElementErrors($form->get('account_status')) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group" style="margin-bottom:10px">

                <label for="image" class="control-label">{{ __lang('profile-picture') }}</label><br />


                <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                    {{ formElement($form->get('picture')) }}
                    <a class="pointer" onclick="image_upload('image', 'thumb');">{{__lang('browse')}}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{__lang('clear')}}</a></div>

            </div>
        </div>

    </div>

    <div class="form-footer col-lg-offset-1 col-md-offset-2 col-sm-offset-3">
        <button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
    </div>
    </form>
</div>



<!--container ends-->

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
