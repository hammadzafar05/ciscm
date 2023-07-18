@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.index')=>__lang('students'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div >
    <div class="card">
        <div class="card-header">
             {{ __lang('edit') }} &nbsp;  <strong>{{ $row->user->name }} {{ $row->user->last_name }}</strong>
        </div>
        <div class="card-body">

              <ul class="nav nav-pills" id="myTab3" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __lang('student-details') }}</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('change-password') }}</a>
                                    </li>
                                  </ul>
                                  <div class="tab-content" id="myTabContent2">
                                    <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">

                                        <form enctype="multipart/form-data" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>$action,'id'=>$id)) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('name')) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('last_name')) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('last_name')) }}   <p class="help-block">{{ formElementErrors($form->get('last_name')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>








                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('email')) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('email')) }}   <p class="help-block">{{ formElementErrors($form->get('email')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>




                                            </div>










                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('mobile_number')) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('mobile_number')) }}   <p class="help-block">{{ formElementErrors($form->get('mobile_number')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('status')) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('status')) }}   <p class="help-block">{{ formElementErrors($form->get('status')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="row">



                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('picture')) }}</label>
                                                        </div>
                                                        <div >
                                                         <div>
                                                            @php if(!empty($row->user->picture) && isUrl($row->user->picture)): @endphp
                                                            <img src="{{$row->user->picture}}" style="max-width: 200px" alt=""/>
                                                            @php elseif(!empty($row->user->picture) && isImage($row->user->picture)): @endphp
                                                            <img src="{{ resizeImage($row->user->picture,200,200,basePath()) }}" alt=""/>

                                                            @php endif;  @endphp
                                                        </div>
                                                    @php if(!empty($row->user->picture)):  @endphp
                                                            <br>
                                                            <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this display picture?')" href="{{adminUrl(['controller'=>'student','action'=>'removeimage','id'=>$row->id])}}"><i class="fa fa-trash"></i> {{ __lang('Remove image') }}</a>
                                                            <br><br>
                                                           @php endif;  @endphp
                                                            {{ formElement($form->get('picture')) }} <p class="help-block">{{ formElementErrors($form->get('picture')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>







                                            </div>









                                            <div class="row">

                                                @php foreach($fields as $row): @endphp

                                                <div class="col-sm-6">
                                                    <div class="form-group">

                                                        @php if($row->type == 'checkbox'): @endphp

                                                        <div >
                                                            {{ formElement($form->get('custom_'.$row->id)) }}
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('custom_'.$row->id)) }}</label>
                                                            <p class="help-block">{{ formElementErrors($form->get('custom_'.$row->id)) }}</p>
                                                        </div>

                                                        @php elseif($row->type == 'radio'):  @endphp
                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('custom_'.$row->id)) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ $this->formRadio($form->get('custom_'.$row->id)) }}
                                                            <p class="help-block">{{ formElementErrors($form->get('custom_'.$row->id)) }}</p>
                                                        </div>
                                                        @php else:  @endphp

                                                        <div >
                                                            <label for="password1" class="control-label">{{ formLabel($form->get('custom_'.$row->id)) }}</label>
                                                        </div>
                                                        <div >
                                                            {{ formElement($form->get('custom_'.$row->id)) }}   <p class="help-block">{{ formElementErrors($form->get('custom_'.$row->id)) }}</p>
                                                        </div>

                                                        @php endif;  @endphp


                                                    </div>
                                                </div>

                                                @php endforeach;  @endphp

                                            </div>










                                            <div class="form-footer col-lg-offset-1 col-md-offset-2 col-sm-offset-3">
                                                <button type="submit" class="btn btn-primary btn-block">{{__lang('save-changes')}}</button>
                                            </div>

                                        </form>





                                    </div>
                                    <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">


                                        <form method="post" class="form form-horizontal"
                                              action="{{ adminUrl( ['controller' => 'student', 'action' => 'changepassword', 'id' => $id]) }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="">{{ __lang('new-password') }}</label>
                                                <input required class="form-control" type="password" name="password"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{ __lang('confirm-password') }}</label>
                                                <input required class="form-control" type="password" name="confirm_password"/>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" name="notify" value="1" checked/>
                                                <label for="">{{ __lang('send-new-password') }}</label>
                                            </div>
                                            <button class="btn btn-primary btn-block" type="submit">{{__lang('save')}}</button>
                                        </form>




                                    </div>

                                  </div>

        </div><!--end .box -->
    </div><!--end .col-lg-12 -->


</div>
@endsection
