@extends('layouts.admin')

@section('pageTitle',__('default.edit').' '.__('default.administrator').': '.$admin->name)
@section('innerTitle',__('default.edit').' '.__('default.administrator').': '.$admin->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.admins.index')=>__lang('administrators'),
            '#'=>__lang('edit')
        ]])
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ url('/admin/admins') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        <br />
                        <br />



                        <form method="POST"
                              action="{{ url('/admin/admins/' . $admin->id) }}"
                              accept-charset="UTF-8"
                              id="form_ids"
                              class="form-horizontal"
                              enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.admins.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/select.js') }}" type="text/javascript"></script>
    <script>
        $(".upload_image").on("change", function () {
            var id = $(this).prop('id');
            var image_name = $('#'+id).data('image_name');
            var image_width = $('#'+id).data('image_width');
            var image_height = $('#'+id).data('image_height');
            var thumbs_width = $('#'+id).data('thumbs_width');
            var thumbs_height = $('#'+id).data('thumbs_height');

            var form = $(this).closest('form')[0];

            var formData = new FormData($(this).closest('form')[0]);
            var file_data = $("#"+id).prop("files")[0];
            formData.append("image", file_data);
            formData.append("image_width", image_width);
            formData.append("image_height", image_height);
            formData.append("thumbs_width", thumbs_width);
            formData.append("thumbs_height", thumbs_height);

            var settings = {
                "async": true,
                "crossDomain": true,
                "url": "{{ route('ajaxupload.image') }}",
                "method": "POST",
                "processData": false,
                "contentType": false,
                "mimeType": "multipart/form-data",
                "data": formData
            }

            $('#nameSubmitBtn').hide();
            jQuery('#loader_'+image_name).removeClass('display-hide');
            jQuery('#image_preview_'+image_name).hide();

            $.ajax(settings).done(function (response) {
                var response = $.parseJSON(response);

                alert(response.message);

                $('#nameSubmitBtn').show();
                jQuery('#loader_'+image_name).addClass('display-hide');

                jQuery('#image_preview_'+image_name).attr("src", response.data.path);
                jQuery('#image_preview_'+image_name).show();

                if(response.status == 'success') {
                    $('<input>').attr('type','hidden')
                        .attr('id','uploaded_image_'+image_name)
                        .attr('class','form-control')
                        .attr('name',image_name)
                        .attr('value',response.data.name)
                        .appendTo('#'+form.id);
                }else{
                    $('#'+id).val('');
                }
            });
        });
    </script>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">


@endsection
