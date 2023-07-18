@extends($layout)
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.student.surveys')=>__lang('surveys'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
    <div class="card">
    <div class="card-body">
        {{  __lang('survey-thanks')  }}
    </div>
    </div>

@endsection
