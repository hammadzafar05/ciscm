@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://raw.githubusercontent.com/Talv/x-editable/develop/dist/bootstrap4-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('client/vendor/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}">
@endsection




@section('content')


<br> <br>
<div class="table-responsive">
    <table id="student_enrollment_request" class="table table-striped datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>Course name</th>
            <th>{{ __lang('first-name') }}</th>
            <th>{{ __lang('last-name') }}</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Organization</th>
            <th>Designation</th>
            <th>Country</th>
            <th>Date</th>
        </tr>

        </thead>
        <tbody>
        @foreach($paginator as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->course_name }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->last_name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->mobile_number }}</td>
            <td>{{ $row['organization'] }}</td>
            <td>{{ $row['designation'] }}</td>
            <td>{{ $row['country'] }}</td>
            <td>{{ showDateTime('d/m/Y h:i A',$row['created_at']) }}</td>
        </tr>
        @endforeach

        </tbody>
    </table>
    {{--<div>{{$paginator->links()}}</div>--}}

</div><!--end .box-body -->
@endsection


@section('footer')
    <script src="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

    <script>
        $('.datatable').DataTable({
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



@endsection
