@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
    <div class="alert alert-primary">
        {{ __lang('add-mul-img-lect') }}
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" action="{{selfURL()}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript>
                    {{ __lang('enable-javascript-img') }}
                </noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row mb-2 fileupload-buttonbar">
                    <div class="col-lg-7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                    <i class="fa fa-plus-circle"></i>
                    <span>{{ __lang('add-images') }}...</span>
                    <input type="file" name="files" multiple>
                </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="fa fa-upload"></i>
                            <span>{{ __lang('start-upload') }}</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="fa fa-ban"></i>
                            <span>{{ __lang('cancel-upload') }}</span>
                        </button>

                        <input style="display: none" type="checkbox" class="toggle">
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <div class="card">
                <div class="card-body">
                    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                </div>
                </div>

            </form>

        </div>
    </div>

    <!-- The blueimp Gallery widget -->
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">{{ __lang('processing') }}...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>{{ __lang('start') }}</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>{{ __lang('cancel') }}</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>{{ __lang('delete') }}</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } %}

        </td>
    </tr>
{% } %}
</script>

    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="{{ basePath() }}/client/vendor/javascript-templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="{{ basePath() }}/client/vendor/javascript-load-image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="{{ basePath() }}/client/vendor/javascript-canvas-to-blob/js/canvas-to-blob.min.js"></script>
    <!-- blueimp Gallery script -->
    <script src="{{ basePath() }}/client/vendor/gallery/js/jquery.blueimp-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-process.js"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-image.js"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
    <!-- The File Upload video preview plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-video.js"></script>
    <!-- The File Upload validation plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
    <!-- The main application script -->
    <!--<script src="<?/*=basePath()*/?>/client/vendor/jquery-file-upload/js/main.js"></script>-->
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
    <!--[if (gte IE 8)&(lt IE 10)]>
<script src="{{ basePath() }}/client/vendor/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->

    <script>
        $(function () {
            'use strict';

            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: '{{selfURL()}}',
                maxFileSize: 209715200,
                acceptFileTypes: /(\.|\/)(jpg|jpeg|png|gif)$/i
            });


        });

    </script>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/gallery/css/blueimp-gallery.min.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/jquery-file-upload/css/jquery.fileupload.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/jquery-file-upload/css/jquery.fileupload-ui.css' }}">
@endsection
