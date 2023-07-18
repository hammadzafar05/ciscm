@extends('layouts.checkout')
@section('page-title',$method->label)

@section('page-class')
    class="col-md-8 offset-md-2"
@endsection
@section('payment-content')

    <div class="row">
        <div class="col-md-6">
            <h4>{{ __lang('billing-address') }}</h4>
            <table class="table table-striped">
                <tr>
                    <th>{{ __lang('firstname') }}</th>
                    <td>{{ $user->billing_firstname }}</td>
                </tr>
                <tr>
                    <th>{{ __lang('lastname') }}</th>
                    <td>{{ $user->billing_lastname }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.country') }}</th>
                    <td>
                        @if(\App\Country::find($user->billing_country_id))
                        {{ \App\Country::find($user->billing_country_id)->name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('default.state') }}</th>
                    <td>{{ $user->billing_state }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.city') }}</th>
                    <td>{{ $user->billing_city }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.address-1') }}</th>
                    <td>{{ $user->billing_address_1  }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.address-2') }}</th>
                    <td>{{ $user->billing_address_2 }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.zip') }}</th>
                    <td>{{ $user->billing_zip }}</td>
                </tr>



            </table>

            <button onclick="openModal('{{ addslashes(__lang('billing-address')) }}','{{ route('student.student.billing') }}')" class="btn btn-primary"><i class="fa fa-edit"></i> {{ __lang('edit') }}</button>

        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th>{{ __lang('amount') }}</th>
                    <td>{{ price(getCart()->getCurrentTotal()) }}</td>
                </tr>
                <tr>
                    <th>{{ __lang('invoice-id') }}</th>
                    <td>{{ $invoice->id }}</td>
                </tr>

            </table>
            <div class="text-center">

                <form id="2checkoutform" action='{{ $action }}' method='post'>
                    <input type='hidden' name='sid' value='{{ paymentOption($code,'account_number') }}' />
                    <input type='hidden' name='mode' value='2CO' />
                    <input type="hidden" name="currency_code" value="{{ strtoupper($invoice->currency->country->currency_code) }}"/>
                    <input type='hidden' name='li_0_type' value='product' />
                    <input type='hidden' name='li_0_name' value='{{ $description }}' />
                    <input type='hidden' name='li_0_price' value='{{ $invoice->amount }}' />
                    <input type='hidden' name='li_0_tangible' value='N' />
                    <input type='hidden' name='li_0_product_id' value='{{ $invoice->id }}' />
                    <input type='hidden' name='x_receipt_link_url' value='{{ route('cart.callback',['code'=>$code]) }}' />
                    <input type='hidden' name='card_holder_name' value='{{ $user->billing_firstname }}' />
                    <input type='hidden' name='street_address' value='{{ $user->billing_address_1 }}' />
                    <input type='hidden' name='street_address2' value='{{ $user->billing_address_2 }}' />
                    <input type='hidden' name='city' value='{{ $user->billing_city }}' />
                    <input type='hidden' name='state' value='{{ $user->billing_state }}' />
                    <input type='hidden' name='zip' value='{{ $user->billing_zip }}' />
                    @if(\App\Country::find($user->billing_country_id))
                    <input type='hidden' name='country' value='{{ strtoupper(\App\Country::find($user->billing_country_id)->iso_code_3) }}' />
                    @endif
                    <input type='hidden' name='email' value='{{ $user->email }}' />
                    @if($user->student)
                    <input type='hidden' name='phone' value='{{ $user->student->mobile_number }}' />
                    @endif
                    <input type='hidden' name='merchant_order_id' value='{{ $invoice->id }}' />
                    @if($mode!=1)
                        <input type="hidden" name="demo" value="Y">
                    @endif

                    <button class="btn btn-success btn-block btn-lg"><i class="fa fa-money-bill"></i> {{ __lang('make-payment') }}</button>
                </form>
                <script src="https://www.2checkout.com/static/checkout/javascript/direct.min.js"></script>



            </div>
        </div>
    </div>



@endsection
