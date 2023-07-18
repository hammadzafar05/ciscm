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


<form action="{{ adminUrl(array('controller'=>'setting','action'=>'index')) }}" method="post">
    @csrf
<div class="row mb-3">
    <div   >
        <button class="btn btn-primary float-right" type="submit"><i class="fa fa-save"></i> {{__lang('save-changes')}}</button>
    </div><!--end .col-lg-12 -->
</div>

<div >
    <div >



        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills" data-toggle="tabs">
                    <li class="nav-item"><a class="nav-link active"  href="#general" data-toggle="tab"><i class="fa fa-fw fa-cogs"></i> {{ __lang('general') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#logo" data-toggle="tab"><i class="fa fa-fw fa-images"></i> {{ __lang('logo-icon') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#menu" data-toggle="tab"><i class="fa fa-fw fa-list"></i> {{ __lang('menu') }}</a></li>
                     <li class="nav-item"><a class="nav-link"  href="#labels" data-toggle="tab"><i class="fa fa-fw fa-tag"></i> {{ __lang('labels') }}</a></li>
                     <li class="nav-item"><a class="nav-link"  href="#regis" data-toggle="tab"><i class="fa fa-fw fa-user-plus"></i> {{ __lang('registration') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#mail" data-toggle="tab"><i class="fa fa-fw fa-envelope"></i> {{ __lang('mail') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#info" data-toggle="tab"><i class="fa fa-fw fa-info"></i> {{ __lang('info') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#social" data-toggle="tab"><i class="fa fa-fw fa-sign-in-alt"></i> {{ __lang('social-login') }}</a></li>
                    <li class="nav-item"><a class="nav-link"  href="#banner" data-toggle="tab"><i class="fa fa-fw fa-mobile"></i> {{ __lang('smart-banners') }}</a></li>
                </ul>
            </div>

            <div class="box-body tab-content">
                <div class="tab-pane active " id="general">

                    <div class="form-group">
                        <div class="col-md-12">
                            {{ formLabel($form->get('country_id')) }}
                        </div>
                        <div class="col-md-12">

                            {{ formElement($form->get('country_id')) }}

                        </div>
                    </div>


                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^general_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-12">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp






                </div>

                <div class="tab-pane fade" id="logo">


                    <div class="form-group col-sm-6" style="margin-bottom:10px">

                        <label for="image" class="control-label">{{ __lang('logo') }}</label><br />


                        <div class="image"><img data-name="image" src="{{ $logo }}" alt="" id="thumb_logo" /><br />
                            {{ formElement($form->get('image_logo')) }}
                            <a class="pointer" onclick="image_upload('image_logo', 'thumb_logo');">{{__lang('browse')}}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb_logo').attr('src', '{{ $no_image }}'); $('#image_logo').attr('value', '');">{{__lang('clear')}}</a></div>

                    </div>

                    <div class="form-group col-sm-6" style="margin-bottom:10px">

                        <label for="image" class="control-label">{{ __lang('favicon') }}</label><br />


                        <div class="image"><img data-name="image" src="{{ $icon }}" alt="" id="thumb_icon" /><br />
                            {{ formElement($form->get('image_icon')) }}
                            <a class="pointer" onclick="image_upload('image_icon', 'thumb_icon');">{{__lang('browse')}}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb_icon').attr('src', '{{ $no_image }}'); $('#image_icon').attr('value', '');">{{__lang('clear')}}</a></div>

                    </div>



                    <div class="form-group">
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-7">



                        </div>
                    </div>




                </div>

                <div class="tab-pane fade" id="menu">
                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^menu_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-4">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-7">
                                        {{ formElement($form->get($row->key)) }}
                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp
                </div>

                <div class="tab-pane fade" id="labels">

                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^label_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-12">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp

                </div>

                <div class="tab-pane fade" id="regis">

                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^regis_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-12">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp

                </div>

                <div class="tab-pane fade" id="mail">

                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^mail_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-12">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp

                </div>

                <div class="tab-pane fade" id="info">

                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^info_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-12">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp

                </div>

                <div class="tab-pane fade" id="social">
                    <p>
                        <h4>{{ __lang('callback-urls') }}</h4>
                    <ul>
                        <li>Facebook: {{$siteUrl}}/student/social-login?network=Facebook</li>
                        <li>Google: {{$siteUrl}}/student/social-login?network=Google</li>
                    </ul>
                    </p>
                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^social_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-4">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-7">
                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp
                </div>

                <div class="tab-pane fade" id="banner">
                    @php foreach($settings as $row): @endphp
                        @php if(preg_match('#^banner_(.*)$#i',$row->key)): @endphp
                            <div class="form-group">
                                <div class="col-sm-4">
                                    {{ formLabel($form->get($row->key)) }}
                                </div>
                                <div class="col-sm-7">

                                        {{ formElement($form->get($row->key)) }}

                                </div>
                            </div>
                        @php endif;  @endphp
                    @php endforeach;  @endphp
                </div>

            </div>

        </div>
    </div><!--end .col-lg-12 -->
</div>



 </form>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/colorpicker/jquery.colorpicker.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ basePath() }}/client/vendor/colorpicker/jquery.colorpicker.js"></script>
    @php foreach($settings as $row): @endphp
    @php if($row->class == 'rte'): @endphp
    <script type="text/javascript">

        CKEDITOR.replace('rte_{{ $row->key }}', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>
    @php endif;  @endphp

    @php endforeach;  @endphp


    <script type="text/javascript">
        $(function() {
            $('.colorpicker-full').colorpicker({
                parts:          'full',
                showOn:         'both',
                buttonColorize: true,
                showNoneButton: true,
                buttonImage : '{{ basePath()}}/static/colorpicker/images/ui-colorpicker.png'
            });
        });
    </script>

    <script type="text/javascript"><!--
        function image_upload(field, thumb) {
            console.log('Field: '+field+'. Thumb:'+thumb);
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
                    else{
                        console.log('no field content');
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
