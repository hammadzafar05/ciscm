@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.certificate.index')=>__lang('certificates'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection


@section('content')


<div>
    <table class="table table-striped datatable">
        <thead>
            <tr>
                <th>{{__lang('certificate Name')}}</th>
                <th>{{__lang('student')}}</th>
                <th>{{__lang('tracking-number')}}</th>
                <th>{{__lang('downloaded-on')}}</th>
                <th>login</th>
            </tr>
        </thead>

        <tbody>

        @foreach($students as $student)
            @php
                $row = App\Student::with('user')->find($student->student_id);
            @endphp
            @if($row)
            <tr>
                <td>{{ $pageTitle }}</td>
                <td>
                    <a class="viewbutton"
                       style="text-decoration: underline"
                       data-id="{{ $student->student_id }}"
                       data-toggle="modal"
                       data-target="#simpleModal"
                       href="">{{ $row->user->name }} {{ $row->user->last_name }}</a>
                </td>
                <td>{{ $student->tracking_number }}</td>
                <td>{{ showDate('d/M/Y',$student->created_at) }}</td>
                <td>
                    <a href="{{ route('impersonate.impersonate',$row->user_id) }}">Login as {{ $student->student->user->name }} {{ $student->student->user->last_name }}</a>
                </td>
            </tr>
            @endif
        @endforeach

        </tbody>

    </table>

</div>
<div>
    {{ $students->links() }}
</div>


@endsection

@section('footer')
    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="simpleModalLabel">{{__lang('student-details')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body" id="info">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{__lang('close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->

    <script src="{{ asset('client/themes/admin/assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('client/themes/admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pdfmake/build/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('Loading...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ url('admin/student/view') }}'+'/'+id);
            });

            $('.datatable').DataTable({
                dom: 'lfrtipB',
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

        });
    </script>
@endsection
