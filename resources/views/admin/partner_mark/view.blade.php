<table class="table table-striped">
    <tbody>
        <tr>
            <th>Course:</th>
            <td>{{ $mark_row->course_name }}</td>
        </tr>
        <tr>
            <th>Created By</th>
            <td>{{ adminName($mark_row->admin_id) }}</td>
        </tr>
        {{--<tr>
            <th>Status</th>
            <td>{{ $mark_row->status }}</td>
        </tr>--}}
        <tr>
            <th>Created On</th>
            <td>{{ showDate('d/m/Y',$mark_row->created_at) }}</td>
        </tr>
    </tbody>
</table>
@php
    $mark_calc = unserialize($mark_row->mark_calc);
    function convert_marks($marks,$convert_to){
        $marks = round((($marks / 100) * $convert_to),2);
        return $marks;
    }
@endphp
<div class="table-responsive">
<table class="table table-bordered table-striped table-condensed datatableE"
       id="example">
    <thead>
    <tr>
        <th id="">#</th>
        <th id="">Name</th>
        {{--<th id="">Email</th>--}}
        <th id="">Attendance Mark ({{ $mark_calc['attendance_mark'] }})</th>
        <th id="">Assignment Mark ({{ $mark_calc['assignment_mark'] }})</th>
        <th id="">Assessment Mark ({{ $mark_calc['assessment_mark'] }})</th>
        <th id="">Status</th>
        <th id="">Partner Mark(60%)</th>
        <th id="">Admin Mark(100%)</th>
        <th id="">Admin Mark(40%)</th>
        <th id="">Total(100)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($markDistributionTable as $markD)
        <tr>
            <td id="">{{ $loop->iteration }}</td>
            <td id="">{{ $markD->first_name.' '.$markD->last_name }}<br>{{ $markD->email }}</td>
            {{--<td id="">{{ $markD->email }}</td>--}}
            <td id="">
                {{ $markD->attendance_mark }}
            </td>
            <td id="">
                {{ $markD->assignment_mark }}
            </td>
            <td id="">
                {{ $markD->assessment_mark }}
            </td>
            <td id="">
                {{ $markD->distribution_status }}
            </td>
            <td id="">
                {{--{{ $markD->partner_60_percent_mark }}--}}
                @php
                    $partner_60_percent_mark = convert_marks($markD->total_mark,60);
                    echo $partner_60_percent_mark;
                @endphp
            </td>
            <td id="">
                {{ $markD->admin_mark }}
            </td>
            <td id="">
                {{--{{ $markD->admin_40_percent_mark }}--}}
                @php
                    $admin_40_percent_mark = convert_marks($markD->admin_mark,40);
                    echo $admin_40_percent_mark;
                @endphp
            </td>
            <td id="">
                {{ ($partner_60_percent_mark + $admin_40_percent_mark) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<script>
    $('.datatableE').DataTable({
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        lengthMenu: [ [10, 25, 50,75, 100, -1], [10, 25, 50, 75, 100, "{{__lang('all')}}"]  ],
        responsive: true,
        language: {
            "decimal":        "",
            "emptyTable":     "No data available in table",
            "info":           "{{__lang('Showing')}} _START_ {{__lang('to')}} _END_ {{__lang('of')}} _TOTAL_ {{__lang('entries')}}",
            "infoEmpty":      "{{__lang('Showing')}} 0 to 0 {{__lang('of')}} 0 {{__lang('entries')}}",
            "infoFiltered":   "({{__lang('filtered-from')}}  _MAX_ {{__lang('total')}} {{__lang('entries')}})",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "{{__lang('show')}} _MENU_ {{__lang('entries')}}",
            "loadingRecords": "{{__lang('loading')}}...",
            "processing":     "{{__lang('processing')}}...",
            "search":         "{{__lang('search')}}:",
            "zeroRecords":    "{{__lang('no-matching-records')}}",
            "paginate": {
                "first":      "{{__lang('First')}}",
                "last":       "{{__lang('Last')}}",
                "next":       "{{__lang('Next')}}",
                "previous":   "{{__lang('Previous')}}"
            },
            "aria": {
                "sortAscending":  ": {{__lang('sort-ascending')}}",
                "sortDescending": ": {{__lang('sort-descending')}}"
            }
        }
    } );
</script>



