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
			<div >
				<div class="card">

					<div class="card-body">

                        <form method="post" action="{{ adminUrl(array('controller'=>'certificate','action'=>$action,'id'=>$id)) }}">
                            @csrf


									<div class="form-group">
											{{ formLabel($form->get('name')) }}
										 {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

									</div>


                        <div class="form-group">
                            {{ formLabel($form->get('course_id')) }}
                            {{ formElement($form->get('course_id')) }}   <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>

                        </div>

						 	<div class="form-group">
											{{ formLabel($form->get('description')) }}
										 {{ formElement($form->get('description')) }}   <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

									</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ formLabel($form->get('enabled')) }}
                                    {{ formElement($form->get('enabled')) }}   <p class="help-block">{{ formElementErrors($form->get('enabled')) }}</p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ formLabel($form->get('orientation')) }}
                                    {{ formElement($form->get('orientation')) }}   <p class="help-block">{{ formElementErrors($form->get('orientation')) }}</p>

                                </div>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ formLabel($form->get('max_downloads')) }}
                                    {{ formElement($form->get('max_downloads')) }}   <p class="help-block">{{ formElementErrors($form->get('max_downloads')) }}</p>
                                    <p class="help-block">{{ __lang('download-help') }}</p>
                                </div>
                            </div>

                        </div>






                        <div class="form-group" style="margin-bottom:10px">

									<label for="image" class="control-label">{{ __lang('certificate-image') }}(A4 {{ __lang('size') }} - 595 {{ __lang('pixels') }} x 842 {{ __lang('pixels') }})</label><br />


                               <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                  {{ formElement($form->get('image')) }}
                  <a class="pointer" onclick="image_upload('image', 'thumb');">{{ __lang('browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>

</div>












							<div class="form-footer">
								<button type="submit" class="btn btn-primary">{{ __lang('save') }}</button>
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
    <script type="text/javascript">
        function image_upload(field, thumb) {
            console.log('image upload');
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{ addslashes(__lang('Image Manager')) }}',
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

    </script>

@endsection
