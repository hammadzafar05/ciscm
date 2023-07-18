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
        <form id="test_pos" method="POST" action="https://community.ipaygh.com/gateway">
            <input name="merchant_key" value="{{ paymentOption($code,'key') }}" type="hidden">
            <input name="success_url" value="{{ route('cart.complete') }}" type="hidden">
            <input name="cancelled_url" value="{{ route('cart') }}" type="hidden">
            <input name="deferred_url" value="{{ route('cart') }}" type="hidden">
            <input name="ipn_url" value="{{ route('cart.ipn',['code'=>$code]) }}" type="hidden">

            <input name="total" value="{{ $invoice->amount }}" type="hidden">
            <input name="invoice_id" id="invoice_id" value="{{ $transaction->id  }}" type="hidden">

            <button class="btn btn-primary" type="submit">{{ __lang('make-payment') }}</button>
        </form>
    </div>

@endsection
