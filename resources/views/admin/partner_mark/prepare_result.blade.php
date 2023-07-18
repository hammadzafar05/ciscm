@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('/admin/partner_mark/index')=>__lang('mark-management'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('content')

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
                <td id="" width="250px">
                    <div id="mark_result_{{ $markD->id }}"></div>
                    <div class="input-group input-group-sm"
                         id="div_hidden_">
                        {{--<span class="input-group-addon">Mark:</span>--}}
                        <input type="number"
                               id="mark_{{ $markD->id }}"
                               class="form-control input-sm required"
                               name="mark"
                               value="{{ $markD->admin_mark }}"
                               min="0"
                               max="100"
                               placeholder="Enter obtained percentage">
                        <span class="input-group-btn">
					<button type="button"
                            id="btn_{{ $markD->id }}"
                            class="btn btn-info btn-flat"
                            onclick="calculate_results({{ $markD->id }})"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">Save</button>
					</span>
                    </div>
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

@endsection
@section('footer')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var change_status = function(id,name,pk,table,value){

            /*var name = jQuery("select#"+id).data('name');
            alert('id-->'+id)
            var pk = jQuery("select#"+id).data('pk');
            var table = jQuery("select#"+id).data('table');
            var value = jQuery("select#"+id).val();*/

            jQuery("#typechangeLoader"+id).html('<i class="fa fa-spin fa-spinner"></i>');
            var datastring = "name="+name+"&pk="+pk+"&table="+table+"&value="+value;

            $.ajax({
                type: "POST",
                '_token': '{{ csrf_token() }}',
                url: "./editable/update_editable",
                data: datastring,
                success: function(response) {
                    alert(response.message);
                    jQuery("#typechangeLoader"+id).html('');
                },
                error: function(jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Not connect. Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Requested page not found. [404].');
                    } else if (jqXHR.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Time out error.');
                    } else if (exception === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        alert('Uncaught Error.' + jqXHR.responseText);
                    }
                    $('button[name=nameSubmitBtn]').button('reset');
                }
            });
            return false;
        }
        function calculate_results(mark_distribution_id) {
            if (jQuery("#mark_" + mark_distribution_id).val() == '') {
                alert('Put obtained marks in the field!');
            } else {
                var mark = jQuery("#mark_" + mark_distribution_id).val();
                $("#btn_" + mark_distribution_id).button('loading');

                var datastring = "name=admin_mark&pk="+mark_distribution_id+"&table=mark_distributions&value="+mark;

                $.ajax({
                    type: "POST",
                    url: "./editable/update_editable",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: datastring,
                    success: function(response) {
                        console.log(response);
                        $("#btn_" + mark_distribution_id).button('reset');

                        if (response.status == 'success') {
                            alert(response.message);
                            location.reload();
                        } else if (response.status == 'error') {
                            alert(response.message);
                        } else {
                            alert('Unable to save into database. Please try again!');
                        }
                    }
                }).fail(function() {
                    $("#btn_" + mark_distribution_id).button('reset');
                    alert('{{ __lang('network - error ') }}');
                });
            }
        }
    </script>

@endsection



