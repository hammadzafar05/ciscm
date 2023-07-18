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
<div>

    <!-- Nav tabs -->
    <ul class="nav nav-pills" role="tablist">
        <li class="nav-item"><a  class="nav-link active"  href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('message')}}</a></li>
        <li class="nav-item"><a class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('placeholders')}}</a></li>
        <li class="nav-item"><a class="nav-link" href="#messages" aria-controls="messages" role="tab" data-toggle="tab">{{__lang('default-message')}}</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="well">
                {{__lang('e-template-desc-'.$template->id)}}
            </div>
            <form action="{{ selfURL() }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="subject">{{__lang('subject')}}</label>
                    <input required="required" type="text" class="form-control" name="subject" value="{{ $template->subject }}">
                </div>

                <div class="form-group">
                    <label for="message">{{__lang('message')}}</label>
                    <textarea  required="required"  name="message" id="message" class="form-control summernote">{{ $template->message }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__lang('save')}}</button>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">{!!  $template->placeholders  !!}</div>
        <div role="tabpanel" class="tab-pane" id="messages">
            <p><strong>{{__lang('subject')}}:</strong> {{ $template->default_subject }}</p>
            <hr>

            <div class="well">
                <strong>{{__lang('message')}}</strong>
                <hr>
                <p>{!!  $template->default  !!}</p></div>
            <a href="{{ adminUrl(['controller'=>'messages','action'=>'resetemail','id'=>$template->id]) }}" onclick="return confirm('{{__lang('restore-default-help')}}')" class="btn btn-primary"><i class="fa fa-refresh"></i> {{__lang('restore-default')}}</a>
        </div>
    </div>

</div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote-ext-emoji/src/css-new-version.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/summernote-ext-emoji/src/summernote-ext-emoji.js') }}"></script>
    <script>
        $(function() {
            $('.summernote').summernote({  height: 300 });
        });
    </script>
@endsection
