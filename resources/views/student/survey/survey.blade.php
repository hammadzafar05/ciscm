@extends($layout)
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.student.surveys')=>__lang('surveys'),
            '#'=>__lang('take-survey')
        ]])
@endsection

@section('content')



<form id="testform" method="post" action="{{  selfURL()  }}">
    @csrf
    <input id="studentTestId" type="hidden" name="student_survey_id" value=""/>
    <div   class="col-md-8 offset-md-2">

        <div class="card card-primary" id="intro">
            <div class="card-header">
                <h3 >{{  __lang('Instructions')  }}</h3>
            </div>
            <div class="card-body">
                <p>{!! $survey->description !!} </p>
                <button type="button" id="start" class="btn btn-primary btn-lg float-right">{{  __lang('Start Survey')  }}</button>
            </div>
        </div>
        @php  $count = 0;  @endphp
        @php  foreach($survey->surveyQuestions()->orderBy('sort_order')->get() as  $question): @endphp
            @php  $count++;  @endphp
            <div class="card card-success question" id="question{{  $count  }}">
                <div class="card-header">
                    <div class="card-title"><h3>{{  $count }}/{{  $totalQuestions  }}</h3> {!! $question->question  !!}  </div>
                </div>
                <div class="card-body">
                    <p >

                        @php  foreach($question->surveyOptions as $option): @endphp


                    <div class="radio">
                        <label style="font-size: 14px">
                            <input type="radio" id="question_op_{{  $option->survey_question_id }}" name="question_{{  $option->survey_question_id }}" value="{{  $option->survey_option_id  }}"/>

                            {{  $option->option  }}
                        </label>
                    </div>

                    @php  endforeach; @endphp


                    </p>

                    @php  if($count > 1): @endphp
                        <button type="button" onclick="showPanel('{{  ($count - 1) }}')" class="prev btn btn-primary btn-lg ">{{  __lang('Prev')  }}</button>
                    @php  endif;  @endphp

                    @php  if($count < $totalQuestions): @endphp
                        <button  type="button"  onclick="showPanel('{{  $count + 1 }}')"  class="next btn btn-primary btn-lg float-right">{{  __lang('Next')  }}</button>
                    @php  else:  @endphp
                        <a onclick="if(confirm('{{ __lang('submit-survey-msg') }}')){$('#testform').submit()};" class="btn btn-success btn-lg float-right" href="#testform">{{  __lang('finish')  }}</a>
                    @php  endif;  @endphp
                </div>
            </div>
        @php  endforeach;  @endphp

    </div>
</form>




@endsection


@section('footer')
    <script>
        var interval;
        $('.question').hide();

        $('#start').click(function(){

            $('#intro').hide();
            showPanel(1);


        });

        function showPanel(id){
            $('.question').hide();
            $('#question'+id).show();
        }
        $(function(){


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
            interval=  setInterval(timer, 1000);
        }




    </script>
@endsection
