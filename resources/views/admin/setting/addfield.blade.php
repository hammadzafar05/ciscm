@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.setting.fields')=>__lang('Custom Student Fields'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
			<div >
				<div class="card">

					<div class="card-body">

                        <form method="post" action="{{ adminUrl(array('controller'=>'setting','action'=>$action,'id'=>$id)) }}">
                            @csrf


									<div class="form-group">
											{{ formLabel($form->get('name')) }}
										 {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

									</div>







                        <div class="form-group">
                            {{ formLabel($form->get('type')) }}
                            {{ formElement($form->get('type')) }}   <p class="help-block">{{ formElementErrors($form->get('type')) }}</p>

                        </div>


                        <div class="form-group options">
                            {{ formLabel($form->get('options')) }}
                            {{ formElement($form->get('options')) }}   <p class="help-block">{{ formElementErrors($form->get('options')) }}</p>

                        </div>




                        <div class="form-group">
                            {{ formLabel($form->get('required')) }}
                            {{ formElement($form->get('required')) }}   <p class="help-block">{{ formElementErrors($form->get('required')) }}</p>

                        </div>




                        <div class="form-group hint">
                            {{ formLabel($form->get('placeholder')) }}
                            {{ formElement($form->get('placeholder')) }}   <p class="help-block">{{ formElementErrors($form->get('placeholder')) }}</p>

                        </div>


                        <div class="form-group">
                            {{ formLabel($form->get('sort_order')) }}
                            {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

                        </div>

                        <div class="form-group">
                            {{ formLabel($form->get('enabled')) }}
                            {{ formElement($form->get('enabled')) }}   <p class="help-block">{{ formElementErrors($form->get('enabled')) }}</p>

                        </div>





                        <div class="form-footer">
								<button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
							</div>
						 </form>
					</div>
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>


@endsection

@section('footer')
    <script>
        $('select[name=type]').change(function(){
            showOptions();
        });

        function showOptions(){
            if($('select[name=type]').val()=='select' || $('select[name=type]').val()=='radio' ){
                $('.options').show();
            }
            else{
                $('.options').hide();
            }

        }

        showOptions();
    </script>

    <script>
        $('select[name=type]').change(function(){
            showHint();
        });

        function showHint(){
            if($('select[name=type]').val()=='text' || $('select[name=type]').val()=='textarea' ){
                $('.hint').show();
            }
            else{
                $('.hint').hide();
            }

        }

        showHint();
    </script>

@endsection
