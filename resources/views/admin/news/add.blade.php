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
						               @php
$form->prepare();
$form->setAttribute('action', adminUrl(array('controller'=>'news','action'=>$action,'id'=>$id)));
$form->setAttribute('method', 'post');
$form->setAttribute('role', 'form');
$form->setAttribute('class', 'form-horizontal');

echo $this->form()->openTag($form);
@endphp




									<div class="form-group">
											{{ formLabel($form->get('title')) }}
										 {{ formElement($form->get('title')) }}   <p class="help-block">{{ formElementErrors($form->get('title')) }}</p>

									</div>




						 	<div class="form-group">
											{{ formLabel($form->get('content')) }}
										 {{ formElement($form->get('content')) }}   <p class="help-block">{{ formElementErrors($form->get('content')) }}</p>

									</div>


    <div class="form-group" style="margin-bottom:10px">

									<label for="image" class="control-label">{{ __lang('Cover Image') }}</label><br />


                               <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                  {{ formElement($form->get('picture')) }}
                  <a class="pointer" onclick="image_upload('image', 'thumb');">{{__lang('browse')}}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>
                    <div><small>{{ __lang('recommended') }}: 1170px X 711px</small></div>
</div>












							<div class="form-footer">
								<button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
							</div>
						 </form>
					</div>
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>
@php $this->headScript()->prependFile(basePath() . 'client/vendor/ckeditor/ckeditor.js')
      @endphp
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
		title: '{{__lang('image-manager')}}',
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
//--></script>
@endsection
