@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            adminUrl(['controller'=>'lesson','action'=>'index'])=>__lang('Classes'),
            adminUrl(['controller'=>'lecture','action'=>'index','id'=>$row->lesson_id])=>__lang('Class Lectures'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
<div>
			<div >
				<div class="card">

					<div class="card-body">


                        <form method="post" action="{{ adminUrl(array('controller'=>'lecture','action'=>$action,'id'=>$id)) }}">
                            @csrf

									<div class="form-group">
											{{ formLabel($form->get('title')) }}
										 {{ formElement($form->get('title')) }}   <p class="help-block">{{ formElementErrors($form->get('title')) }}</p>

									</div>










                        <div class="form-group">
                            {{ formLabel($form->get('sort_order')) }}
                            {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

                        </div>






							<div class="form-footer">
								<button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
							</div>
						 </form>
					</div>
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>


@endsection


@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('hcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

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
        $(function(){


            if($('select[name=lecture_type]').val()!='c'){
                $('.online').hide();
            };

            $('select[name=lecture_type]').change(function(){
                if($(this).val()=='c'){
                    $('.online').show();
                }
                else{
                    $('.online').hide();
                }

            });

        });
        //--></script>
@endsection
