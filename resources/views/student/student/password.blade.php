@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
<div class="card">
<div class="card-body">

    <form method="post" action="{{ route('student.student.password')  }}">
        @csrf
        <div class="form-group">
            <label for="password">{{  __lang('new-password')  }}</label>
            <input class="form-control" type="password" name="password" required="required"/>

        </div>

        <div class="form-group">
            <label for="confirm_password">{{  __lang('Confirm Password')  }}</label>
            <input class="form-control" type="password" name="password_confirmation" required="required"/>

        </div>


        <div class="form-footer">
            <button type="submit" class="btn btn-primary">{{  __lang('Submit')  }}</button>
        </div>

    </form>


</div>
</div>





@endsection
