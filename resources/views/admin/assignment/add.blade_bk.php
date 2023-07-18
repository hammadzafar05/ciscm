@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.assignment.index')=>__lang('homework'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('content')
<div   ng-app="myApp" ng-controller="myCtrl"  >
			<div >
				<div class="card">

					<div class="card-body">

                        <form method="post" action="{{ adminUrl(array('controller'=>'assignment','action'=>$action,'id'=>$id)) }}">
@csrf
									<div class="form-group">
											{{ formLabel($form->get('title')) }}
										 {{ formElement($form->get('title')) }}   <p class="help-block">{{ formElementErrors($form->get('title')) }}</p>

									</div>
														<div class="form-group">
											{{ formLabel($form->get('course_id')) }}


                                                            <select name="course_id" id="course_id"
                                                                    class="form-control select2">
                                                                <option value=""></option>
                                                                @foreach($form->get('course_id')->getValueOptions() as $option)
                                                                    <option @if(old('course_id',$form->get('course_id')->getValue()) == $option['value']) selected @endif data-type="{{ $option['attributes']['data-type'] }}" value="{{ $option['value'] }}">{{$option['label']}}</option>
                                                                @endforeach
                                                            </select>



                                                            <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>

									</div>


                        <div class="form-group class-field">
                            {{ formLabel($form->get('schedule_type')) }}


                            {{ formElement($form->get('schedule_type')) }}   <p class="help-block">{{ formElementErrors($form->get('schedule_type')) }}</p>

                        </div>






                        <div class="form-group scheduled">
                            {{ formLabel($form->get('opening_date')) }}
                            {{ formElement($form->get('opening_date')) }}   <p class="help-block">{{ formElementErrors($form->get('opening_date')) }}</p>

                        </div>



                        <div class="form-group  scheduled">
                            {{ formLabel($form->get('due_date')) }}
                            {{ formElement($form->get('due_date')) }}   <p class="help-block">{{ formElementErrors($form->get('due_date')) }}</p>

                        </div>

                        <div class="form-group post-class">

                        </div>
                        <div id="classbox" class="form-group post-class">
                            </div>







                        <div class="form-group">
											{{ formLabel($form->get('type')) }}
										 {{ formElement($form->get('type')) }}   <p class="help-block">{{ formElementErrors($form->get('type')) }}</p>

									</div>



						 	<div class="form-group">
											{{ formLabel($form->get('instruction')) }}
										 {{ formElement($form->get('instruction')) }}   <p class="help-block">{{ formElementErrors($form->get('instruction')) }}</p>

									</div>

                        <div class="form-group">
                            {{ formLabel($form->get('passmark')) }}
                            {{ formElement($form->get('passmark')) }}   <p class="help-block">{{ formElementErrors($form->get('passmark')) }}</p>

                        </div>


                        <div class="form-group">
                            {{ formElement($form->get('notify')) }} {{ formLabel($form->get('notify')) }}
                        </div>

                        <div class="form-group">
                            {{ formElement($form->get('allow_late')) }} {{ formLabel($form->get('allow_late')) }}
                        </div>
                        <div class="form-group">
                            <input type="checkbox" value="1" name="notify_students" checked/>
                            <label for="">{{ __lang('notify-enrolled') }}</label>

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

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/datatables/media/css/jquery.dataTables.min.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/js/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/app/attendance.js') }}"></script>

    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script type="text/javascript">

        jQuery('.date').pickadate({
            format: 'yyyy-mm-dd'
        });

        CKEDITOR.replace('instruction', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        $(function(){
            $('#course_id').change(function(){
                checkType();

            });

            $('select[name=schedule_type]').change(function(){
                checkSchedule();
            });

        });

        function checkType(selected=null){
            var id = $('#course_id').val();
            console.log(id);
            var type = $('option:selected', $('#course_id')).attr('data-type');
            if(type=='s' || type=='b'){
                $('select[name=schedule_type]').val('s');
                $('.class-field').hide();

            }
            else{
                $('.class-field').show();
            }
            checkSchedule();
            console.log(type);
            $('#classbox').text('Loading...');
            $('#classbox').load('{{ basePath() }}/admin/assignment/sessionlessons/'+id+'?lesson_id='+selected);
        }

        function checkSchedule(){
            $('.scheduled,.post-class').hide();
            var type = $('select[name=schedule_type]').val();
            if(type=='s'){
                $('.scheduled').show();
            }
            else{
                $('.post-class').show();
            }
        }

        @php if($action=='edit'):  @endphp
        checkType('{{ $row->lesson_id }}');
        @php else:  @endphp
        checkType();
        @php endif;  @endphp

        checkSchedule();
    </script>

    <script>
        var basePath = '{{ basePath() }}';
    </script>

@endsection
