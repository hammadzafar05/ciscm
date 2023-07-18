@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__lang('courses'),
            '#'=>__lang('students')
        ]])
@endsection

@section('content')
    <div>
        <div>
            <div class="card">
                <div class="card-header">
                    <button data-toggle="modal"
                            data-target="#importModalMcq"
                            class="btn btn-primary float-right">
                        <i class="fa  fa-download"></i>  Import Questions - MCQ
                    </button>
                    <button data-toggle="modal"
                            data-target="#importModalWritten"
                            class="btn btn-danger float-right">
                        <i class="fa  fa-download"></i>  Import Questions - Written
                    </button>
                </div>
                <div class="card-body">



                   <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>{{ __lang('question') }}</th>
                            <th>{{ __lang('options') }}</th>
                            <th>{{ __lang('type') }}</th>
                            <th>{{ __lang('sort-order') }}</th>
                            <th>{{__lang('actions')}}</th>
                        </tr>
                        </thead>
                        <tbody> @php $number = 1 + (30 * ($page-1));  @endphp
                        @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td>{{ $number }} @php $number++ @endphp</td>
                            <td>
                                @foreach($classes as $class)
                                    @if($class->id == $row->class_id)
                                        {{ $class->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{!! $row->question !!}</td>
                            <td>{{ $row->questions_type == 'MCQ' ? $optionTable->getTotalOptions($row->id) : '' }}</td>
                            <td>{{ $row->questions_type }}</td>
                            <td>{{ $row->sort_order }}</td>

                            <td>

                                {{--<a href="{{ adminUrl(array('controller'=>'test','action'=>'editquestion','id'=>$row->id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('edit-questions-options') }}"><i class="fa fa-edit"></i></a>--}}

                                <a onclick="return confirm('{{__lang('delete-confirm')}}')"
                                   href="{{ route('admin.sessions.question_banks.delete',['id'=>$id,'qid'=>$row->id]) }}"
                                   class="btn btn-xs btn-danger btn-equal"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></a>
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
                            route('admin.sessions.question_banks',['id'=>$id])
                        );
                    @endphp
                </div><!--end .box-body -->
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>



@endsection

@section('footer')

    <div class="modal fade" id="importModalMcq" tabindex="-1" role="dialog" aria-labelledby="importModalMcqLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ __lang('import-questions') }} - MCQ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form enctype="multipart/form-data"
                      id="importform"
                      method="post"
                      action="{{ route('admin.sessions.question_banks.import',['id'=>$id]) }}">
                    @csrf
                    <div class="modal-body">
                        <p>
                            {!! __lang('import-questions-help',['link'=>basePath().'/client/data/test_question_sample.csv']) !!}
                        </p>
                        <div class="form-group">
                            <label for="questions_type">COURSE ID</label>
                            <input type="text"
                                   class="form-control"
                                   name="course_name"
                                   value="{{ $sessionRow->name }}"
                                   readonly>
                            <input type="hidden"
                                   class="form-control"
                                   name="course_id"
                                   value="{{ $id }}"
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label for="questions_type">Classes</label>
                            <select class="form-control"
                                   name="class_id">
                                <option value="">Select an Option</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="questions_type">Type</label>
                            <input type="text"
                                   class="form-control"
                                   name="questions_type"
                                   value="MCQ" readonly>
                        </div>
                        <div class="form-group">
                            <label for="question">{{ __lang('csv-file') }}</label>
                            <input type="file" class="form-control" name="file" >
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

    <div class="modal fade" id="importModalWritten" tabindex="-1" role="dialog" aria-labelledby="importModalWrittenLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ __lang('import-questions') }} - MCQ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form enctype="multipart/form-data"
                      id="importform"
                      method="post"
                      action="{{ route('admin.sessions.question_banks.import',['id'=>$id]) }}">
                    @csrf
                    <div class="modal-body">
                        <p>
                            {!! __lang('import-questions-help',['link'=>basePath().'/client/data/test_question_sample.csv']) !!}
                        </p>
                        <div class="form-group">
                            <label for="questions_type">COURSE ID</label>
                            <input type="text"
                                   class="form-control"
                                   name="course_name"
                                   value="{{ $sessionRow->name }}"
                                   readonly>
                            <input type="hidden"
                                   class="form-control"
                                   name="course_id"
                                   value="{{ $id }}"
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label for="questions_type">Classes</label>
                            <select class="form-control"
                                    name="class_id">
                                <option value="">Select an Option</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="questions_type">Type</label>
                            <input type="text"
                                   class="form-control"
                                   name="questions_type"
                                   value="WRITTEN" readonly>
                        </div>
                        <div class="form-group">
                            <label for="question">{{ __lang('csv-file') }}</label>
                            <input type="file" class="form-control" name="file" >
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

    <script type="text/javascript">

    </script>
@endsection
