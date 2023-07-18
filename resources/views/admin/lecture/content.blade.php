@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <header></header>
            <div class="dropdown d-inline mr-2">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i>  <span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu wide-btn">
                    <li><a  class="dropdown-item show-modal" data-type="t"   data-target="#addt" href="#"><span class="title"><i class="fa fa-file-word"></i> {{ __lang('text') }}</span></a></li>
                    <li><a class="dropdown-item" onclick="openLargeModal('{{__lang('video-library')}}','{{adminUrl(['controller'=>'lecture','action'=>'library','id'=>$id])}}')"  href="#"><span class="title"><i class="fa fa-file-video"></i>  {{__lang('video-from-library')}} </span></a></li>
                    <li><a class="dropdown-item show-modal" data-type="v"   data-target="#addvurl" href="#"><span class="title"><i class="fa fa-file-video"></i> {{ __lang('external-video-url') }}</span></a></li>
                    <li><a class="dropdown-item show-modal" data-type="v"   data-target="#addv" href="#"><span class="title"><i class="fa fa-file-video"></i> {{ __lang('external-video-embed') }}</span></a></li>
                    <li><a class="dropdown-item show-modal"  data-type="i"    data-target="#addi" href="#"><span class="title"><i class="fa fa-image"></i> {{ __lang('image') }}</span></a></li>
                    <li><a class="dropdown-item show-modal"  data-type="c"    data-target="#addc" href="#"><span class="title"><i class="fa fa-file-code"></i> {{ __lang('html-code') }}</span></a></li>
                    <li><a class="dropdown-item show-modal"  data-type="q"    data-target="#addq" href="#"><span class="title"><i class="fa fa-question-circle"></i> {{ __lang('quiz') }}</span></a></li>
                    <li><a class="dropdown-item show-modal-nc"  data-type="z"    data-target="#addz" href="#"><span class="title"><i class="fa fa-video"></i> {{ __lang('zoom-meeting') }}</span></a></li>
                    <li>
                        <a class="dropdown-item show-modal-pp"
                           data-type="p"
                           data-target="#addPowerPoint"
                           href="#">
                            <span class="title"><i class="fa fa-file-powerpoint"></i> {{ __lang('shomvabona-powerpoint') }}</span>
                        </a>
                    </li>
                    <li role="separator" class="divider dropdown-divider"></li>
                    <li><a class="dropdown-item "  href="{{adminUrl(['controller'=>'lecture','action'=>'importimages','id'=>$id])}}"><span class="title"><i class="fa fa-image"></i> {{ __lang('import-images') }}</span></a></li>
                </ul>
            </div>
        </div>
    </div><!--end .box -->
    <form onsubmit="return  confirm('{{__lang('delete-items')}}')" action="{{adminUrl(['controller'=>'lecture','action'=>'deletecontents'])}}" method="post">
        @csrf
        <table id="content_table" class="table table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all"/></th>
                <th  >{{ __lang('title') }}</th>
                <th >{{ __lang('sort-order') }}</th>
                <th>{{ __lang('type') }}</th>
                <th     >{{ __lang('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @php foreach($paginator as $row):  @endphp
            <tr id="row-{{ $row->id }}">
                <td>
                    <input class="check" type="checkbox" name="select{{ $row->id }}" value="{{ $row->id }}"/>  &nbsp; &nbsp;
                    <i style="cursor: grabbing" class="fa fa-arrows-alt"></i>
                </td>
                <td>
                    <span id="title-{{ $row->id }}">{{ $row->title }}</span>
                </td>
                <td><span class="label label-success sort-order" id="sortorder-{{ $row->id }}">{{ $row->sort_order }}</span></td>
                <td>@php
                        switch($row->type){
                            case 't':
                                echo __lang('text');
                            break;
                            case 'v':
                                echo  __lang('video');
                            break;
                            case 'c':
                                echo __lang('html-code');
                            break;
                            case 'i':
                                echo __lang('image');
                                break;
                            case 'q':
                                echo __lang('quiz');
                                break;
                            case 'l':
                                echo __lang('video');
                                break;
                            case 'p':
                                echo __lang('shomvabona-powerpoint');
                                break;
                            case 'z':
                                echo __lang('zoom-meeting');
                                break;
                        } @endphp</td>
                <td class="text-right1">
                    <button type="button" data-target="#infoModal{{ $row->id }}"  data-toggle="modal"   class="btn btn-xs btn-primary"><i data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('view')}}"  class="fa fa-info-circle"></i></button>
                    @php if($row->type=='q'): @endphp
                    <a href="{{ adminUrl(['controller'=>'lecture','action'=>'editquiz','id'=>$row->id]) }}" class="btn btn-xs btn-primary btn-equal"><i class="fa fa-edit"></i></a>
                    @php elseif($row->type=='z'): @endphp
                    <a href="#" onclick="openModal('{{__lang('edit')}} {{__lang('zoom-meeting')}}','{{ adminUrl(['controller'=>'lecture','action'=>'editzoom','id'=>$row->id]) }}')"  class="btn btn-xs btn-primary btn-equal"><i class="fa fa-edit"></i></a>
                    @php else:  @endphp
                    <a  data-type="{{ $row->type }}" data-id="{{ $row->id }}" href="#" class="editlink btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                    @php endif;  @endphp
                    <a   data-id="{{ $row->id }}" href="#" class="audiolink btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="Add Audio Narration"><i class="fa fa-microphone "></i></a>
                    <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'lecture','action'=>'deletecontent','id'=>$row->id)) }}"  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @section('footer')
                @parent
                <div class="modal fade" tabindex="-1" role="dialog" id="infoModal{{ $row->id }}">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $row->title }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    {{--{{ dd($row) }}--}}
                                    @php if(!empty($row->audio_code)): @endphp
                                    {!! $row->audio_code !!}
                                    <div><a class="btn btn-danger" onclick="return confirm('{{ __lang('audio-delete-confirm') }}')" href="{{adminUrl(['controller'=>'lecture','action'=>'removeaudio','id'=>$row->id])}}"><i class="fa fa-trash"></i> {{ __lang('remove-audio') }}</a></div>
                                    @php endif;  @endphp
                                    @php if($row->type=='c'): @endphp
                                    <div id="content-{{ $row->id }}">
                                        {!! clean(nl2br(htmlentities($row->content))) !!}
                                    </div>
                                    @php elseif($row->type=='l'):  @endphp
                                    <div style="display: none" id="content-{{ $row->id }}" >{!! clean($row->content) !!}</div>
                                    @php $video = \App\Video::find(intval($row->content));  @endphp
                                    @php if($video): @endphp
                                    <a target="_blank" href="{{adminUrl(['controller'=>'video','action'=>'play','id'=>$video->id])}}"><img class="img-responsive img-thumbnail" src="{{ basePath() }}/uservideo/{{$video->id}}/{{videoImage($video->file_name)}} " alt="{{$video->name}}"></a>
                                    <div class="card">
                                        <div class="card-body">
                                            {!! clean($video->description) !!}
                                        </div>
                                    </div>
                                    @php else:  @endphp
                                    <strong>{{ __lang('video-delete-msg') }}</strong>
                                    @php endif; @endphp
                                    @php elseif($row->type=='i'):  @endphp
                                    <div id="content-{{ $row->id }}" style="text-align: center"><a data-img-url="{!! clean($row->content) !!}" class="fullsizable" href="{{basePath().'/'.$row->content}}"><img style="max-width: 100%" src="{{resizeImage($row->content, 640, 360,basePath())}}" /></a> </div>
                                    @php elseif($row->type=='q'):  @endphp
                                    <div class="quizbox " id="quiz{{$row->id}}">
                                        <h1 class="quizName"><!-- where the quiz name goes --></h1>
                                        <div class="quizArea">
                                            <div class="quizHeader">
                                                <!-- where the quiz main copy goes -->
                                                <a class="button startQuiz" href="#">{{ __lang('get-started') }}!</a>
                                            </div>
                                            <!-- where the quiz gets built -->
                                        </div>
                                        <div class="quizResults">
                                            <h3 class="quizScore">{{ __lang('you-scored') }}: <span><!-- where the quiz score goes --></span></h3>
                                            <h3 class="quizLevel"><strong>{{ __lang('ranking') }}:</strong> <span><!-- where the quiz ranking level goes --></span></h3>
                                            <div class="quizResultsCopy">
                                                <!-- where the quiz result copy goes -->
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $(function(){
                                            $('#quiz{{$row->id}}').slickQuiz({!! $row->content !!});
                                        })
                                    </script>
                                    @php elseif($row->type=='z'):  @endphp
                                    <div style="display: none" id="content-{{ $row->id }}">{!! clean($row->content) !!}</div>
                                    @php
                                        $zoomData = @unserialize($row->content);
                                    @endphp
                                    @php if($zoomData && is_array($zoomData)):  @endphp
                                    <div class="list-group">
                                        <a href="#" class="list-group-item active">
                                            {{ __lang('meeting-id') }}
                                        </a>
                                        <a href="#" class="list-group-item">{{$zoomData['meeting_id']}}</a>
                                        <a href="#" class="list-group-item active">
                                            {{ __lang('meeting-password') }}
                                        </a>
                                        <a href="#" class="list-group-item">{{$zoomData['password']}}</a>
                                        <a href="#" class="list-group-item active">
                                            {{ __lang('instructions') }}
                                        </a>
                                        <a href="#" class="list-group-item">{{$zoomData['instructions']}}</a>
                                    </div>
                                    @php endif;  @endphp

                                    @php elseif($row->type=='p'):  @endphp
                                    {{--Starting WU-26--}}

                                    @php
                                        $powerPointData = @unserialize($row->content);
                                    @endphp
                                    @php if($powerPointData && is_array($powerPointData)):  @endphp
                                    <div class="list-group">
                                        {{--
                                        array:5 [▼
                                          "title" => "Microsoft PowerPoint"
                                          "instructions" => null
                                          "sort_order" => 1
                                          "lecture_id" => "127"
                                          "lecture_content_file" => "1627411833-Leadership-Presentation.ppt"
                                        ]
                                        --}}
                                        <a href="#" class="list-group-item active">
                                            {{ __lang('title') }}
                                        </a>
                                        <a href="#" class="list-group-item">{{$powerPointData['title']}}</a>

                                        <a href="#" class="list-group-item active">
                                            {{ __lang('file') }}
                                        </a>
                                        <a href="#" class="list-group-item">
                                            <iframe src="//docs.google.com/gview?url={{ url('uploads/lecture_content/')}}/{{ $powerPointData['lecture_content_file'] }}&embedded=true"
                                                    width='100%'
                                                    height='300px'
                                                    frameborder='0'></iframe>
                                        </a>

                                    </div>
                                    @php endif;  @endphp

                                    {{--ending WU-26--}}
                                    @php else:  @endphp
                                    <div id="content-{{ $row->id }}">{!! $row->content !!}</div>
                                    @php endif;  @endphp
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection
            @php endforeach;  @endphp
            </tbody>
        </table>
        @php if($paginator->count() > 0): @endphp
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-trash"></i> {{ __lang('delete-selected') }}</button>
        @php endif;  @endphp
    </form>
    <script>
        $(function(){
            $('#select_all').change(function(){
                $('input.check').not(this).prop('checked', this.checked);
            })
        });
    </script>
@endsection
@section('header')
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/slickquiz/css/slickQuiz.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/slickquiz/css/custom.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/jquery-fullsizable-2.1.0/css/jquery-fullsizable-theme.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/jquery-fullsizable-2.1.0/css/jquery-fullsizable.css' }}">
@endsection
@section('footer')
    <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="addt" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addcontent','id'=>$id]) }}">
                    @csrf
                    <input type="hidden" name="type" value="t"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('text') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control" id="texttitle" required="required" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('text') }}</label>
                            <textarea class="form-control" id="textcontent" name="content" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="textsortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="addv" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addcontent','id'=>$id]) }}">
                    @csrf
                    <input type="hidden" name="type" value="v"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('video') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control" id="videotitle" required="required" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('video-embed-code') }}</label>
                            <textarea class="form-control" id="videocontent" name="content" ></textarea>
                            <p class="help-block">{{ __lang('video-code-help') }}</p>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="videosortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addc" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addcontent','id'=>$id]) }}">
                    @csrf  <input type="hidden" name="type" value="c"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('html-code') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control" id="codetitle" required="required" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('html-code') }}</label>
                            <textarea class="form-control" id="codecontent" name="content" ></textarea>
                            <p class="help-block">{{ __lang('paste-html') }} </p>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="codesortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addvurl" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addvideo','id'=>$id]) }}">
                    @csrf    <input type="hidden" name="type" value="v"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('video') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('video-url') }}</label>
                            <input class="form-control" type="text" name="url" placeholder="Video Url"/>
                            <p class="help-block" >
                            {{ __lang('video-field-desc') }}
                            <ul>
                                <li>Youtube ({{ __lang('example') }}: https://www.youtube.com/watch?v=MG8KADiRbOU)</li>
                                <li>Vimeo ({{ __lang('example') }}: https://vimeo.com/20732587)</li>
                                <li>Instagram ({{ __lang('example') }}: https://www.instagram.com/p/BZQm9cSA6iK)</li>
                            </ul>
                            <div>{{ __lang('vimeo-rec') }}</div>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order"  class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addi" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-sm" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addcontent','id'=>$id]) }}">
                    @csrf  <input type="hidden" name="type" value="i"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('image') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control" id="imagetitle" required="required" value="" type="text">
                        </div>
                        <div class="form-group">
                            <div><label for="">{{ __lang('image') }}</label></div>
                            <div class="image"><img style="max-width: 100px; max-height: 100px" data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                                <input type="hidden" name="content" id="image" />
                                <a class="pointer" onclick="image_upload('image', 'thumb');">{{ __lang('browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="imagesortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="addq" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addquiz','id'=>$id]) }}">
                    @csrf   <input type="hidden" name="type" value="t"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('quiz') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="name" class="form-control"  required="required" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('quiz-description') }}</label>
                            <textarea class="form-control" id="quizcontent" name="main" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="quizsortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
    <div class="modal fade" id="addaudio" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addaudio']) }}">
                    @csrf  <input type="hidden" id="id" name="id" value=""/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('audio-narration') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p>{!! clean(__lang('sound-cloud-help')) !!}</p>
                        <div class="form-group">
                            <label for="">{{ __lang('sound-cloud-url') }}</label>
                            <input class="form-control" type="text" name="url" placeholder="e.g. https://soundcloud.com/epitaph-records/this-wild-life-history"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addl" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addcontent','id'=>$id]) }}">
                    @csrf   <input type="hidden" name="type" value="l"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('video') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control" id="librarytitle" required="required" value="" type="text">
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="">{{ __lang('video') }} {{ __lang('id') }}</label>
                            <textarea name="content" id="librarycontent" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order" id="librarysortorder" class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addz" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'addzoom','id'=>$id]) }}">
                    @csrf
                    <input type="hidden" name="type" value="z"/>
                    <input type="hidden" name="id" value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="contentmode">{{ __lang('add') }}</span> {{ __lang('lecture-content') }} ({{ __lang('zoom-meeting') }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title" class="form-control"   required="required" value="{{ __lang('zoom-meeting') }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('meeting-id') }}</label>
                            <input required="required" class="form-control" type="text" name="meeting_id" placeholder="{{ __lang('zoom-placeholder') }}"/>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('meeting-password') }}</label>
                            <input required="required" class="form-control" type="text" name="password" />
                        </div>
                        <div class="form-group">
                            <label for="">{{ __lang('instructions') }} ({{ __lang('optional') }})</label>
                            <textarea class="form-control" name="instructions"  ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order"   class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addPowerPoint" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <form method="post"
                      class="form"
                      enctype="multipart/form-data"
                      action="{{ adminUrl(['controller'=>'lecture','action'=>'addPowerPoint','id'=>$id]) }}">
                    @csrf
                    <input type="hidden"
                           name="type"
                           value="p"/>
                    <input type="hidden"
                           name="id"
                           value="0"/>
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span class="contentmode">{{ __lang('add') }}</span>
                            {{ __lang('lecture-content') }} ({{ __lang('shomvabona-powerpoint') }})
                        </h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __lang('title') }}</label>
                            <input name="title"
                                   class="form-control"
                                   required="required"
                                   value="{{ __lang('shomvabona-powerpoint') }}"
                                   type="text" required>
                        </div>
                        <div class="form-group">
                            <input type="file"
                                   name="path"
                                   id="path">
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input name="sort_order"   class="form-control number" placeholder="{{ __lang('digits-only') }}" value="" type="text">   <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script type="text/javascript" src="{{ basePath().'/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script type="text/javascript" src="{{ basePath().'/client/vendor/slickquiz/js/slickQuiz.js' }}"></script>
    <script type="text/javascript" src="{{ basePath().'/client/vendor/jquery-fullsizable-2.1.0/js/jquery-fullsizable.min.js' }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('textcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".show-modal").click(function(){
                var type = $(this).attr('data-type');
                $('#add'+type+' .contentmode').text('Add');
                $('#add'+type+' input[type=text],'+'#add'+type+' input[name=id],'+'#add'+type+' textarea').val("");
                if(type=='t'){
                    CKEDITOR.instances['textcontent'].setData('');
                }
                $($(this).attr('data-target')).modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            $(".show-modal-nc").click(function(){
                var type = $(this).attr('data-type');
                $('#add'+type+' .contentmode').text('Add');
                if(type=='t'){
                    CKEDITOR.instances['textcontent'].setData('');
                }
                $($(this).attr('data-target')).modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            $(".show-modal-pp").click(function(){
                var type = $(this).attr('data-type');
                $('#add'+type+' .contentmode').text('Add');
                if(type=='t'){
                    CKEDITOR.instances['textcontent'].setData('');
                }
                $($(this).attr('data-target')).modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
        });
        $('.editlink').click(function(e){
            //get values
            var id = $(this).attr('data-id');
            console.log('id is '+id);
            var type = $(this).attr('data-type');
            console.log('type is '+type);
            //get values
            var title = $('#title-'+id).text();
            var content = $('#content-'+id).html();
            var sortOrder = $('#sortorder-'+id).text();
            //load into form
            $('#add'+type+' input[name=title]').val(title);
            if(type=='t'){
                CKEDITOR.instances['textcontent'].setData(content);
            }
            else if(type =='i'){
                var url = $('#content-'+id+' a').attr('href');
                $('#add'+type+' img').attr('src',url);
                $('#add'+type+' input[name=content]').val($('#content-'+id+' a').attr('data-img-url'));
            }
            else{
                $('#add'+type+'  [name=content]').val(content);
            }
            $('#add'+type+' input[name=sort_order]').val(sortOrder);
            $('#add'+type+' input[name=id]').val(id);
            $('#add'+type+' .contentmode').text('Edit');
            $('#add'+type).modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        $('.audiolink').click(function(){
            var id = $(this).attr('data-id');
            console.log('id is '+id);
            $('#id').val(id);
            $('#addaudio').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
    <script type="text/javascript"><!--
        function image_upload(field, thumb) {
            $('#dialog').remove();
            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
            $('#dialog').dialog({
                title: '{{addslashes(__lang('Image Manager'))}}',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: '{{ basePath() }}/admin/filemanager/image?&image=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                            }
                        });
                    }
                },
                bgiframe: false,
                width: 800,
                height: 570,
                resizable: true,
                modal: false,
                position: "center"
            });
        };
        $(function() {
            $('a.fullsizable').fullsizable();
        });
        $("#content_table tbody").sortable({ opacity:0.6, update: function() {
                var order = $(this).sortable("serialize") + '&action=sort&_token={{ csrf_token() }}';
                //console.log(order);
                $.post("{{adminUrl(['controller'=>'lecture','action'=>'reorder','id'=>$id])}}",order,function(data){
                    console.log(data);
                    var counter = 1;
                    $('.sort-order').each(function(){
                        $(this).text(counter);
                        counter++;
                    });
                });
            }
        });
        $(document).on('click','#pagerlinks a',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#genLargemodalinfo').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');
            $('#genLargemodalinfo').load(url);
        })
        $(document).on("submit","#filterform", function (event) {
            var $this = $(this);
            var frmValues = $this.serialize();
            $('#genLargemodalinfo').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');
            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                data: frmValues
            })
                .done(function (data) {
                    $('#genLargemodalinfo').html(data);
                })
                .fail(function () {
                    $('#genLargemodalinfo').text("{{__lang('error-occurred')}}");
                });
            event.preventDefault();
        });
        //--></script>
    <script>
        $(function(){
            $.fn.modal.Constructor.prototype._enforceFocus = function() {
                var $modalElement = this.$element;
                $(document).on('focusin.modal',function(e) {
                    if ($modalElement.length > 0 && $modalElement[0] !== e.target
                        && !$modalElement.has(e.target).length
                        && $(e.target).parentsUntil('*[role="dialog"]').length === 0) {
                        $modalElement.focus();
                    }
                });
            };
        })
    </script>
@endsection