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
@php $this->headScript()->prependFile(basePath().'/client/vendor/loader/js/jquery.loadingModal.min.js')
 @endphp
{{ $this->headLink()->prependStylesheet(basePath().'/client/vendor/loader/css/jquery.loadingModal.min.css') }}
<div class="row">

    <form id="importform" class="form" action="{{selfURL()}}" method="post">

        <div class="form-group" style="padding-bottom: 20px">

            <div style="margin-bottom: 10px"><strong>Selected File:</strong> <span id="filename"></span> </div>
            <button type="button" onclick="image_upload()" id="addFileBtn" class="btn btn-primary"><i class="fa fa-plus"></i> Select Powerpoint File</button>
            <div>
                {{formElement($form->get('path'))}}
            </div>
        </div>

        <div class="form-group">
            {{formLabel($form->get('title')) }}
            {{formElement($form->get('title')) }}
        </div>


        <button class="btn btn-primary" type="submit">Import File</button>



    </form>


</div>

<script type="text/javascript">


    function image_upload() {
        var field = 'path';
        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: '{{__lang('Select A File')}}',
            close: function (event, ui) {

                if ($('#' + field).attr('value')) {
                    console.log($('#' + field).attr('value'));
                    var fileName = $('#' + field).attr('value');
                    var file = fileName.substring(fileName.lastIndexOf('/')+1);
                    var extension = file.substring(file.lastIndexOf('.')+1);
                    extension = extension.toLowerCase();
                    if(extension=='ppt' || extension=='pptx'|| extension=='odp')
                    {
                        $('#filename').text(file);
                    }
                    else{
                        alert('Please select a Powerpoint/ODP file only');
                        $('#path').val('');
                        $('#filename').text('');
                    }





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


    $('#importform').submit(function(e){

        if($('#path').val()==''){
            e.preventDefault();
            alert('Please select a Powerpoint/ODP file to import');
        }
        else{
            $('body').loadingModal({
                text: 'Importing File. Please wait...'
            })
        }
    });

</script>


@endsection
