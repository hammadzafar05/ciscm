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

        @if($mode==0)
            <div class="alert alert-danger">
              {{ __lang('payu-money-warning') }}
            </div>
        @endif


        <form action="<?php echo $action ?>" method="post" id="payu_form" name="payu_form" >
            <input type="hidden" name="key" value="<?php echo $key; ?>" />
            <input type="hidden" name="txnid" value="<?php echo $tid; ?>" />
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
            <input type="hidden" name="productinfo" value="<?php echo $productinfo; ?>" />
            <input type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
            <input type="hidden" name="lastname" value="<?php echo $lastname; ?>" />
            <input type="hidden" name="email" value="<?php echo $email; ?>" />
            <input type="hidden" name="phone" value="<?php echo $phone; ?>" />
            <input type="hidden" name="surl" value="<?php echo $surl; ?>" />
            <input type="hidden" name="furl" value="<?php echo $furl; ?>" />
            <input type="hidden" name="hash" value="<?php echo $hash;?>" />
            <input type="hidden" name="service_provider" value="<?php echo $service_provider; ?>" />


            <div class="buttons">
                <div ><input type="submit" value="{{ __lang('pay-now') }}" class="btn btn-primary btn-lg" /></div>
            </div>
        </form>

    </div>

@endsection
