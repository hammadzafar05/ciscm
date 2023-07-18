@extends('layouts.admin')

@section('pageTitle',__('default.templates'))
@section('innerTitle',__('default.colors').': '.$template->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.templates')=>__('default.site-theme'),
            '#'=>__('default.colors')
        ]])
@endsection



@section('content')

    <form action="{{ route('admin.templates.save-colors') }}" method="post">
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th class="int_txcen">@lang('default.original-color')</th>
                    <th>@lang('default.new-color')</th>
                </tr>
            </thead>
            <tbody>

            @foreach($colorList as $color)
                <tr>
                    <td class="int_txcen">
                        @section('header')
                            @parent
                        <style>
                            .cls{{ $loop->index }}{
                                background-color: #{{ $color }}
                            }
                        </style>
                        @endsection
                        <div class="int_colorstyle" class="cls{{ $loop->index }}"></div>
                    #{{ $color }}
                    </td>
                    <td>
                        <div class="input-group myColorPicker">
                        <input type="text" class="form-control colorpicker-full"  name="{{ $color }}_new" @if($template->templateColors()->where('original_color',$color)->first()) value="{{ $template->templateColors()->where('original_color',$color)->first()->user_color }}" @endif>
                        </div>

                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>
        <button class="btn btn-primary btn-block">@lang('default.save-changes')</button>
    </form>

@endsection


@section('header')

    <link href="{{ asset('client/vendor/jquery-ui-1.11.4/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/vendor/colorpicker/jquery.colorpicker.css') }}" rel="stylesheet">
@endsection

@section('footer')
    <script src="{{ asset('client/vendor/jquery-ui-1.11.4/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('client/vendor/colorpicker/jquery.colorpicker.js') }}"></script>



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

        });
    </script>


@endsection
