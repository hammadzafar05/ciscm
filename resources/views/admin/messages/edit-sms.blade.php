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
        <li class="nav-item"><a  class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('message')}}</a></li>
        <li class="nav-item"><a  class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('placeholders')}}</a></li>
        <li class="nav-item"><a class="nav-link"  href="#defaulttab" aria-controls="defaulttab" role="tab" data-toggle="tab">{{__lang('default-message')}}</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="well">
             {{__lang('s-template-desc-'.$template->id)}}
            </div>
            <form action="{{ selfURL() }}" method="post">
            @csrf
                <div class="form-group">
                    <label for="smsmessage">{{__lang('message')}}</label>
                    <textarea rows="6"  required="required"  name="message" id="smsmessage" class="form-control summernote">{{ $template->message }}</textarea>
                    <p>
                        <span id="remaining">160 {{__lang('characters-remaining')}}</span>
                        <span id="messages">1 {{__lang('messages')}}</span>
                    </p>
                    <small>{{__lang('sms-template-help')}}.</small>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__lang('save')}}</button>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">{!!  $template->placeholders  !!}</div>
        <div role="tabpanel" class="tab-pane" id="defaulttab">

            <div class="well">
                <strong>{{__lang('message')}}</strong>
                <hr>
                <p>{!!  $template->default  !!}</p></div>
            <a href="{{ adminUrl(['controller'=>'messages','action'=>'resetsms','id'=>$template->id]) }}" onclick="return confirm('{{__lang('restore-default-help')}}')" class="btn btn-primary"><i class="fa fa-refresh"></i> {{__lang('restore-default')}}</a>

        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
        var $remaining = $('#remaining'),
            $messages = $remaining.next();

        $('#smsmessage').keyup(function(){
            var chars = this.value.length,
                messages = Math.ceil(chars / 160),
                remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

            $remaining.text(remaining + ' {{__lang('characters-remaining')}}');
            $messages.text(messages + ' {{__lang('messages')}}');
        });

        $('#smsmessage').trigger('keyup');
    });

</script>
@endsection
