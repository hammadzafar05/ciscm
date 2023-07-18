@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.lesson.index')=>__lang('classes'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
			<div >
				<div class="card">

					<div class="card-body">

                                           <form action="{{ adminUrl(array('controller'=>'lesson','action'=>$action,'id'=>$id)) }}" method="post">
                                                @csrf


									<div class="form-group">
											{{ formLabel($form->get('name')) }}
										 {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

									</div>


                        <div class="form-group">
                            {{ formLabel($form->get('type')) }}
                            {{ formElement($form->get('type')) }}   <p class="help-block">{{ formElementErrors($form->get('type')) }}</p>

                        </div>



                        <div class="form-group online">
                            {{ formElement($form->get('test_required')) }}  {{ formLabel($form->get('test_required')) }}

                            <p class="help-block">{{ formElementErrors($form->get('test_required')) }}</p>
                            <p class="help-block">{{ __lang('test-required-help') }}</p>
                        </div>


                        <div id="test_id_box" class="form-group online">
                            {{ formLabel($form->get('test_id')) }}
                            {{ formElement($form->get('test_id')) }}   <p class="help-block">{{ formElementErrors($form->get('test_id')) }}</p>

                        </div>

                        <div class="form-group online">
                            {{ formElement($form->get('enforce_lecture_order')) }}   {{ formLabel($form->get('enforce_lecture_order')) }}

                            <p class="help-block">{{ __lang('enforce-lecture-order-help') }}</p>

                        </div>



                        <div class="form-group">
											{{ formLabel($form->get('description')) }}
										 {{ formElement($form->get('description')) }}

                            <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

									</div>

                        <div class="form-group online">
                            {{ formLabel($form->get('introduction')) }}

                            {{ formElement($form->get('introduction')) }}

                            <p class="help-block">{{ formElementErrors($form->get('introduction')) }}</p>

                        </div>


                        <div class="form-group">
                            {{ formLabel($form->get('lesson_group_id[]')) }}
                            {{ formElement($form->get('lesson_group_id[]')) }}   <p class="help-block">{{ formElementErrors($form->get('lesson_group_id[]')) }}</p>

                        </div>


                        <div class="form-group">
                            {{ formLabel($form->get('sort_order')) }}
                            {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

                        </div>
    <div class="form-group mb-5" >

									<label for="image" class="control-label">{{ __lang('cover-image') }}  ({{ __lang('optional') }})</label><br />


                               <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                  {{ formElement($form->get('picture')) }}
                  <a class="pointer" onclick="image_upload('image', 'thumb');">{{ __lang('browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>

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

        CKEDITOR.replace('hintroduction', {
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







        @if(false)
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
        @endif

        $(function(){


            if($('select[name=type]').val()!='c'){
                $('.online').hide();
            };

            $('select[name=type]').change(function(){
                if($(this).val()=='c'){
                    $('.online').show();
                }
                else{
                    $('.online').hide();
                }

            });

        });
  </script>

@endsection
