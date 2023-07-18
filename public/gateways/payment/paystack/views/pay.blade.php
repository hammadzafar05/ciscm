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
        <form id="paymentForm" action="{{ route('cart.callback',['code'=>$code]) }}" method="POST" >
            <script
                src="https://js.paystack.co/v1/inline.js"
                data-key="{{  trim(paymentOption($code,'public_key')) }}"
                data-email="{{ $user->email }}"
                data-amount="{{ ($invoice->amount * 100) }}"
                data-ref="{{ $transaction->id }}"

            >
            </script>

        </form>
    </div>

@endsection
