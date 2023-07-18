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
            Mailbox
            @if($unreadMessages)
                <small>{{$unreadMessages}} new messages</small>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/mailbox') }}"> Mailbox</a></li>
            <li class="active">Compose</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ url('admin/mailbox') }}" class="btn btn-primary btn-block margin-bottom">Back to inbox</a>

                @include('pages.mailbox.includes.folders_panel')
            </div>
            <div class="col-md-9">
                <form method="post" action="{{ url('admin/mailbox-create') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Compose New Message</h3>
                        </div>

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                    @endif

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <?php $selected_receivers = old('receiver_id') ?>
                                <select name="receiver_id" id="receiver_id" class="form-control required select2"  data-placeholder="Select Recipient" >
                                    <option></option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $selected_receivers!=null && ($user->id=$selected_receivers)?"selected":"" }}>
                                            {{ $user->name.' '.$user->last_name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="subject" placeholder="Subject:" value="{{ old("subject")!=null?old("subject"):"" }}">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" class="form-control" name="body" style="height: 300px">
                                    {{ old("body")!=null?old("body"):"" }}
                                </textarea>
                            </div>
                            <div class="form-group">
                                <div class="btn btn-primary btn-file">
                                    <i class="fa fa-paperclip"></i> Attachments
                                    <input type="file" name="attachments[]" multiple>
                                </div>
                                <p class="help-block">Max. {{ (int)(ini_get('upload_max_filesize')) }}M</p>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" name="submit" value="2" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                                <button type="submit" name="submit" value="1" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                            </div>
                            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /. box -->
                </form>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('compose-textarea', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
    </script>

@endsection