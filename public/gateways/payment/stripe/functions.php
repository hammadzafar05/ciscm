<?php

use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $user = \Illuminate\Support\Facades\Auth::user();
  //  setBillingDefaults($user);
  $description = __lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');
  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','user','description'));
}

function traineasy_callback(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();
    $user = \Illuminate\Support\Facades\Auth::user();

    if(!request()->isMethod('post'))
    {
        return redirect()->route('cart');
    }

    $token  = request('stripeToken');

    Stripe::setApiKey(paymentOption($code,'secret_key'));

    try{

        $customer = Customer::create([
            'email'=>$user->email,
            'source'=>$token
        ]);

        $charge = Charge::create(array(
            'customer' => $customer->id,
            'amount'   => ($invoice->amount * 100),
            'currency' => strtolower($invoice->currency->country->currency_code)
        ));

        $total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
        $message = __lang('enroll-success-msg',['total'=>$total]);
        flashMessage($message);
        return redirect()->route('student.student.mysessions');

    }
    catch(\Exception $ex){


        flashMessage(__lang('payment-unsuccessful').$ex->getMessage());

        return redirect()->route('cart');
    }


}

function traineasy_ipn(){

}



