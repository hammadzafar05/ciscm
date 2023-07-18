@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.test.index')=>__lang('tests'),
            '#'=>__lang('test-questions')
        ]])
@endsection

@section('content')
    <div>
        <div>
            <div class="card">
                <div class="card-header">
                    <button data-toggle="modal"
                            data-target="#myModal"
                            class="btn btn-success float-right">
                        <i class="fa fa-plus"></i>  Add Question - {{ $type }}
                    </button>
                    <button data-toggle="modal"
                            data-target="#modalQuestionBank"
                            class="btn btn-warning float-right">
                        <i class="fa fa-plus"></i>  Add Question from Question Bank- {{ $type }}
                    </button>
                    @if($exam_type == 0)
                        <button data-toggle="modal"
                                data-target="#importModal"
                                class="btn btn-danger float-right">
                            <i class="fa  fa-download"></i>  Import Questions - {{ $type }}
                        </button>
                    @endif
                </div>
                <div class="card-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __lang('question') }}</th>
                            <th>{{ __lang('options') }}</th>
                            <th>{{ __lang('sort-order') }}</th>
                            <th>{{__lang('actions')}}</th>
                        </tr>
                        </thead>
                        <tbody> @php $number = 1 + (30 * ($page-1));  @endphp
                        @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>{{ $number }} @php $number++ @endphp</td>
                            <td>{!! $row->question !!}</td>
                            <td>{{ $optionTable->getTotalOptions($row->id) }}</td>
                            <td>{{ $row->sort_order }}</td>

                            <td>

                                <a href="{{ adminUrl(array('controller'=>'test','action'=>'editquestion','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('edit-questions-options') }}"><i class="fa fa-edit"></i></a>

                                <a onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'test','action'=>'deletequestion','id'=>$row->id)) }}"  class="btn btn-xs btn-danger btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @php endforeach;  @endphp

                        </tbody>
                    </table>

                    @php
                        // add at the end of the file after the table
                        echo paginationControl(
                        // the paginator object
                            $paginator,
                            // the scrolling style
                            'sliding',
                            // the partial to use to render the control
                            null,
                            // the route to link to when a user clicks a control link
                            array(
                                'route' => 'admin/default',
                                'controller'=>'test',
                                'action'=>'questions',
                                'id'=>$id
                            )
                        );
                    @endphp
                </div><!--end .box-body -->
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>




@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
@endsection
@section('footer')

    <!-- Modal -->
    <div class="modal fade"
         id="myModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="myModalLabel">{{ __lang('add-question') }}</h4>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="questionform"
                      method="post"
                      action="{{ adminUrl(['controller'=>'test','action'=>'addquestion','id'=>$id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="question">{{ __lang('question') }}</label>
                            <textarea
                                    required="required"
                                    class="form-control summernote"
                                    name="question" p
                                    laceholder="{{ __lang('enter-question') }}"
                                    id="question"
                                    rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
                            <input placeholder="{{ __lang('digits-only') }}"
                                   class="form-control number"
                                   type="text"
                                   id="sort_order"
                                   name="sort_order"/>
                        </div>
                        @if($exam_type == 0)
                            <h3>{{ __lang('options') }}</h3>
                            <p><small>{{ __lang('add-question-help') }}</small></p>
                            <table class="table table-stripped">
                                <thead>
                                <tr>
                                    <th>{{ __lang('option') }}</th>
                                    <th>{{ __lang('correct-answer') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php for($i=1;$i<=5;$i++): @endphp
                                <tr>
                                    <td><input name="option_{{ $i }}" class="form-control" type="text"/></td>
                                    <td><input  required="required"  type="radio" name="correct_option" value="{{ $i }}"/></td>
                                </tr>
                                @php endfor;  @endphp
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">{{ __lang('cancel') }}</button>
                        <button type="submit"
                                class="btn btn-primary">{{__lang('save-changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade"
         id="modalQuestionBank"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modalQuestionBankLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="modalQuestionBankLabel">{{ __lang('add-question') }} from Question Bank</h4>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="questionform"
                      method="post"
                      action="{{ adminUrl(['controller'=>'test','action'=>'add_question_from_question_bank','id'=>$id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="questions_type">Exam Type</label>
                            <input type="text"
                                   class="form-control"
                                   name="select_question_type"
                                   value="{{ $type }}"
                                   readonly>
                        </div>
                        <div class="form-group display-hide">
                            <label for="questions_type">COURSE ID</label>
                            <input type="text"
                                   class="form-control"
                                   name="select_question_from_course_id"
                                   value="{{ $course_id }}"
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label for="questions_type">Classes</label>
                            <select class="form-control select2"
                                    name="select_question_from_class_id[]"
                                    multiple>
                                <option value="">Select an Option</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">{{ __lang('cancel') }}</button>
                        <button type="submit"
                                class="btn btn-primary">{{__lang('save-changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ __lang('import-questions') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <form enctype="multipart/form-data"
                      id="importform"
                      method="post"
                      action="{{ adminUrl(['controller'=>'test','action'=>'importquestions','id'=>$id]) }}">
                    @csrf
                    <div class="modal-body">
                        <p>
                            {!! __lang('import-questions-help',['link'=>basePath().'/client/data/test_question_sample.csv']) !!}
                        </p>
                        <div class="form-group">
                            <label for="question">{{ __lang('csv-file') }}</label>
                            <input type="file" name="file" >
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('cancel') }}</button>
                        <button  type="submit" class="btn btn-primary"><i class="fa  fa-download"></i> {{ __lang('import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function(){

            $('.summernote').summernote({
                height: 200
            } );
        });
    </script>
@endsection
