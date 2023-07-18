@extends('layouts.admin')

@section('pageTitle',__('default.templates'))
@section('innerTitle',__('default.customize').': '.$template->name)

@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.templates')=>__('default.site-theme'),
            '#'=>__('default.customize')
        ]])
@endsection


@section('content')





    <a href="{{ route('admin.templates') }}" title="@lang('default.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
    <br/><br/>

    <div class="accordion" id="accordionExample">
       @foreach($settings as $key=>$option)
        <div class="accordion int_overvis"  >
            <div class="accordion-header" id="heading{{ $key }}" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                <h4>
                        {{ $option['name'] }}
                </h4>
            </div>
            <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                <div class="accordion-body">
                   <p>{{ $option['description'] }}</p>

                    <form class="option-form" action="{{ route('admin.templates.save-options',['option'=>$key]) }}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row">
                            <div class="col-md-3">
                                {{ Form::select('enabled', ['1'=>__('default.enabled'),'0'=>__('default.disabled')], $option['enabled'], ['class'=>'form-control']) }}
                            </div>
                            <div class="col-md-9">
                                <button class="btn btn-primary float-right" type="submit">@lang('default.save-changes')</button>

                            </div>
                        </div>
                        <hr/>
                    @if(file_exists('./templates/'.currentTemplate()->directory.'/assets/previews/'.$key.'.jpg'))

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home-{{ $key }}">@lang('default.settings')</a>
                            </li>
                           <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1-{{ $key }}">@lang('default.demo')</a>
                            </li>

                        </ul>
                    @endif
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active container px-2 pt-4" id="home-{{ $key }}">


                                @include($option['form'],$option['values'])

                            </div>
                            @if(file_exists('./templates/'.currentTemplate()->directory.'/assets/previews/'.$key.'.jpg'))

                            <div class="tab-pane container px-2 pt-4" id="menu1-{{ $key }}">
                               <img src="{{ tasset('previews/'.$key.'.jpg') }}" class="img-fluid">


                            </div>
                            @endif

                        </div>






                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>


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
