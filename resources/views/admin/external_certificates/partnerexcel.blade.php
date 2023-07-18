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

    <div class="card">
        <div class="card-header">
            Import External Certificates
        </div>
        <div class="card-body">
            <form onsubmit="return confirm('You are about to import external certificates')"
                  enctype="multipart/form-data"
                  class="form"
                  method="post"
                  action="{{ url('admin/partner/import') }}">
                @csrf
                <p>
                    Download the CSV template file here: <a href="<?php echo basePath().'/client/data/sample_partner_demo.csv';?>"> sample.csv </a>. Ensure that you do not modify the column names.
                </p>

                <div class="form-group" style="padding-bottom: 10px">
                    <label for="file">{{ __lang('csv-file') }}</label>
                    <input required="required" name="file" type="file"/>
                </div>

                <button class="btn btn-primary" type="submit">{{ __lang('import') }}</button>
            </form>
        </div>
    </div>
@endsection
