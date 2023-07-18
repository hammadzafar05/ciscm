@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('/admin/external_certificates')=>__lang('external_certificates'),
            '#'=>__('default.add')
        ]])
@endsection
@section('pageTitle',__('default.create-new').' '.__('default.external_certificate'))
@section('innerTitle',__('default.create-new').' '.__('default.external_certificate'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ url('/admin/external_certificates') }}"
                           title="@lang('default.back')">
                            <button class="btn btn-warning btn-sm">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')
                            </button>
                        </a>
                        <br />
                        <br />
                        <form method="POST"
                              action="{{ url('/admin/external_certificates') }}"
                              accept-charset="UTF-8"
                              class="form-horizontal"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include ('admin.external_certificates.form', ['formMode' => 'create'])
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ basePath() . '/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script type="text/javascript">

        CKEDITOR.replace('textcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        jQuery('#issue_date').pickadate({
            format: 'dd-mm-yyyy'
        });


    </script>
@endsection
