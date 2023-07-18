{{--{{ dd($student_tests_id) }}--}}
<table class="table table-stripped">
    <thead>
    <tr>
        <th>{{ __lang('question') }}</th>
        <th>{{ __lang('answer') }}</th>
        <th>{{ __lang('correct') }}</th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
    <tr>
        <td>
            {!! clean($row->question) !!}</td>

        @if($test->exam_type == 0)
            <td>{{ $row->option}}</td>
        @else
            <td>{!! $row->answer  !!}</td>
        @endif
        @if($test->exam_type == 0)
            <td>{{ boolToString($row->is_correct) }}</td>
        @else
            <td>
                <div id="mark_result_{{ $row->test_question_id }}"></div>
                <div class="input-group input-group-sm"
                     id="div_hidden_">
                    {{--<span class="input-group-addon">Mark:</span>--}}
                    <input type="number"
                           id="mark_{{ $row->test_question_id }}"
                           class="form-control input-sm required"
                           name="mark"
                           value="{{ $row->marks_percentage }}"
                           min="0"
                           max="100"
                           placeholder="Enter obtained percentage">
                    <span class="input-group-btn">
					<button type="button"
                            id="btn_{{ $row->test_question_id }}"
                            class="btn btn-info btn-flat"
                            onclick="calculate_results({{ $row->test_question_id }},{{ $test->id }},{{ $student_tests_id }}, {{ $row->student_test_test_option_id }})"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">Save</button>
					</span>
                </div>

            </td>
        @endif
    </tr>
    @php endforeach;  @endphp
    </tbody>

</table>

