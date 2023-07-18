@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
    <div class="table-responsive_ ">
        <table class="table   table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Course</th>
                <th>Students</th>
                <th>{{ __lang('amount') }}</th>
                <th>{{ __lang('currency') }}</th>
                <th>{{ __lang('created-on') }}</th>
                <th>{{ __lang('status') }}</th>
                <th  >{{__lang('actions')}}</th>
            </tr>

            </thead>
            <tbody>
            @foreach($paginator as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->invoice_number }}</td>
                <td>
                    @if($row->course)
                        @php
                            $course_name = explode(' - Batch ',$row->course->name);
                            $course_name = @$course_name[0];
                        @endphp
                        {{ $course_name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    {!! $row->students !!}
                </td>
                <td>{{formatCurrency($row->amount,$row->currency->country->currency_code)}}</td>
                <td>{{ $row->currency->country->currency_code }}</td>
                <td>{{ showDate('d/M/Y',$row->sent_date) }}</td>
                <td>
                    {{ $row->status }}
                </td>
                <td>
                    @if($row->status == 'unpaid')
                    <a href="#"
                       id="{{ $row->id }}"
                       class="btn btn-danger"
                       data-toggle="modal"
                       data-target="#modalBootstrap"
                       data-modal_size="modal-lg"
                       data-page_link="./view/{{ $row->id }}"
                       data-modal_title="{{ $row->invoice_number }}"
                    >
                        <i class="fa fa-info-circle"></i>
                        {{ __lang('view') }}
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach

            </tbody>
        </table>
        <div>{{$paginator->links()}}</div>

    </div><!--end .box-body -->
@endsection

@section('footer')

<div class="modal fade"
     id="modalBootstrap"
     tabindex="-1"
     role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true"
     data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog">{{--modal-lg | modal-sm--}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title" style="float: left !important;"></h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/jquery-validate/jquery.validate.min.js')}}"></script>
<script>
    $(document).on('click', 'a[data-target="#modalBootstrap"]', function(event) {
        event.preventDefault();
        var id = $(this).attr('id');
        var page_link = $(this).data('page_link');
        var modal_title = $(this).data('modal_title');
        $('#modal_title').html(modal_title);

        var modal_size = $(this).data('modal_size');
        $('.modal-dialog').addClass(modal_size);

        var myModal = $('#modalBootstrap');
        var modalBody = myModal.find('.modal-body');
        modalBody.html('<div class="text-center"><em class="fa fa-4x fa-spin fa-spinner"></em></div>');
        modalBody.load(page_link, function() {
            myModal.modal('show');
        });
    });


    /*$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });*/

    var change_status = function(id,name,pk,table,value){
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
