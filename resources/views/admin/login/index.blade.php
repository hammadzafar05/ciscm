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
<section class="login_content">
    @php 
    if(!isset($form))
    {
        $form = new Application\Form\LoginForm();
    }
    $form->prepare();

    $form->setAttribute('action', $this->url('admin/process'));


    $form->setAttribute('method', 'post');
    $form->setAttribute('id', 'loginform');
    $form->setAttribute('class', 'form-horizontal');

    $form->get('email')->setAttribute('class','form-control');
    $form->get('email')->setAttribute('placeholder','Email');
    $form->get('email')->setAttribute('id','admin-login-email');

    $form->get('password')->setAttribute('class','form-control');
    $form->get('password')->setAttribute('placeholder','Password');
    $form->get('rememberme')->setAttribute('id','checkbox-signup');
    $form->get('password')->setAttribute('id','admin-login-password');


    echo $this->form()->openTag($form);

    @endphp
    {{ formElement($form->get('security')) }}
        <h1> @php if(isset($loginerror) && $loginerror):  @endphp

                {{ __lang('invalid-login') }}
            @php else: @endphp

                {{ __lang('sign-in') }}
            @php endif;  @endphp</h1>
    {{ $this->alert($this->flashMessenger()->render(),@$action->mode) }}
    {{ $this->alert(@$message,@$action->mode) }}
        <div>
            {{ formElement($form->get('email')) }}
        </div>
        <div>
            {{ formElement($form->get('password')) }}
        </div>
    <div class="checkbox checkbox-success" style="text-align: right">
        @php 
        echo formElement($form->get('rememberme'));
        @endphp
        <label for="checkbox-signup">
            {{ __lang('remember-me') }}
        </label>
    </div>
        <div>

            <button class="btn w-md btn-bordered btn-primary waves-effect waves-light" type="submit">{{ __lang('login') }}</button>

            <a class="reset_pass" href="{{ $this->url('admin/reset') }}">{{ __lang('lost-password') }}</a>
        </div>

        <div class="clearfix"></div>

        <div class="separator">



            <div>
                <a href="{{ basePath() }}/"> <img style="max-width: 100%;max-height: 100%" src="@php if(TE_CREDITS):  @endphp{{ basePath() }}/img/logo-black.png@php else:  @endphp{{ basePath() }}/{{ getSetting('image_logo') }}@php endif;  @endphp" alt=""/></a>

            </div>
        </div>
    </form>
</section>
@endsection
