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
    <form class="form" action="{{ $this->url('admin/reset') }}" method="post">
    {{ formElement($form->get('security')) }}
    <h1>  @php if(isset($message)):  @endphp
            {{ $message }}
        @php else:  @endphp
            {{ __lang('password-reset') }}
        @php endif;  @endphp</h1>
    <div>
        <input class="form-control" type="text" name="email" placeholder="{{ __lang('your-email') }}"/>
    </div>


    <div>

        <button class="btn w-md btn-bordered btn-primary waves-effect waves-light" type="submit">{{ __lang('reset') }}</button>

        <a class="reset_pass" href="{{ $this->url('admin/signin') }}">{{ __lang('login') }}</a>
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
