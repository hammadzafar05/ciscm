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
@endphp
<table class="table table-bordered table-striped table-condensed datatableE"
       id="example">
    <thead>
    <tr>
        <th id="">#</th>
        <th id="">Name</th>
        <th id="">Email</th>
        <th id="">Attendance Mark ({{ $mark_calc['attendance_mark'] }})</th>
        <th id="">Assignment Mark ({{ $mark_calc['assignment_mark'] }})</th>
        <th id="">Assessment Mark ({{ $mark_calc['assessment_mark'] }})</th>
        <th id="">Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($markDistributionTable as $markD)
            <tr>
                <td id="">{{ $loop->iteration }}</td>
                <td id="">{{ $markD->first_name.' '.$markD->last_name }}</td>
                <td id="">{{ $markD->email }}</td>
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
                    @if($markD->distribution_status == 'Final')
                        {{ $markD->distribution_status }}
                    @else
                        @if($markD->distribution_status != 'Draft')
                            <select id="mark_distribution_status_{{ $markD->id }}"
                                    name="mark_distribution_status[{{ $markD->id }}]"
                                    class="form-control "
                                    required
                                    onchange="change_status({{ $markD->id }},'distribution_status',{{ $markD->id }},'mark_distributions',this.value)"
                            >
                                <option value="Submit for Approval" {{ $markD->distribution_status == 'Submit for Approval' ? 'selected="selected"' : '' }}>Submit for Approval</option>
                                <option value="Final" {{ $markD->distribution_status == 'Final' ? 'selected="selected"' : '' }}>Final</option>
                                <option value="Back to Partner" {{ $markD->distribution_status == 'Back to Partner' ? 'selected="selected"' : '' }}>Back to Partner</option>
                            </select>
                        @else
                            <p>{{ $markD->distribution_status }}</p>
                        @endif
                    @endif
                </td>
            </tr>
    @endforeach
    </tbody>
</table>


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

    </script>

@endsection



