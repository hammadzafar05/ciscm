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
         <form action="{{ route('cart.callback',['code'=>$code]) }}" method="POST">
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ paymentOption($code,'publishable_key') }}"
                data-currency="{{ $invoice->currency->country->currency_code }}"
                data-amount="{{ ($invoice->amount * 100) }}"
                data-name="{{ setting('general_site_name') }}"
                data-description="{{ $description }}"
                data-email="{{ $user->email }}"
                data-image="{{ asset(resizeImage(setting('image_logo'),128,128,url('/'))) }}"
                data-locale="auto"
                data-zip-code="true">
            </script>
        </form>


    </div>

@endsection
