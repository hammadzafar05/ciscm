@extends('layouts.admin')
@section('pageTitle',$paymentMethod->name)
@section('innerTitle',$paymentMethod->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.payment-gateways')=>__lang('payment-methods'),
            '#'=>__lang('edit')
        ]])
@endsection

@section('content')
    <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab"  class="nav nav-pills bar_tabs" role="tablist">
            <li class="nav-item" ><a class="nav-link active" href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">{{ __lang('settings') }}</a>
            </li>
            <li id="methodtab" class="nav-item"><a  class="nav-link" href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">{{ __lang('currencies') }}</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane show active" id="tab_content1" aria-labelledby="home-tab">

                <form method="post" action="{{ route('admin.payment-gateways.save',['paymentMethod'=>$paymentMethod->id]) }}">
                    @csrf
                <div >
                    <div >
                        <div class="card">

                            <div class="card-body">


                                @include($form,$settings)

                                <div class="form-group">
                                    <div >
                                        <label for="status">{{ __lang('label') }}</label>
                                    </div>
                                    <div  >
                                        <input required type="text" name="label" value="{{ old('label',$paymentMethod->label) }}" class="form-control">

                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="form-check form-check-inline">
                                        <input  class="form-check-input" id="is_global" type="checkbox" name="is_global" value="1" @if(old('is_global',$paymentMethod->is_global)==1) checked @endif >

                                        <label class="form-check-label" for="inlineCheckbox1">{{ __lang('all-currencies?') }}</label>
                                    </div>


                                </div>


                                <div class="form-group">
                                    <div >
                                        <label for="status">{{ __lang('sort-order') }}</label>
                                    </div>
                                    <div  >
                                        <input name="sort_order" type="text" class="number form-control" value="{{ old('sort_order',$paymentMethod->sort_order) }}">

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
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                <div id="currencylist">



                </div>

            </div>
        </div>
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

    <script type="text/javascript">
        $(function(){
            $('#currencylist').load('{{adminUrl(['controller'=>'payment','action'=>'currencies','id'=>$paymentMethod->id])}}',function(){
                $('.select2').select2();
            });
            $(document).on('submit','#currencyform',function(event){
                var $this = $(this);
                var frmValues = $this.serialize();
                $('#currencylist').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

                $.ajax({
                    type: $this.attr('method'),
                    url: $this.attr('action'),
                    data: frmValues
                })
                    .done(function (data) {
                        $('#currencylist').html(data);
                    })
                    .fail(function () {
                        $('#currencylist').text("{{__lang('error-occurred')}}");
                    });
                event.preventDefault();

            });

            $(document).on('click','#currencylist a.delete',function(e){
                e.preventDefault();
                $('#currencylist').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

                $('#currencylist').load($(this).attr('href'));
            });
        });

        toggleTab();
        $('#is_global').click(function(){
            toggleTab();
        });

        function toggleTab() {
            console.log('checked');
            if ($('#is_global').prop("checked")) {

                $('#methodtab').hide();

            }
            else {


                $('#methodtab').show();

            }
        }
    </script>

@endsection
