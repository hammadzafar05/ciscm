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

    <section class="content-header display-hide">
        <h1>
            Show Mail
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/mailbox') }}"> Mailbox</a></li>
            <li class="active">Show Mail</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ url('admin/mailbox') }}" class="btn btn-primary btn-block margin-bottom"><i class="fa fa-reply"></i> Back to Inbox</a>

                @include('pages.mailbox.includes.folders_panel')
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Read Mail</h3>
                    </div>

                    @include('includes.flash_message')

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ $mailbox->subject }}</h3>
                            <h5>
                                From: {{ $mailbox->sender->email }}
                                <span class="mailbox-read-time pull-right">{{ !empty($mailbox->time_sent)?date("d M. Y h:i A", strtotime($mailbox->time_sent)):"not sent yet" }}</span>
                            </h5>
                        </div>

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            {!! $mailbox->body !!}
                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->
                    @if($mailbox->attachments->count() > 0)
                    <div class="box-footer">

                        @include('pages.mailbox.includes.attachments', ['mailbox' => $mailbox])

                    </div>
                    @endif
                    @if($mailbox->replies->count() == 0)
                    <div class="box-footer">
                        <div class="pull-right">
                            <a href="{{ url('admin/mailbox-reply',['id'=>$mailbox->id]) }}" class="btn btn-primary"><i class="fa fa-reply"></i> Reply</a>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /. box -->

                @if($mailbox->replies->count() > 0)
                    <h3>Replies</h3>
                    @foreach($mailbox->replies as $reply)
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><strong>From: </strong>{{ $reply->sender->name }}</h3>
                            </div>
                            <div class="box-body no-padding">
                                <div class="mailbox-read-info">
                                    <h3>{{ $reply->subject }}</h3>
                                    <h5>From: {{ $reply->sender->email }}
                                        <span class="mailbox-read-time pull-right">{{ !empty($reply->time_sent)?date("d M. Y h:i A", strtotime($reply->time_sent)):"not sent yet" }}</span></h5>
                                </div>
                                <div class="mailbox-read-message">
                                    {!! $reply->body !!}
                                </div>
                            </div>
                            @if($reply->attachments->count() > 0)
                            <div class="box-footer">
                                @include('pages.mailbox.includes.attachments', ['mailbox' => $reply])
                            </div>
                            @endif
                            @if($loop->iteration == $mailbox->replies->count())
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="{{ url('admin/mailbox-reply',['id'=>$mailbox->id]) }}" class="btn btn-primary"><i class="fa fa-reply"></i> Reply</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                @endif

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection