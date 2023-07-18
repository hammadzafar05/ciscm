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
@php $this->layout('layout/adminlogin');  @endphp
<section class="login_content" >
    @php 




    $form->prepare();
    //$form->setAttribute('action', basePath().'/users/login/process');
    $form->setAttribute('action', $this->url('admin/change-password').'?token='.$token);
    $form->setAttribute('method', 'post');
    $form->setAttribute('id', 'loginform');
    $form->setAttribute('class', 'form-horizontal');

    $form->get('password')->setAttribute('class','form-control');
    $form->get('password')->setAttribute('placeholder','New Password');

    $form->get('confirm_password')->setAttribute('class','form-control');
    $form->get('confirm_password')->setAttribute('placeholder','Confirm Password');


    //$form->get('submit')->setAttribute('class','btn btn-danger');

    echo $this->form()->openTag($form);
    @endphp
        {{ formElement($form->get('security')) }}
        <h1>  @php if(isset($message)):  @endphp
                {{ $message }}
            @php else:  @endphp
                Change Your Password
            @php endif;  @endphp</h1>
        <div>
            @php 
            echo formElement($form->get('password'));
            echo formElementErrors($form->get('password'));
            @endphp
        </div>

        <div>
            @php 
            echo formElement($form->get('confirm_password'));
            echo formElementErrors($form->get('confirm_password'));
            @endphp
        </div>

        <div>

            <button class="btn w-md btn-bordered btn-primary waves-effect waves-light" type="submit">Change Password</button>

            <a class="reset_pass" href="{{ $this->url('admin/signin') }}">Login</a>
        </div>

        <div class="clearfix"></div>

        <div class="separator">



            <div>
                <img style="max-width: 100%;max-height: 100%" src="@php if(TE_CREDITS):  @endphp{{ basePath() }}/img/logo-black.png@php else:  @endphp{{ basePath() }}/{{ getSetting('image_logo') }}@php endif;  @endphp" alt=""/>

            </div>
        </div>
    </form>
</section>
@endsection
