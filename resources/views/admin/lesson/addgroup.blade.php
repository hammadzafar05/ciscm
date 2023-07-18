@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.lesson.groups')=>__lang('class-groups'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <div >
        <div class="card">

            <div class="card-body">

                <form method="post" action="{{ adminUrl(array('controller'=>'lesson','action'=>$action,'id'=>$id)) }}">
                    @csrf



                <div class="form-group">
                    {{ formLabel($form->get('name')) }}
                    {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

                </div>




                <div class="form-group">
                    {{ formLabel($form->get('description')) }}
                    {{ formElement($form->get('description')) }}   <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

                </div>



                <div class="form-group">
                    {{ formLabel($form->get('sort_order')) }}
                    {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

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
