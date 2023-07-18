@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.test.index')=>__lang('tests'),
            '#'=>__lang('take-test')
        ]])
@endsection

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-4">
                <h4>{{  __lang('total-questions')  }}</h4>
                <h1>{{ $totalQuestions }}</h1>
            </div>
            @php  if(!empty($testRow->minutes)): @endphp
            <div class="col-md-4">
                <h4>{{  __lang('time-allowed')  }}</h4>
                <h1>{{ $testRow->minutes }} {{  __lang('mins')  }}</h1>
            </div>

            <div class="col-md-4">
                <h4>{{  __lang('time-remaining')  }}</h4>
                <h1><span id="timespan">{{  $testRow->minutes }} {{  __lang('mins')  }}</span></h1>
            </div>
            @php  endif;  @endphp
        </div>


        <form id="testform"
              method="post"
              action="{{  route('student.test.processtest',['id'=>$testRow->id]) }}">
            @csrf
            <input id="studentTestId"
                   type="hidden"
                   name="student_test_id"
                   value=""/>
            <div class="col-md-8 offset-md-2">

                <div class="card card-default" id="intro">
                    <div class="card-header">
                        <h3 class="card-title">{{  __lang('Instructions')  }}</h3>
                    </div>
                    <div class="card-body">
                        <p>{!! $testRow->description !!}</p>
                        <button type="button"
                                id="start"
                                class="btn btn-primary btn-lg float-right">{{  __lang('Start Test')  }}</button>
                    </div>
                </div>
                @php
                    $count = 0;
                @endphp
                @php  foreach($questions as $id => $question): @endphp

                @php  $count++;  @endphp
                <div class="card card-default question" id="question{{  $count  }}">
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{  $count }}.</h3> {!! $question['question']->question !!}
                        </div>
                    </div>
                    <div class="card-body">
                        @if($exam_type == 1)
                            <div class="form-group">
                            <textarea required="required"
                                      class="form-control summernote"
                                      name="answer_{{  $question['question']->id }}"
                                      placeholder="{{ __lang('shomvabonaenter-question-answer') }}"
                                      id="answer"
                                      rows="1"></textarea>
                            </div>
                        @else
                            <p>
                            @php
                            $options = array();
                            foreach($question['options'] as $option){
                                $options[] = array('id' => $option->id,
                                                 'test_question_id' => $option->test_question_id,
                                                 'option' => $option->option);
                            }
                            shuffle($options);
                            @endphp
                            @php  foreach($options as $option): @endphp
                            <div class="radio">
                                <label style="font-size: 14px">
                                    <input type="radio"
                                           id="question_op_{{  $option['test_question_id'] }}"
                                           name="question_{{  $option['test_question_id'] }}"
                                           value="{{  $option['id']  }}"/>
                                    {{  $option['option']  }}
                                </label>
                            </div>
                            @php  endforeach; @endphp
                            </p>
                        @endif

                        @php  if($count > 1): @endphp
                        <button type="button"
                                onclick="showPanel('{{  ($count - 1) }}')"
                                class="prev btn btn-primary btn-lg ">{{  __lang('Prev')  }}</button>
                        @php  endif;  @endphp

                        @php  if($count < $totalQuestions): @endphp
                        <button type="button" onclick="showPanel('{{  $count + 1 }}')"
                                class="next btn btn-primary btn-lg float-right">{{  __lang('Next')  }}</button>
                        @php  else:  @endphp
                        <a onclick="if(confirm('{{ __lang('submit-test-msg') }}')){$('#testform').submit()};"
                           class="btn btn-success btn-lg float-right"
                           href="#testform">{{  __lang('finish')  }}</a>
                        @php  endif;  @endphp
                    </div>
                </div>
                @php  endforeach;  @endphp
            </div>
        </form>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function(){

            $('.summernote').summernote({
                height: 200
            } );
        });
    </script>
    <script>
        var interval;
        $('.question').hide();

        $('#start').click(function () {
            $('#start').text('{{ __lang('Loading') }}...');
            $('#start').attr('disabled', 'disabled');
            $.ajax({
                dataType: "json",
                url: '{{  route('student.test.starttest',['id'=>$testRow->id]) }}',
                success: function (data) {
                    if (data.status) {
                        $('#studentTestId').val(data.id);
                        $('#intro').hide();
                        showPanel(1);
                        window.onbeforeunload = function () {
                            return confirm("{{ __lang('are-you-sure-cancel-test') }}"@php  if(empty($testRow->allow_multiple)){ echo '+"'.__lang('not-take-again').'"'; }  @endphp);
                        }
                        @php  if(!empty($testRow->minutes)): @endphp
                        var minutes = {{  intval($testRow->minutes)  }} *
                            60 ,
                            display = document.querySelector('#timespan');
                        startTimer(minutes, display);
                        @php  endif;  @endphp

                    } else {
                        $('#start').text('{{ __lang('start-test') }}');
                        $('#start').removeAttr('disabled');
                        alert('{{ __lang('error-try-again') }}');

                    }
                }
            }).fail(function () {
                $('#start').text('{{ __lang('start-test') }}');
                $('#start').removeAttr('disabled');
                alert('{{ __lang('network-error') }}');
            });
        });

        function showPanel(id) {
            $('.question').hide();
            $('#question' + id).show();
        }

        $(function () {


        });

        function startTimer(duration, display) {
            var start = Date.now(),
                diff,
                minutes,
                seconds;

            function timer() {
                // get the number of seconds that have elapsed since
                // startTimer() was called
                diff = duration - (((Date.now() - start) / 1000) | 0);

                // does the same job as parseInt truncates the float
                minutes = (diff / 60) | 0;
                seconds = (diff % 60) | 0;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (diff <= 0) {
                    // add one second so that the count down starts at the full duration
                    // example 05:00 not 04:59
                    // start = Date.now() + 1000;
                    console.log('time is up!');
                    $('#testform').submit();
                    clearInterval(interval);
                }
            };
            // we don't want to wait a full second before the timer starts
            timer();
            interval = setInterval(timer, 1000);
        }


        $('#testform').on('submit', (function (e) {

            window.onbeforeunload = function () {
                // blank function do nothing
            }


        }));
    </script>
    @if($exam_type == 1)
        <script type="text/javascript">
            $(document).ready(function () {
                var ambit = $(document);

                // Disable Cut + Copy + Paste (input)
                ambit.on('copy paste cut', function (e) {
                    e.preventDefault(); //disable cut,copy,paste
                    return false;
                });

                // Disable Cut + Copy + Paste and Browser Admin Tools (all document)
                ambit.keydown(function (e) {
                    var forbiddenCtrlKeys = new Array('c', 'x', 'v', 'ins', 'u');
                    var forbiddenShiftKeys = new Array('del', 'ins', 'f2', 'f4', 'f7');
                    var forbiddenCtrlShiftKeys = new Array('k', 'i', 'm', 's', 'j');
                    var keyCode = (e.keyCode) ? e.keyCode : e.which;

                    var isCtrl, isShift;
                    isCtrl = e.ctrlKey;
                    isShift = e.ctrlShift;

                    string = getKeyCodeString(keyCode);

                    if (string == 'f12')
                    {
                        e.preventDefault();
                        return false;
                    }

                    if (isCtrl && !isShift) {
                        for (i = 0; i < forbiddenCtrlKeys.length; i++) {
                            if (forbiddenCtrlKeys[i] == string) {
                                e.preventDefault();
                                return false;
                            }
                        }
                    }

                    if (!isCtrl && isShift) {
                        for (i = 0; i < forbiddenShiftKeys.length; i++) {
                            if (forbiddenShiftKeys[i] == string) {
                                e.preventDefault();
                                return false;
                            }
                        }
                    }

                    if (isCtrl && isShift) {
                        for (i = 0; i < forbiddenCtrlShiftKeys.length; i++) {
                            if (forbiddenCtrlShiftKeys[i] == string) {
                                e.preventDefault();
                                return false;
                            }
                        }
                    }

                    return true;
                });

                var getKeyCodeString = function(keyCode)
                {
                    var string;
                    switch (keyCode) {
                        case 45:
                            string = 'ins'; break;
                        case 46:
                            string = 'del'; break;
                        case 113:
                            string = 'f2'; break;
                        case 115:
                            string = 'f4'; break;
                        case 118:
                            string = 'f7'; break;
                        case 123:
                            string = 'f12'; break;
                        default:
                            string = String.fromCharCode(keyCode);
                            break;
                    }
                    return string.toLowerCase();
                }


                var ambit = $(document);
                // Disable Contextual Menu
                ambit.on('contextmenu', function (e) {
                    e.preventDefault();
                    return false;
                });

                var ambit = $(document);

                // Disable Tap and Hold (jQuery Mobile)
                ambit.on('taphold', function (e) {
                    e.preventDefault();
                    return false;
                });

            });
        </script>
    @endif
@endsection
