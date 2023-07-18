@extends('layouts.admin')
@section('page-title',__lang('edit').': '.smsInfo($smsGateway->directory)['name'])
@section('innerTitle',__lang('edit').': '.smsInfo($smsGateway->directory)['name'])
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.smsgateway.index')=>__lang('sms-gateways'),
            '#'=>__lang('edit')
        ]])
@endsection

@section('content')
    <form method="post" action="{{ route('admin.smsgateway.save',['smsGateway'=>$smsGateway->id]) }}">
        @csrf
        <div >
            <div >
                <div class="card">

                    <div class="card-body">


                        @include($form,$settings)

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input @if($smsGateway->default==1) checked @endif class="form-check-input" type="checkbox" id="default" value="1" name="default">
                                <label class="form-check-label" for="default">{{ __lang('default-gateway') }}</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div >
                <button class="btn btn-primary" type="submit">{{__lang('save-changes')}}</button>
            </div><!--end .col-lg-12 -->
        </div>

    </form>




@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

    <link href="{{ asset('client/vendor/jquery-ui-1.11.4/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/vendor/colorpicker/jquery.colorpicker.css') }}" rel="stylesheet">
@endsection

@section('footer')
    <script src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('client/vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('client/vendor/jquery-ui-1.11.4/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('client/vendor/colorpicker/jquery.colorpicker.js') }}"></script>

    <script src="{{ asset('client/js/textrte.js') }}"></script>

    <script>
        "use strict";

        $(document).ready(function(){


            $('.colorpicker-full').colorpicker({
                parts:          'full',
                showOn:         'both',
                buttonColorize: true,
                showNoneButton: true,
                buttonImage : '{{ asset('client/vendor/colorpicker/images/ui-colorpicker.png') }}'
            });


            $('.option-form').on('submit',function(e){
                e.preventDefault();
                /!*Ajax Request Header setup*!/
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.toast('@lang('default.saving')');

                /!* Submit form data using ajax*!/
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'post',
                    data: $(this).serialize(),
                    success: function(response){
                        //------------------------
                        $.toast('@lang('default.changes-saved')');
                        //--------------------------
                    }});
            });
        });



    </script>

    @include('admin.partials.image-browser')



@endsection
