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
<div>

<div class="col-md-12">

    <div class="card">


					<div class="card-header">
                        <button   data-toggle="modal" data-target="#formModal" type="button" class="btn btn-rounded btn-primary"><i class="fa fa-plus"></i>{{ __lang('add-widget') }}</button>

					</div>
					<div class="card-body">
						<div id="options">


                            <div id="accordion">

                                @php $counter= 1 ; @endphp
                                @php foreach($form as $key=>$value):  @endphp

                                <div class="accordion">
                                    <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-{{ $key }}" aria-expanded="false">
                                        <h4>{{ __lang($value['name']) }}</h4>
                                    </div>
                                    <div class="accordion-body collapse" id="panel-body-{{ $key }}" data-parent="#accordion" style="">


                                        <form class="widget-ajaxform" role="form"  method="post" action="{{ adminUrl(array('controller'=>'widget','action'=>'process','id'=>$key)) }}">
                                            @csrf  <div class="row" style="margin-bottom:10px">
                                                <div class="col-md-3">
                                                    <span >{{ formElement($value['enabled']) }}</span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span >{{ formElement($value['sortOrder']) }}</span>
                                                </div>
                                                <div class="col-md-4 offset-3">
                            <span class="float-right"><button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> {{__lang('save-changes')}}</button>
                            <a onclick="return confirm('{{__lang('remove-widget-confirm')}}')" class="btn btn-primary" href="{{ adminUrl(array('controller'=>'widget','action'=>'delete','id'=>$key)) }}"><i class="fa fa-trash"></i> {{ __lang('remove') }}</a>
                            </span>
                                                </div>

                                            </div>



                                            @php if(!empty($value['description'])):  @endphp
                                            <div style="padding:10px">
                                                {{ __lang($value['code'].'-dis') }}
                                            </div>
                                            @php endif;  @endphp

                                            <div style="border-top:solid 1px #CCCCCC; padding-top:20px">

                                                {!! $value['form'] !!}

                                            </div>



                                        </form>


                                    </div>
                                </div>
                                @php endforeach;  @endphp



                            </div>









			                </div>


					</div>




    </div>

</div>

</div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/izitoast/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/colorpicker/jquery.colorpicker.css') }}">
@endsection

@section('footer')

    <script src="{{ asset('client/themes/admin/assets/modules/izitoast/js/iziToast.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/colorpicker/jquery.colorpicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script type="text/javascript">


        $(document).on('submit','form.widget-ajaxform',function(e){


            e.preventDefault();
            iziToast.info({
                title: '{{ __lang('info') }}',
                message: '{{ __lang('sending') }}',
                position: 'topRight'
            });

            var postData = $(this).serialize();


            var formURL = $(this).attr("action");


            $.ajax(
                {
                    url : formURL,
                    type: "POST",
                    data : postData,
                    dataType: "json",
                    //  contentType: "application/json",
                    headers: {
                        Accept:"application/json"
                    },
                    success:function(data, textStatus, jqXHR)
                    {

                        //data: return data from server
                        if(data.status)
                        {
                            iziToast.success({
                                title: '{{ __lang('done') }}',
                                message: data.message,
                                position: 'topRight'
                            });
                        }
                        else
                        {
                            iziToast.error({
                                title: '{{ __lang('error') }}',
                                message: data.message,
                                position: 'topRight'
                            });
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        iziToast.error({
                            title: '{{ __lang('error') }}',
                            message: '{{ __lang('submission-failed') }} '+errorThrown,
                            position: 'topRight'
                        });
                    }
                });



        });

        @php foreach ($editors as $value) {  @endphp
        CKEDITOR.replace('{{ $value }}', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
        @php }  @endphp
    </script>

    <script type="text/javascript"><!--
        function image_upload(field, thumb) {
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{addslashes(__lang('Image Manager'))}}',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: '{{ basePath() }}/admin/filemanager/image?&image=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                            }
                        });
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
        //-->
    </script>
    <script type="text/javascript">
        $(function() {
            $('.colorpicker').colorpicker({
                parts:          'full',
                showOn:         'both',
                buttonColorize: true,
                showNoneButton: true,
                buttonImage : '{{ basePath()}}/client/vendor/colorpicker/images/ui-colorpicker.png'
            });
        });
    </script>

    <!-- START FORM MODAL MARKUP -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">{{ __lang('create-new-widget') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                </div>
                <form class="form-horizontal" role="form" method="post" action="{{ adminUrl(array('controller'=>'widget','action'=>'create')) }}">
                 @csrf   <div class="modal-body">
                        <div class="form-group">
                            <div  >
                                <label for="email1" class="control-label">{{ __lang('widget-type') }}</label>
                            </div>
                            <div  >
                                {{ formElement($createSelect)}}
                            </div>
                        </div>
                        <div class="form-group">
                            <div  >
                                <label for="password1" class="control-label">{{ __lang('sort-order') }}</label>
                            </div>
                            <div >
                                <input required="required" type="text" name="sort_order" value="1"  class="form-control" placeholder="Sort Order">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('cancel') }}</button>
                        <button type="submit" class="btn btn-primary" >{{ __lang('create') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END FORM MODAL MARKUP -->



@endsection
