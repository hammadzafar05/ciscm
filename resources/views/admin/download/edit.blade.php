@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.download.index')=>__lang('downloads'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

<div class="card"   ng-app="myApp" ng-controller="myCtrl" >
    <div class="card-body">

       <ul class="nav nav-pills" id="myTab3" role="tablist">
                             <li class="nav-item">
                               <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __lang('details') }}</a>
                             </li>
                             <li class="nav-item">
                               <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('manage-files') }}</a>
                             </li>
                             <li class="nav-item">
                               <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false">{{ __lang('manage-sessions') }}</a>
                             </li>
                           </ul>
                           <div class="tab-content" id="myTabContent2">
                             <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                                 <div >


                                     <form id="editform" method="post" action="{{ adminUrl(['controller'=>'download','action'=>'edit','id'=>$row->id]) }}">
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



                                         <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-floppy-o"></i> {{ __lang('save') }} </button>



                                     </form>




                                 </div>

                             </div>
                             <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                                 <div>
                                     <button onclick="image_upload()" id="addFileBtn" class="btn btn-primary">{{ __lang('Add File') }}</button>
                                     <input id="file_name" type="hidden" name="file_name"/>
                                     <p><small>{{ __lang('allowed-file-types') }}: pdf, zip, mp4, mp3, doc, docx, ppt, pptx, xls, xlsx, png, jpeg, gif, txt, csv</small></p>
                                 </div>
                                 <div id="filelist">
                                     {!! $files !!}
                                 </div>
                             </div>
                             <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
                                 <div>
                                     <button onclick="openLargeModal('Select Sessions','{{ adminUrl(['controller'=>'download','action'=>'browsesessions','id'=>$id]) }}')" id="addSessionBtn" class="btn btn-primary">{{ __lang('add-session') }}</button>
                                 </div>
                                 <br>
                                 <div id="sessionlist">
                                     {!! $sessions !!}
                                 </div>


                             </div>
                           </div>
    </div>
</div>

@endsection

@section('footer')

    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('hcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>
    <script type="text/javascript"><!--
        function image_upload() {
            var field = 'file_name';
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{__lang('select-file')}}',
                close: function (event, ui) {

                    if ($('#' + field).attr('value')) {
                        $('#filelist').text('{{__lang('loading')}}...');
                        $.ajax({
                            url: '{{ basePath() }}/admin/download/addfile/{{ $id }}?&path=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                //$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                                //$('#filelist').load('{{ basePath() }}/admin/download/files/{{ $id }}');
                                $('#filelist').html(data);
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



        //-->
        $(function(){
            $(document).on('click','.delete',function(e){
                e.preventDefault();
                $('#filelist').text('{{__lang('loading')}}...');
                $('#filelist').load($(this).attr('href'));
            });

            $(document).on('click','#genmodalinfo a',function(e){
                e.preventDefault();
                $('#genmodalinfo').text('{{__lang('loading')}}...');
                $('#genmodalinfo').load($(this).attr('href'));
            });

            $(document).on('click','.delete-session',function(e){
                e.preventDefault();
                $('#sessionlist').text('{{__lang('loading')}}...');
                $('#sessionlist').load($(this).attr('href'));
            });

        })

    </script>


@endsection
