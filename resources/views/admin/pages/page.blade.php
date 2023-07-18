{{--MARUF START--}}
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
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <form action="#"
                      id="sampleForm">
                    @csrf
                    <div class="panel-body">
                        <div class="form-result"></div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Header</label>
                                    <input id="name"
                                           class="form-control input-sm required"
                                           name="name"
                                           value="@if(isset($output['data'])){{ $output['data']['name'] }} @else {{ $pageTitle }} @endif" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <textarea id="description"
                                              class="form-control input-sm required ckeditor"
                                              name="description"
                                              rows="15">@if(isset($output['data'])){{ $output['data']['description'] }}@endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-footer">
                        <input type="hidden"
                               id="existing_slug"
                               name="existing_slug"
                               value="{{ $output['slug'] }}">
                        <button type="submit"
                                id="idSubmitData"
                                class="btn btn-sm btn-success pull-right btn-add-client"
                                value="Add"
                                name="nameSubmitBtn"
                                data-loading-text="<em class='fa fa-circle-notch fa-spin'></em> Processing">
                            <em class="fa fa-check"></em> Save
                        </button>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection


@section('footer')
<link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/izitoast/css/iziToast.min.css') }}">
<script src="{{ asset('client/themes/admin/assets/modules/izitoast/js/iziToast.min.js') }}" type="text/javascript"></script>

<script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('js/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('js/jquery-validate/jquery.validate.min.js')}}"></script>
<script>
    (function($) {
        $.fn.button = function(action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
    $("#sampleForm").validate({
        ignore: "",
        errorElement: 'label',
        errorClass: 'error',
        rules: {
            description: {
                required: function(textarea) {
                    var editorId = $(textarea).attr('id');
                    var editorContent = CKEDITOR.instances[editorId].getData();
                    var editorCon = $(editorContent).text().trim();
                    return editorCon.length === 0;
                }
            },
        },
        messages: {
            description: {
                required: "Please enter description",
            },
        },
        errorPlacement: function (error, element) {
            if(element.hasClass('select2') && element.next('.select2-container').length) {
                error.insertAfter(element.next('.select2-container'));
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
            else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                error.insertAfter(element.parent().parent());
            }
            else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                error.appendTo(element.parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var temp = $('button[name=nameSubmitBtn]').val();
            $('button[name=nameSubmitBtn]').button('loading');
            var datastring = $("#sampleForm").serialize() + "&temp=" + temp;
            var module_link = $('#navz').val();
            $.ajax({
                type: "POST",
                url: "./{{ $output['slug'] }}/store",
                data: datastring,
                success: function(response) {

                    $('button[name=nameSubmitBtn]').button('reset');

                    if (response.status == 'success') {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status == 'error') {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else {
                        iziToast.error({
                            message: 'Unable to save into database. Please try again!',
                            position: 'topRight'
                        });
                        $('button[name=nameSubmitBtn]').button('reset');
                    }
                },
                error: function(jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Not connect. Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Requested page not found. [404].');
                    } else if (jqXHR.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Time out error.');
                    } else if (exception === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        alert('Uncaught Error.' + jqXHR.responseText);
                    }
                    $('button[name=nameSubmitBtn]').button('reset');
                }
            });
            return false;
        }
    });
</script>
@endsection
{{--MARUF END--}}