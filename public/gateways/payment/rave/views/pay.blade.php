@extends('layouts.checkout')
@section('page-title',$method->label)


@section('payment-content')

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
        <form>
            <a class="flwpug_getpaid"
               data-PBFPubKey="{{ paymentOption($code,'public_key') }}"
               data-txref="{{ $transaction->id }}"
               data-amount="{{ $invoice->amount }}"
               data-customer_email="{{ $invoice->user->email }}"
               data-customer_phonenumber="{{ $invoice->user->student->mobile_number }}"
               data-currency="{{ strtoupper($invoice->currency->country->currency_code) }}"
               data-country="{{  strtoupper($invoice->currency->country->iso_code_2) }}"
               data-redirect_url="{{ route('cart.callback',['code'=>$code]) }}"></a>

            @if(paymentOption($code,'mode')==0)
            <script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
            @else
            <script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
            @endif
        </form>


    </div>

@endsection
