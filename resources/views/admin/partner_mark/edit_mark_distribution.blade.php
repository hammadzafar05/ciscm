<table class="table table-bordered table-striped table-condensed"
       id="example">
    <thead>
        <tr>
            <th id="">#</th>
            <th id="">Name</th>
            <th id="">Attendance Mark (20)</th>
            <th id="">Assignment Mark (30)</th>
            <th id="">Assessment Mark (50)</th>
            <th id="">Status</th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden"
           id="title"
           class="form-control required"
           name="title"
           value="{{ $output['session_name'] }}">
    <input type="hidden"
           id="attendance_mark"
           class="form-control required"
           name="marks[attendance_mark]"
           value="20">
    <input type="hidden"
           id="assignment_mark"
           class="form-control required"
           name="marks[assignment_mark]"
           value="30">
    <input type="hidden"
           id="assessment_mark"
           class="form-control required"
           name="marks[assessment_mark]"
           value="50">
    @foreach($output['students'] as $key => $student)
        <tr>
            <td id="">
                {{ $loop->iteration }}
                <input type="hidden"
                       id="mark_distributions_id_{{ $key }}"
                       class="form-control required"
                       name="mark_distributions_id[{{ $key }}]"
                       value="{{ $output['edit_mark_distributions_id'][$key] }}"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
            </td>
            <td id="">
                {{ $student }}
                <input type="hidden"
                       id="student_id_{{ $key }}"
                       class="form-control required"
                       name="student_id[{{ $key }}]"
                       value="{{ $key }}"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
                <input type="hidden"
                       id="student_name{{ $key }}"
                       class="form-control required"
                       name="student_name[{{ $key }}]"
                       value="{{ $student }}"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
            </td>
            <td id="">
                <input type="number"
                       id="attendance_mark_{{ $key }}"
                       class="form-control required"
                       name="attendance_mark[{{ $key }}]"
                       value="{{ $output['edit_attendance_mark'][$key] }}"
                       max="20"
                       min="0"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
            </td>
            <td id="">
                <input type="number"
                       id="assignment_mark_{{ $key }}"
                       class="form-control required"
                       name="assignment_mark[{{ $key }}]"
                       value="{{ $output['edit_assignment_mark'][$key] }}"
                       max="30"
                       min="0"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
            </td>
            <td id="">
                <input type="number"
                       id="assessment_mark_{{ $key }}"
                       class="form-control required"
                       name="assessment_mark[{{ $key }}]"
                       value="{{ $output['edit_assessment_mark'][$key] }}"
                       max="50"
                       min="0"
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval'){{ 'readonly' }} @endif>
            </td>
            <td>
                @if($output['distribution_status'][$key] == 'Final' || $output['distribution_status'][$key] == 'Submit for Approval')
                    {{ $output['distribution_status'][$key] }}
                    <input type="hidden"
                           id="distribution_status_{{ $key }}"
                           class="form-control required"
                           name="distribution_status[{{ $key }}]"
                           value="{{ $output['distribution_status'][$key] }}">
                @else
                    <select id="distribution_status_{{ $key }}"
                            name="distribution_status[{{ $key }}]"
                            class="form-control required"
                            required>
                        <option value="Draft" {{ $output['distribution_status'][$key] == 'Draft' ? 'selected="selected"' : '' }}>Draft</option>
                        <option value="Submit for Approval" {{ $output['distribution_status'][$key] == 'Submit for Approval' ? 'selected="selected"' : '' }}>Submit for Approval</option>
                        <option value="Back to Partner" {{ $output['distribution_status'][$key] == 'Back to Partner' ? 'selected="selected"' : '' }}>Back to Partner</option>
                    </select>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


<div class="form-group" style="display: none">
    <label for="filter">Status</label>
    <select id="status"
            name="status"
            class="form-control required"
            required>
        <option value="Draft" {{ $output['edit_status'] == 'Draft' ? 'selected="selected"' : '' }}>Draft</option>
        <option value="Submit for Approval" {{ $output['edit_status'] == 'Submit for Approval' ? 'selected="selected"' : '' }}>Submit for Approval</option>
        <option value="Back to Partner" {{ $output['edit_status'] == 'Back to Partner' ? 'selected="selected"' : '' }}>Back to Partner</option>
    </select>
</div>

