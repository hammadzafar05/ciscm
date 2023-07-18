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
<div class="card">
    <div class="card-body">
        <p>
            {{ __lang('update-instructions') }}
        </p>


        @php if($file): @endphp
        <form method="POST"
            action="{{ adminUrl( ['controller' => 'setting', 'action' => 'migrate']) }}">
            @csrf
            <button class="btn btn-primary" type="submit">{{ __lang('update') }}</button>
        </form>
        @php else:  @endphp
           <strong> {{ __lang('no-update-file') }}</strong>

        @php endif;  @endphp





    </div>
</div>
@endsection
