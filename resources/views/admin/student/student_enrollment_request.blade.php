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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var change_status = function(id){

            var name = jQuery("select#"+id).data('name');
            var pk = jQuery("select#"+id).data('pk');
            var table = jQuery("select#"+id).data('table');
            var value = jQuery("select#"+id).val();

            jQuery("#typechangeLoader"+id).html('<i class="fa fa-spin fa-spinner"></i>');
            var datastring = "name="+name+"&pk="+pk+"&table="+table+"&value="+value;

            $.ajax({
                type: "POST",
                '_token': '{{ csrf_token() }}',
                url: "./editable/update_editable",
                data: datastring,
                success: function(response) {
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

@section('content')
<div >
    <div >
        <div class="card">
            {{--
            <div class="card-header">
                <header>

                    <p class="well">{{ __lang('active-student-def') }}</p>
                </header>

            </div>--}}
            <div class="card-body">

                <div class="table-responsive">
                    <table id="student_enrollment_request" class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>{{ __lang('id') }}</th>
                            <th>Course</th>
                            <th>{{ __lang('first-name') }}</th>
                            <th>{{ __lang('last-name') }}</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Organization</th>
                            <th>Designation</th>
                            <th>Country</th>
                            <th class="text-right1"  >{{__lang('actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->course_name }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->last_name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->mobile_number }}</td>
                                <td>{{ $row['4_Organization'] }}</td>
                                <td>{{ $row['3_Designation'] }}</td>
                                <td>{{ $row['2_Country'] }}</td>
                                <td class="text-right1">
                                    @if($admin_role == 'Partner')
                                        {{ $row->status }}
                                    @else
                                        @if($row->status == 'Approve')
                                            {{ $row->status }}
                                        @else
                                            <select id="{{ $row->id }}"
                                                    class="form-control "
                                                    name="customerType"
                                                    onchange="change_status(this.id)"
                                                    data-name="status"
                                                    data-type="select"
                                                    data-pk="{{ $row->id }}"
                                                    data-table="ptrn_students" style="width: 100px">
                                                <option value="Pending" {{ ($row->status == 'Pending' ? 'selected' : '') }}>Pending</option>
                                                <option value="Approve" {{ ($row->status == 'Approve' ? 'selected' : '') }}>Approve</option>
                                                <option value="Decline" {{ ($row->status == 'Decline' ? 'selected' : '') }}>Decline</option>
                                            </select>
                                            <span id="typechangeeLoader_{{ $row->id }}">&nbsp;</span>


                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>
@endsection

@section('footer')

    <!-- START SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="simpleModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="simpleModalLabel">{{ __lang('student-details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="info">

                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->

    <script type="text/javascript">
        $(function(){
            $('.viewbutton').click(function(){
                $('#info').text('{{__lang('loading')}}...');
                var id = $(this).attr('data-id');
                $('#info').load('{{ adminUrl(array('controller'=>'student','action'=>'view'))}}'+'/'+id);
            });
        });
    </script>

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
