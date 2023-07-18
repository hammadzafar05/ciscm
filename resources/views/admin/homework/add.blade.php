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
<div   ng-app="myApp" ng-controller="myCtrl"  >
			<div >
				<div class="card">

					<div class="card-body">

                        <form method="post" action="{{ adminUrl(array('controller'=>'homework','action'=>$action,'id'=>$id)) }}">
@csrf

									<div class="form-group">
											{{ formLabel($form->get('title')) }}
										 {{ formElement($form->get('title')) }}   <p class="help-block">{{ formElementErrors($form->get('title')) }}</p>

									</div>
														<div class="form-group">
											{{ formLabel($form->get('course_id')) }}
										 {{ formElement($form->get('course_id')) }}   <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>

									</div>

                        <div class="form-group">
                            {{ formLabel($form->get('lesson_id')) }}
                            {{ formElement($form->get('lesson_id')) }}   <p class="help-block">{{ formElementErrors($form->get('lesson_id')) }}</p>

                        </div>


                        <div class="form-group">
											{{ formLabel($form->get('description')) }}
										 {{ formElement($form->get('description')) }}   <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

									</div>



						 	<div class="form-group">
											{{ formLabel($form->get('content')) }}
										 {{ formElement($form->get('content')) }}   <p class="help-block">{{ formElementErrors($form->get('content')) }}</p>

									</div>


                        <div class="form-group">
                            <input checked type="checkbox" name="notify" value="1"/> {{ __lang('notify-session-students') }}?
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
    <script type="text/javascript" src="{{ asset('client/js/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/app/attendance.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>


    <script type="text/javascript">

        CKEDITOR.replace('hcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        var basePath = '{{ basePath() }}';
    </script>

@endsection
