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
            <th>{{ __lang('user') }}</th>
            <th>{{ __lang('items') }}</th>
            <th>{{ __lang('payment-method') }}</th>
            <th>{{ __lang('amount') }}</th>
            <th>{{ __lang('currency') }}</th>
            <th>{{ __lang('created-on') }}</th>
            <th>{{ __lang('status') }}</th>
            {{--MARUF START--}}<th>Resend</th>{{--MARUF END--}}
            <th  >{{__lang('actions')}}</th>
        </tr>

        </thead>
        <tbody>



        @php foreach($paginator as $row):  @endphp
        <tr>
            <td>{{ $row->id }}</td>
            <td>
                
                {{ $row->user->name ??""}} {{ $row->user->last_name??"" }} ({{ $row->user->email??"" }})

            </td>
            <td><a  class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample{{$row->id}}" aria-expanded="false" aria-controls="collapseExample{{$row->id}}">
                    @php
                        $cart = unserialize($row->cart);
                        try{

                            echo $cart->getTotalItems().' '.__lang('items');
                        }
                        catch(\Exception $ex){
                            echo '0 '.__lang('items');
                        }
                    @endphp <span class="caret"></span></a>
            </td>
            <td>
                @if($row->paymentMethod)
                    {{ $row->paymentMethod->name }}
                @endif
            </td>
            <td>

                @php
                    $currency_code = 'USD';
                    $currency = \App\Currency::with('country')->find($row->currency_id);
					if ($currency) $currency_code = $currency->country->currency_code;
                @endphp
                {{formatCurrency($row->amount,$currency_code)}}</td>
            <td>{{ $currency_code }}</td>
            <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
            <td>
                <p>
                    @php if($row->paid == 1):  @endphp
                    <span class="text-highlight-success">{{ __lang('paid') }}</span>
                    @php else:  @endphp
                    <span class="text-highlight-danger">{{ __lang('unpaid') }}</span>
                    @php endif;  @endphp
                </p>
            </td>
            {{--MARUF START--}}
            <td>
                @php if($row->paid == 0):  @endphp
                <a class="dropdown-item"
                   onclick="return confirm('Are you sure?')"
                   href="{{ adminUrl(array('controller'=>'student','action'=>'resend_invoice','id'=>$row->id)) }}">
                    <i class="fa fa-envelope"></i> Resend
                </a>
                @php endif;  @endphp
            </td>
            {{--MARUF END--}}
            <td>
                <div class="button-group dropleft">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __lang('actions') }}
                    </button>
                    <div class="dropdown-menu">
                        @php if($row->paid == 0):  @endphp
                        <a class="dropdown-item"
                           onclick="return confirm('{{__lang('invoice-approve-confirm')}}')"
                           href="{{ adminUrl(array('controller'=>'student','action'=>'approvetransaction','id'=>$row->id)) }}">
                            <i class="fa fa-check"></i> {{ __lang('approve') }}
                        </a>
                        <a   class="dropdown-item"  href="{{ adminUrl(array('controller'=>'student','action'=>'deleteinvoice','id'=>$row->id)) }}" onclick="return confirm('{{ __lang('delete-confirm') }}')"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
                        @php endif;  @endphp
                    </div>
                </div>


            </td>
        </tr>
        <tr>
            <td colspan="9" style="height: 0px">
                <div class="collapse" id="collapseExample{{$row->id}}">
                    @php if(is_object($cart)): @endphp
                    <div class="well">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ __lang('course-session') }}</th>
                                <th>{{ __lang('fee') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php foreach($cart->getSessions() as $session): @endphp
                            <tr>
                                <td>{{$session->name}}</td>
                                <td>{{ price($session->fee,$row->currency_id) }}</td>
                            </tr>

                            @php endforeach;  @endphp

                            </tbody>
                        </table>
                        @php if($cart->hasDiscount()): @endphp
                        <p>
                            <strong>{{ __lang('discount') }}:</strong> {{$cart->getDiscount()}}% <br/>
                            @php if(\App\Coupon::find($cart->getCouponId())):  @endphp
                            <strong>{{ __lang('coupon-code') }}:</strong> {{\App\Coupon::find($cart->getCouponId())->code}}
                            @php endif;  @endphp
                        </p>
                        @php endif;  @endphp
                    </div>
                    @php endif;  @endphp
                </div>
            </td>
        </tr>
        @php endforeach;  @endphp





        </tbody>
    </table>
    <div>{{$paginator->links()}}</div>

</div><!--end .box-body -->
@endsection

@section('footer')


    <form method="post" action="{{ route('admin.student.create-invoice') }}">
        @csrf
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __lang('create-invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id">{{__lang('user')}}</label>
                        <select required  name="user_id" id="user_id" ></select>
                    </div>
                    <div class="form-group">
                        <label for="items">{{__lang('courses')}}</label>
                        <select required name="courses[]" id="courses" class="form-control select2" multiple>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }} ({{ price($course->fee) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">{{ __lang('amount') }}</label>
                        <input type="text" class="digit form-control" name="amount" placeholder="{{ __lang('optional') }}">
                       <p><small>{{ __lang('invoice-amount-hint') }}</small></p>
                    </div>
                    <div class="form-group">
                        <label for="currency_id">{{ __lang('currency') }}</label>
                        <select name="currency_id" id="currency_id" class="select2 form-control">
                            <option></option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->country->currency_name }}</option>
                            @endforeach
                        </select>
                        <p><small>{{ __lang('invoice-currency-hint') }}</small></p>
                    </div>

                    <div class="form-group">
                        <label for="paid">{{ __lang('status') }}</label>
                        <select name="paid" id="paid" class="form-control">
                            <option value="0">{{ __lang('unpaid') }}</option>
                            <option value="1">{{ __lang('paid') }}</option>
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
        $(function (){
            $('#user_id').select2({
                placeholder: "@lang('default.search-users')...",
                minimumInputLength: 3,
                dropdownParent: $('#exampleModal'),
                ajax: {
                    url: '{{ route('admin.students.search') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            term: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }

            });
        });

    </script>
@endsection
