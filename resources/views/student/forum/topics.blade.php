@extends(studentLayout())
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.forum.index')=> __lang('student-forum'),
            '#'=>__lang('topics')
        ]])
@endsection

@section('content')
@include('student.forum.topics-content')

@endsection
