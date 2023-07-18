@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__('default.courses'),
            '#'=>__lang('manage-classes')
        ]])
@endsection

@section('content')
<p>
    {{ __lang('drag-and-drop-rows') }}
        <a class="btn btn-primary float-right" href="#"  data-toggle="modal" data-target="#addClassModal"><i class="fa fa-plus"></i> {{ __lang('Add Class') }}</a>

</p>

        <table id="selectedTable" class="table table-md table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th  data-sort="string">{{ __lang('class-name') }}</th>
                <th>{{ __lang('class-type') }}</th>
                <th>{{ __lang('class-date-opening-date') }}</th>
                <th>{{ __lang('start-time') }}</th>
                <th>{{ __lang('end-time') }}</th>
                <th>{{ __lang('class-venue') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody  id="selectedlist">

            @php foreach($session->lessons()->orderBy('pivot_sort_order')->get() as $sessionLesson): @endphp
            <tr  id="row-{{ $sessionLesson->id }}" class="sort_row">
                <td class="sort_cell">{{$sessionLesson->pivot->sort_order}}</td>
                <td>{{$sessionLesson->name}}</td>
                <td>{{ ($sessionLesson->type=='c')?__lang('online'):__lang('physical-location') }}</td>
                <td><input placeholder="@if($sessionLesson->type=='c') {{ __lang('opening-date') }} @else  {{ __lang('class-date') }} @endif" style="max-width: 120px" data-id="{{$sessionLesson->id}}" name="lesson_date_{{$sessionLesson->id}}" id="lesson_date_{{$sessionLesson->id}}" class="form-control date lesson_date" value="{{showDate('Y-m-d',$sessionLesson->pivot->lesson_date)}}" type="text"/></td>

                <td>
                    @php if($sessionLesson->type=='s'): @endphp
                    <input placeholder="{{ __lang('start-time') }}"  style="max-width: 100px" data-id="{{$sessionLesson->id}}" name="lesson_start_{{$sessionLesson->id}}" id="lesson_start_{{$sessionLesson->id}}" class="form-control time lesson_start_time" value="{{$sessionLesson->pivot->lesson_start}}" type="text"/>
                    @php endif;  @endphp
                </td>
                <td>
                    @php if($sessionLesson->type=='s'): @endphp
                    <input placeholder="{{ __lang('end-time') }}"  style="max-width: 100px" data-id="{{$sessionLesson->id}}" name="lesson_end_{{$sessionLesson->id}}" id="lesson_end_{{$sessionLesson->id}}" class="form-control time lesson_end_time" value="{{$sessionLesson->pivot->lesson_end}}" type="text"/>
                    @php endif;  @endphp
                </td>
                <td>
                    @php if($sessionLesson->type=='s'): @endphp
                    <textarea class="form-control lesson_venue" data-id="{{$sessionLesson->id}}" name="lesson_venue_{{$sessionLesson->id}}" id="lesson_venue_{{$sessionLesson->id}}"  >{{$sessionLesson->pivot->lesson_venue}}</textarea>
                    @php endif;  @endphp

                </td>


                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">

                        <a target="_blank" class="btn btn-primary" href="{{ adminUrl(array('controller'=>'lesson','action'=>'edit','id'=>$sessionLesson->id)) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                        @php if($sessionLesson->type=='c'): @endphp
                        <a target="_blank" class="btn btn-success" href="{{ adminUrl(array('controller'=>'lecture','action'=>'index','id'=>$sessionLesson->id)) }}"><i class="fa fa-file-video"></i> {{ __lang('manage-lectures') }}</a>
                        @php endif;  @endphp
                        <div class="btn-group dropleft  ">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash"></i>   {{ __lang('delete') }}
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="return confirm('{{ __lang('course-remove-prompt') }}')" href="{{ route('admin.session.deleteclass',['lesson'=>$sessionLesson->id,'course'=>$sessionLesson->pivot->course_id]) }}"   >{{ __lang('remove-from-session') }}</a>
                                <a class="dropdown-item" onclick="return confirm('{{ __lang('class-delete-prompt') }}')" href="{{ adminUrl(array('controller'=>'lesson','action'=>'delete','id'=>$sessionLesson->id)) }}">{{ __lang('delete-class') }}</a>
                            </div>
                        </div>


                    </div>






                </td>
            </tr>

            @php endforeach;  @endphp
            </tbody>
        </table>





@endsection

@section('header')
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.date.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.time.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/datatables/media/css/jquery.dataTables.min.css' }}">

@endsection

@section('footer')
    <div class="modal fade" tabindex="-1" role="dialog" id="addClassModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __lang('add') }}      @php if($session->lessons()->count()==0): @endphp
                        {{ __lang('your-first') }}
                    @php endif;  @endphp {{ __lang('class') }}

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __lang('new-class') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('existing-class') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            @php
                            $form->get('type')->setValue('s');
                            @endphp
                            <form method="post" action="{{ adminUrl(array('controller'=>'lesson','action'=>'add')).'?sessionId='.$session->id.'&back=true' }}">
                                @csrf
                                <div class="form-group">
                                    {{ formLabel($form->get('name')) }}
                                    {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

                                </div>


                                <div class="form-group">
                                    {{ formLabel($form->get('type')) }}
                                    {{ formElement($form->get('type')) }}   <p class="help-block">{{ formElementErrors($form->get('type')) }}</p>

                                </div>



                                <div class="form-group online">
                                    {{ formElement($form->get('test_required')) }}  {{ formLabel($form->get('test_required')) }}

                                    <p class="help-block">{{ formElementErrors($form->get('test_required')) }}</p>
                                    <p class="help-block">{{ __lang('test-required-help') }}</p>
                                </div>


                                <div id="test_id_box" class="form-group online">
                                    {{ formLabel($form->get('test_id')) }}
                                    {{ formElement($form->get('test_id')) }}   <p class="help-block">{{ formElementErrors($form->get('test_id')) }}</p>

                                </div>

                                <div class="form-group online">
                                    {{ formElement($form->get('enforce_lecture_order')) }}   {{ formLabel($form->get('enforce_lecture_order')) }}

                                    <p class="help-block">{{ __lang('enforce-lecture-order-help') }}</p>

                                </div>



                                <div class="form-group">
                                    {{ formLabel($form->get('description')) }}
                                    {{ formElement($form->get('description')) }}

                                    <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

                                </div>

                                <div class="form-group online">
                                    {{ formLabel($form->get('introduction')) }}

                                    {{ formElement($form->get('introduction')) }}

                                    <p class="help-block">{{ formElementErrors($form->get('introduction')) }}</p>

                                </div>


                                <div class="form-group">
                                    {{ formLabel($form->get('lesson_group_id[]')) }}
                                    {{ formElement($form->get('lesson_group_id[]')) }}   <p class="help-block">{{ formElementErrors($form->get('lesson_group_id[]')) }}</p>

                                </div>


                                <div class="form-group">
                                    {{ formLabel($form->get('sort_order')) }}
                                    {{ formElement($form->get('sort_order')) }}   <p class="help-block">{{ formElementErrors($form->get('sort_order')) }}</p>

                                </div>
                                <div class="form-group" style="margin-bottom:10px">

                                    <label for="image" class="control-label">{{ __lang('cover-image') }}  ({{ __lang('optional') }})</label><br />


                                    <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                                        {{ formElement($form->get('picture')) }}
                                        <a class="pointer" onclick="image_upload('image', 'thumb');">{{ __lang('browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>

                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary btn-block">{{__lang('save-changes')}}</button>
                                </div>
                            </form>

                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <div id="classlistbox"></div>
                        </div>
                    </div>



                </div>

            </div>
        </div>
    </div>
    </div>

    <!--
      End modal
        -->

    <script type="text/javascript" src="{{ basePath() }}/client/vendor/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <style>
        #selectedTable tr{
            cursor: grabbing;
        }
    </style>
    <script>
        jQuery(function(){

            @php if($session->lessons()->count()==0): @endphp
            $('#addClassModal').modal('show');
            @php endif;  @endphp

            $('#classlistbox').load('{{adminUrl(['controller'=>'session','action'=>'browseclasses','id'=>$session->id])}}?type=session',function(){
                $('.select2').select2();
            });

            $('.date').pickadate({
                format: 'yyyy-mm-dd'
            });

            $('.time').pickatime({
                interval: 15
            });

            $("#selectedTable tbody").sortable({ opacity:0.6, update: function() {

                    var counter = 1;
                    //console.log(order);

                    $('.sort_row').each(function(){
                        $(this).find('.sort_cell').text(counter);

                        counter++;
                    });

                    var order = $(this).sortable("serialize") + '&action=sort&_token={{ csrf_token() }}';
                    console.log(order);
                    console.log(order);
                    $.post("{{adminUrl(['controller'=>'session','action'=>'reorder','course'=>$session->id])}}",order,function(data){
                        console.log(data);
                    }) }});

            $('.lesson_date').change(function(){
                var date= $(this).val();
                var id = $(this).attr('data-id');
                $.post('{{adminUrl(['controller'=>'session','action'=>'setdate','course'=>$session->id])}}/'+id,{
                    date:date,
                    '_token': '{{ csrf_token() }}'
                });
            });

            $('.lesson_start_time').change(function(){
                var start= $(this).val();
                var id = $(this).attr('data-id');
                $.post('{{adminUrl(['controller'=>'session','action'=>'setstart','course'=>$session->id])}}/'+id,{
                    start:start,
                    '_token': '{{ csrf_token() }}'
                });
            });

            $('.lesson_end_time').change(function(){
                var end= $(this).val();
                var id = $(this).attr('data-id');
                $.post('{{adminUrl(['controller'=>'session','action'=>'setend','course'=>$session->id])}}/'+id,{
                    end:end,
                    '_token': '{{ csrf_token() }}'
                });
            });


            $('.lesson_venue').each(function(){
                var id = $(this).attr('id');
                //setup before functions
                var typingTimer;                //timer identifier
                var doneTypingInterval = 2000;  //time in ms, 5 second for example
                var $input = $('#'+id);

//on keyup, start the countdown
                $input.on('keyup', function () {

                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                });

//on keydown, clear the countdown
                $input.on('keydown', function () {
                    clearTimeout(typingTimer);
                });

//user is "finished typing," do something
                function doneTyping () {
                    //do something
                    console.log('done typing: '+id);
                    var venue= $input.val();
                    var lid = $input.attr('data-id');
                    $.post('{{adminUrl(['controller'=>'session','action'=>'setvenue','course'=>$session->id])}}/'+lid,{
                        venue:venue,
                        '_token': '{{ csrf_token() }}'
                    });
                }


            });



        });
    </script>


    <script type="text/javascript">

        CKEDITOR.replace('hcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('hintroduction', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
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
        $(function(){


            if($('select[name=type]').val()!='c'){
                $('.online').hide();
            };

            $('select[name=type]').change(function(){
                if($(this).val()=='c'){
                    $('.online').show();
                }
                else{
                    $('.online').hide();
                }

            });

        });

        $(document).on('click','#pagerlinks a',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#classlistbox').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

            $('#classlistbox').load(url);
        })
        $(document).on("submit","#filterform", function (event) {
            var $this = $(this);
            var frmValues = $this.serialize();
            $('#classlistbox').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                data: frmValues
            })
                .done(function (data) {
                    $('#classlistbox').html(data);
                    $('.select2').select2();
                })
                .fail(function () {
                    $('#classlistbox').text("{{__lang('error-occurred')}}");
                });
            event.preventDefault();
        });


        //--></script>
@endsection
