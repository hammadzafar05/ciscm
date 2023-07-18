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
            <td id="">{{ $loop->iteration }}</td>
            <td id="">
                {{ $student }}
                <input type="hidden"
                       id="student_id_{{ $key }}"
                       class="form-control required"
                       name="student_id[{{ $key }}]"
                       value="{{ $key }}">
                <input type="hidden"
                       id="student_name{{ $key }}"
                       class="form-control required"
                       name="student_name[{{ $key }}]"
                       value="{{ $student }}">
            </td>
            <td id="">
                <input type="number"
                       id="attendance_mark_{{ $key }}"
                       class="form-control required"
                       name="attendance_mark[{{ $key }}]"
                       value="0"
                       max="20"
                       min="0">
            </td>
            <td id="">
                <input type="number"
                       id="assignment_mark_{{ $key }}"
                       class="form-control required"
                       name="assignment_mark[{{ $key }}]"
                       value="0"
                       max="30"
                       min="0">
            </td>
            <td id="">
                <input type="number"
                       id="assessment_mark_{{ $key }}"
                       class="form-control required"
                       name="assessment_mark[{{ $key }}]"
                       value="0"
                       max="50"
                       min="0">
            </td>
            <td>
                <select id="distribution_status_{{ $key }}"
                        name="distribution_status[{{ $key }}]"
                        class="form-control required"
                        required>
                    <option value="Draft">Draft</option>
                    <option value="Submit for Approval">Submit for Approval</option>
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="form-group" style="display: none">
    <label for="filter">Status</label>
    <select id="status"
            name="status"
            class="form-control"
            required>
        <option value="Draft">Draft</option>
        <option value="Submit for Approval">Submit for Approval</option>
    </select>
</div>

