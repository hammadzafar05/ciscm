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
@endsection

@section('footer')
    <script src="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pdfmake/build/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

@endsection
@section('content')

@yield('content')

<script>
    $(document).ready(function() {
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
    } );
</script>

@endsection
