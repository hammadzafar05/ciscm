@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/loader/css/jquery.loadingModal.min.css') }}">
@endsection


@section('content')
    <div   ng-app="myApp" ng-controller="myCtrl" >
        <button ng-click="submit()" class="btn btn-primary float-right"><i class="fa fa-save"></i> {{ __lang('save') }}</button>

          <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-info-circle"></i> {{ __lang('info') }}</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-question-circle"></i> {{ __lang('questions') }}</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-cogs"></i> {{ __lang('options') }}</a>
                                </li>
                              </ul>
                              <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                                        <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">{{ __lang('name') }}</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.name" />
                                            </div>
                                            <div class="form-group">
                                                <label for="mail">{{ __lang('description') }}</label>
                                                <textarea class="form-control" name="main" id="main" cols="30" rows="4"  ng-model="quiz.json.info.main" ></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">{{ __lang('sort-order') }}</label>
                                                <input class="form-control number" type="text" name="sort_order" ng-model="sortOrder" />
                                            </div>
                                            <div class="form-group">
                                                <label for="mail">{{ __lang('post-quiz-text') }}</label>
                                                <textarea class="form-control" name="main" id="results" cols="30" rows="4"  ng-model="quiz.json.info.results" ></textarea>
                                                <p class="help-block">{{ __lang('post-quiz-help') }}</p>
                                            </div>

                                            <h3>{{ __lang('levels') }}</h3>
                                            <p class="help-block">{{ __lang('type-message') }}</p>
                                            <div class="form-group">
                                                <label for="name">{{ __lang('level') }} 1</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.level1" />
                                            </div>

                                            <div class="form-group">
                                                <label for="name">{{ __lang('level') }} 2</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.level2" />
                                            </div>

                                            <div class="form-group">
                                                <label for="name">{{ __lang('level') }} 3</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.level3" />
                                            </div>

                                            <div class="form-group">
                                                <label for="name">{{ __lang('level') }} 4</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.level4" />
                                            </div>

                                            <div class="form-group">
                                                <label for="name">{{ __lang('level') }} 5</label>
                                                <input class="form-control" type="text" name="name" ng-model="quiz.json.info.level5" />
                                            </div>



                                        </div>
                                        </div>
                                </div>
                                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card"  ng-repeat="question in quiz.json.questions"  >
                                            <div class="card-header" id="headingOne@{{ $index }}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne@{{ $index }}" aria-expanded="true" aria-controls="collapseOne@{{ $index }}">
                                                        {{ __lang('question') }}: @{{ question.q }}
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapseOne@{{ $index }}" class="collapse show" aria-labelledby="@{{ $index }}" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="question">{{ __lang('question') }}</label>
                                                        <textarea class="form-control"  ng-model="question.q"  ></textarea>
                                                    </div>

                                                    <h3>{{ __lang('options') }}</h3>
                                                    <hr/>
                                                    <table class="table table-stripped">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 10%;"></th>
                                                            <th>{{ __lang('option') }}</th>
                                                            <th>{{ __lang('correct') }}?</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr ng-repeat="option in question.a">
                                                            <td><button class="btn btn-danger btn-sm" ng-click="removeOption(question.a,$index)"><i class="fa fa-trash"></i> {{ __lang('remove') }}</button></td>
                                                            <td><input ng-model="option.option" class="form-control" type="text"/></td>
                                                            <td><input ng-model="option.correct" type="checkbox" ng-value="true"/></td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                    <button class="btn btn-primary btn-sm" ng-click="addOption(question)"><i class="fa fa-plus"></i> {{ __lang('add-option') }}</button>




                                                    <div style="margin-top: 50px" class="form-group">
                                                        <label for="correct">{{ __lang('correct-response') }}</label>
                                                        <textarea class="form-control"  ng-model="question.correct"  placeholder="{{ __lang('answer-is-correct') }}"  ></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="correct">{{ __lang('incorrect-response') }}</label>
                                                        <textarea class="form-control"  ng-model="question.incorrect" placeholder="{{ __lang('answer-is-incorrect') }}"  ></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="checkbox" ng-value="true" ng-model="question.select_any" /> {{ __lang('select-any') }}
                                                        <p class="help-block">{{ __lang('select-any-text') }}</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="checkbox" ng-value="true" ng-model="question.force_checkbox" /> {{ __lang('force-checkbox') }}
                                                        <p class="help-block">{{ __lang('force-checkbox-help') }}</p>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button ng-click="removeQuestion($index)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> {{ __lang('delete-question') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <button ng-click="addQuestion()" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __lang('add-question') }}</button>




                                </div>
                                <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">

                                    <div class="card">
                                    <div class="card-body">
                                        <form class="form-horizontal" onsubmit="return false">
                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('checked-answer-text') }}:</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.checkAnswerText"/>
                                                    <p class="help-block">
                                                        {{ __lang('checked-answer-text-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('next-question-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.nextQuestionText"/>
                                                    <p class="help-block">
                                                        {{ __lang('new-question-text-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('completed-quiz-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.completeQuizText"/>
                                                    <p class="help-block">
                                                        {{ __lang('completed-quiz-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('back-button-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.backButtonText"/>
                                                    <p class="help-block">
                                                        {{ __lang('back-button-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('try-again-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.tryAgainText"/>
                                                    <p class="help-block">
                                                        {{ __lang('try-again-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('prevent-unanswered-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.preventUnansweredText"/>
                                                    <p class="help-block">
                                                        {{ __lang('prevent-unanswered-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('question-count-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.questionCountText"/>
                                                    <p class="help-block">
                                                        {{ __lang('question-count-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('question-template-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.questionTemplateText"/>
                                                    <p class="help-block">
                                                        {{ __lang('question-template-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('score-template-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.scoreTemplateText"/>
                                                    <p class="help-block">
                                                        {{ __lang('score-template-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('name-template-text') }}</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" ng-model="quiz.nameTemplateText"/>
                                                    <p class="help-block">
                                                        {{ __lang('name-template-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('skip-start-button') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.skipStartButton"/>
                                                    <p class="help-block">
                                                        {{ __lang('skip-start-help') }}
                                                    </p>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('random-sort-questions') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.randomSortQuestions"/>
                                                    <p class="help-block">
                                                        {{ __lang('random-sort-help') }}
                                                    </p>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('random-sort-answers') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.randomSortAnswers"/>
                                                    <p class="help-block">
                                                        {{ __lang('random-sort-a-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('prevent-unanswered') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.preventUnanswered"/>
                                                    <p class="help-block">
                                                        {{ __lang('prevent-unanswered-helper') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('per-question-response') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.perQuestionResponseMessaging"/>
                                                    <p class="help-block">
                                                        {{ __lang('per-question-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('per-question-answers') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.perQuestionResponseAnswers"/>
                                                    <p class="help-block">
                                                        {{ __lang('per-question-helper') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('completion-response') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.completionResponseMessaging"/>
                                                    <p class="help-block">
                                                        {{ __lang('completion-response-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('display-question-count') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.displayQuestionCount"/>
                                                    <p class="help-block">
                                                        {{ __lang('display-question-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('display-question-number') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.displayQuestionNumber"/>
                                                    <p class="help-block">
                                                        {{ __lang('display-ques-number-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('disable-score') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.disableScore"/>
                                                    <p class="help-block">
                                                        {{ __lang('disable-score-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('disable-ranking') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.disableRanking"/>
                                                    <p class="help-block">
                                                        {{ __lang('disable-ranking-help') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label   class="col-sm-2 control-label">{{ __lang('score-as-percentage') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" ng-value="true" ng-model="quiz.scoreAsPercentage"/>
                                                    <p class="help-block">
                                                        {{ __lang('score-as-percentage-help') }}
                                                    </p>
                                                </div>
                                            </div>


                                        </form>

                                    </div>
                                    </div>


                                </div>
                              </div>

    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/js/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/loader/js/jquery.loadingModal.min.js') }}"></script>
    <script>
        var app = angular.module('myApp', []);
        app.controller('myCtrl', function($scope,$http) {
            // $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
            $scope.quiz = {!! $lecturePage->content !!};

            $scope.sortOrder = {{ intval($lecturePage->sort_order) }};


            $scope.submit = function(){
                console.log($scope.quiz);
                //  return;
                $('body').loadingModal({
                    text: '{{__lang('saving-quiz')}}'
                })
                var data = {
                    'content':$scope.quiz,
                    'sort_order':$scope.sortOrder,
                    'title':$scope.quiz.json.info.name,
                    '_token': '{{ csrf_token() }}'
                };
                $.post('{{ selfURL()}}',data,function(data){

                    if(data){
                        document.location.replace('{{ adminUrl(['controller'=>'lecture','action'=>'content','id'=>$lecturePage->lecture_id]) }}')
                    }
                    else{
                        alert('{{__lang('submission-failed')}}')
                    }
                },'json').fail(function(){
                    alert('{{__lang('network-error')}}');
                }).always(function() {
                    $('body').loadingModal('hide');
                });

            }

            $scope.addQuestion= function(){

                console.log('adding a question');
                $scope.quiz.json.questions.push({
                    q: "",
                    a: [],
                    correct: "{{__lang('is-correct')}}",
                    incorrect: "{{__lang('is-incorrect')}}"
                });

                // $('.collapse').collapse('hide');
                $('.collapse').removeClass('in');
                var index = $scope.quiz.json.questions.length -1;
                console.log(index);

                setTimeout(function(){
                    //  $('#collapseOne'+index).collapse('show')
                    $('#collapseOne'+index).addClass('in');
                }, 500);
            }

            $scope.addOption = function(question){
                question.a.push({"option": "", "correct": false});
            }

            $scope.removeOption = function(options,index){
                if(confirm('{{__lang('conf-delete')}}?')){
                    options.splice(index,1);
                }

            }

            $scope.removeQuestion = function(index){
                if(confirm('{{__lang('conf-delete')}}?')){
                    $scope.quiz.json.questions.splice(index,1);
                }
            }


        });


    </script>


@endsection
