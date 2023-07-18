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

<a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __lang('add-new') }}</a>
<br> <br>
<div class="table-responsive_ ">
    <table class="table   table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Partner</th>
            <th>Course</th>
            <th>{{ __lang('amount') }}</th>
            <th>{{ __lang('currency') }}</th>
            <th>Invoice Date</th>
            <th>Payment Date</th>
            <th>{{ __lang('status') }}</th>
            <th  >{{__lang('actions')}}</th>
        </tr>

        </thead>
        <tbody>
        @php foreach($paginator as $row):  @endphp
        <tr>
            <td>{{ $row->id }}</td>
            <td>
                @if($row->user)
                {{ $row->user->name }} {{ $row->user->last_name }} ({{ $row->user->email }})
                @else
                N/A
                @endif

            </td>
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
            <td>{{formatCurrency($row->amount,$row->currency->country->currency_code)}}</td>
            <td>{{ $row->currency->country->currency_code }}</td>
            <td>{{ showDate('d/M/Y',$row->sent_date) }}</td>
            <td>{{ showDate('d/M/Y',$row->payment_date) }}</td>
            <td>
                {{ $row->status }}
            </td>
            <td>
                @if($row->status != 'Approved')
                <div class="button-group dropleft">
                    <button class="btn btn-primary dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        {{ __lang('actions') }}
                    </button>
                    <div class="dropdown-menu">

                        <a class="dropdown-item"
                           onclick="return confirm('{{__lang('invoice-approve-confirm')}}')"
                           href="{{ adminUrl(array('controller'=>'partner','action'=>'approvepartnertransaction','id'=>$row->id)) }}">
                            <i class="fa fa-check"></i> {{ __lang('approve') }}
                        </a>
                        <a class="dropdown-item"
                           href="{{ adminUrl(array('controller'=>'partner','action'=>'deleteinvoice','id'=>$row->id)) }}"
                           onclick="return confirm('{{ __lang('delete-confirm') }}')"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>

                    </div>
                </div>

                @endif
            </td>
        </tr>
        @php endforeach;  @endphp





        </tbody>
    </table>
    <div>{{$paginator->links()}}</div>

</div><!--end .box-body -->
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/jquery.chained.js') }}"></script>
    <form method="post" action="{{ route('admin.partner.create-invoice') }}">
        @csrf
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __lang('create-invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="items">Partners</label>
                        <select id="ptrn_id"
                                name="ptrn_id"
                                class="form-control">
                            <option value="">Select an option</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->user_details->name.' '.$partner->user_details->last_name }} ({{ $partner->user_details->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="items">Courses</label>
                        <select id="course_id"
                                name="course_id"
                                class="form-control"
                                onchange="set_fee()">
                            <option value="">Select an option</option>
                            @foreach($courses as $course)
                                <option value="{{ $course['id'] }}"
                                        class='{{ $course['admin_id'] }}'
                                        data-fee="{{ ($course['fee']) }}"
                                        data-admin_id="{{ ($course['admin_id']) }}"
                                        data-user_id="{{ ($course['user_id']) }}"
                                >{{ $course['name'] }} ({{ price($course['fee']) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group display-hide">
                        <label for="partner_admin_id">partner_admin_id</label>
                        <input type="number"
                               class="digit form-control"
                               id="partner_admin_id"
                               name="partner_admin_id"
                               value="0"
                               min="0" readonly>
                    </div>
                    <div class="form-group display-hide">
                        <label for="partner_user_id">partner_user_id</label>
                        <input type="number"
                               class="digit form-control"
                               id="partner_user_id"
                               name="partner_user_id"
                               value="0"
                               min="0" readonly>
                    </div>
                    <div class="form-group course_form">
                        <label>Student Lists by Course</label>
                        <select id="students_ids"
                                name="students_ids[]"
                                class="form-control select2"
                                multiple
                                onchange="set_fee()">
                            @foreach($student_by_courses as $student)
                                <option value="{{ $student['student_id'] }}"
                                        class='{{ $student['session_id'] }}'
                                        data-subtext='{{ $student['email'] }}'>{{ $student['name'] }} - {{ $student['email'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number"
                               class="digit form-control"
                               id="amount"
                               name="amount"
                               value=""
                               min="0" readonly>
                    </div>
                    <div class="form-group">
                        <label for="currency_id">Currency</label>
                        <select name="currency_id"
                                id="currency_id"
                                class="select2 form-control">
                            <option></option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->country->currency_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">{{ __lang('status') }}</label>
                        <select name="status" id="status" class="form-control">
                            <option value="unpaid">{{ __lang('unpaid') }}</option>
                            <option value="paid">{{ __lang('paid') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __lang('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __lang('create') }}</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <script type="text/javascript">
        var set_fee = function (){
            var fee  = $('#course_id').find(':selected').data('fee');

            var partner_user_id  = $('#course_id').find(':selected').data('user_id');
            $('#partner_user_id').val(partner_user_id);

            var partner_admin_id  = $('#course_id').find(':selected').data('admin_id');
            $('#partner_admin_id').val(partner_admin_id);

            //alert('fee-->'+fee)
            var count = $("#students_ids :selected").length;
            if (count == ''){
                count = 0;
            }
           // alert('count-->'+count)
            var total = parseFloat(count) * parseFloat(fee);
            //alert('total-->'+total)
            $('#amount').val(total);
        }
        $("#course_id").chained("#ptrn_id");
        $("#students_ids").chained("#course_id");

    </script>
@endsection
