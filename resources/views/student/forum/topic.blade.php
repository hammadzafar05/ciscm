@extends('layouts.student')
@section('pageTitle',$forumTopic->title)
@section('innerTitle',__lang('forum-topic'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection


@section('content')

    <div class="card">
        <div class="card-header">
            <figure class="avatar mr-2 ">
                <img src="{{ profilePictureUrl($forumTopic->user->picture) }}" >
            </figure>
            <h4>@lang('default.by') {{ $forumTopic->user->name }} @lang('default.on') {{ \Carbon\Carbon::parse($forumTopic->created_at)->format('D d/M/Y') }}</h4>
            <div class="card-header-form">
                <form>
                    <div class="input-group">
                        <a class="btn btn-primary btn-round float-right" href="#replybox"><i class="fa fa-reply"></i> @lang('default.reply')</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @foreach($posts as $thread)
        <div class="card" id="thread{{ $thread->id }}">
            <div class="card-body">
                <div class="tickets">
                    <div class="ticket-content">
                        <div class="ticket-header">
                            <div class="ticket-sender-picture img-shadow">
                                <img src="{{ profilePictureUrl($thread->user->picture) }}" >
                            </div>
                            <div class="ticket-detail">
                                <div class="ticket-title">
                                    <h4>{{ $thread->user->name }}</h4>
                                </div>
                                <div class="ticket-info">
                                    <div class="font-weight-600">{{ \Carbon\Carbon::parse($thread->created_at)->format('D d/M/Y') }}</div>
                                    <div class="bullet"></div>
                                    <div class="text-primary font-weight-600">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="ticket-description thread-text">
                            @if($thread->postReply)
                                <article>
                                    <blockquote class="readmore" style="overflow: hidden">
                                        <div class="ticket-header">
                                            <div class="ticket-sender-picture img-shadow">
                                                <img src="{{ profilePictureUrl($thread->postReply->user->picture) }}" >
                                            </div>
                                            <div class="ticket-detail">
                                                <div class="ticket-title">
                                                    <h4>{{ $thread->postReply->user->name }}</h4>
                                                </div>
                                                <div class="ticket-info">
                                                    <div class="font-weight-600">{{ \Carbon\Carbon::parse($thread->postReply->created_at)->format('D d/M/Y') }}</div>
                                                    <div class="bullet"></div>
                                                    <div class="text-primary font-weight-600">{{ \Carbon\Carbon::parse($thread->postReply->created_at)->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        {!! clean($thread->postReply->message) !!}
                                    </blockquote>
                                </article>
                            @endif
                            <p class="thread-text"> {!! clean($thread->message) !!}</p>
                            <a class="btn btn-sm btn-primary float-right" role="button" data-toggle="collapse" href="#collapseExample{{$thread->id}}" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-reply"></i>   {{ __lang('reply') }}
                            </a>
                            <div class="collapse" id="collapseExample{{$thread->id}}">
                                <div class="well">
                                    <h4>{{ __lang('reply') }}</h4>
                                    <form method="post" action="{{  route('student.forum.reply', ['id' => $id])  }}">
                                        @csrf   <textarea id="message{{$thread->id}}" name="message" class="form-control" rows="5">{{old('message')}}</textarea>
                                        <input type="hidden" name="post_reply_id" value="{{$thread->id}}"/>
                                        <br>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-reply"></i> {{ __lang('reply') }}</button>
                                    </form>
                                    @section('footer')
                                    @parent
                                    <script>
                                        $(function(){
                                            document.emojiSource = '{{ asset('client/vendor/summernote-emoji-master/tam-emoji/img') }}';
                                            document.emojiButton = 'fas fa-smile';
                                            $('#message{{$thread->id}}').summernote({
                                                height: 200,
                                                toolbar: [
                                                    ['style', ['style']],
                                                    ['font', ['bold', 'italic', 'underline', 'clear']],
                                                    ['fontname', ['fontname']],
                                                    ['color', ['color']],
                                                    ['para', ['ul', 'ol', 'paragraph']],
                                                    ['height', ['height']],
                                                    ['table', ['table']],
                                                    ['insert', ['link', 'picture','video', 'hr']],
                                                    ['view', ['fullscreen', 'codeview']],
                                                    ['misc', ['emoji']],
                                                    ['help', ['help']],
                                                ]
                                            } );
                                        });
                                    </script>

                                    @endsection
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($posts->hasPages())
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        {{ $posts->links() }}
                    </div>
                </div>

            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong>{{ __lang('notifications') }}</strong>
        </div>
        <div class="card-body">

            <div style="">
                <input @if($checked) checked @endif type="checkbox" name="notify" id="notify"> <label for="notify">{{ __lang('get-notifications') }}</label>

            </div>

        </div>

    </div>

    <div class="card" id="replybox">
        <div class="card-header">
            <h4>@lang('default.reply')</h4>
        </div>
        <div class="card-body">

            <form method="post" action="{{  route('student.forum.reply', ['id' => $id])  }}">
                @csrf  <textarea id="message-reply" name="message" class="form-control snote" rows="5">{{old('message')}}</textarea>
                <br>
                <button class="btn btn-primary btn-lg" type="submit"><i class="fa fa-reply"></i> {{ __lang('reply') }}</button>
            </form>
        </div>
    </div>



@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote-ext-emoji/src/css-new-version.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/izitoast/css/iziToast.min.css') }}">
    <link href="{{ asset('client/vendor/summernote-emoji-master/tam-emoji/css/emoji.css') }}" rel="stylesheet">
    <style>
        .tickets .ticket-content {
            width: 100%;
        }

    </style>
@endsection

@section('footer')



    <script src="{{ asset('client/vendor/summernote/summernote-bs4.js') }}"></script>

    <script type="text/javascript" src="{{ asset('client/vendor/readmore/readmore.min.js') }}"></script>

    <script src="{{ asset('client/vendor/summernote-emoji-master/tam-emoji/js/config.js') }}"></script>
    <script src="{{ asset('client/vendor/summernote-emoji-master/tam-emoji/js/tam-emoji.min.js?v=1.1') }}"></script>

    <script>
        $(function(){
            document.emojiSource = '{{ asset('client/vendor/summernote-emoji-master/tam-emoji/img') }}';
            document.emojiButton = 'fas fa-smile';

            $('.snote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture','video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['misc', ['emoji']],
                    ['help', ['help']],
                ]
            } );

        });
    </script>

    <script src="{{ asset('client/themes/admin/assets/modules/izitoast/js/iziToast.min.js') }}" type="text/javascript"></script>

    <script>
        $(function(){
            $('.readmore').readmore({
                collapsedHeight : 300
            });
            $('#notify').change(function(){

                iziToast.info({
                    title: '{{ __lang('info') }}',
                    message: '{{ __lang('saving-settings') }}',
                    position: 'topRight'
                });

                var checked = $(this).is(":checked");
                if(checked){
                    var notify = 1;
                }
                else{
                    var notify = 0;
                }

                $.get('{{ route('student.forum.notifications',['id'=>$id]) }}?notify='+notify,function(){

                    iziToast.success({
                        title: '{{ __lang('info') }}',
                        message: '{{ __lang('settings-saved') }}',
                        position: 'topRight'
                    });
                })
            })
        });
    </script>

@endsection


