<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $instructions = paymentOption($code,'text');
  return view("payment.{$code}.views.pay",compact('cart','instructions','method','invoice','code'));
}

function traineasy_callback(){

}

function traineasy_ipn(){

}


function traineasy_complete(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();
    $instructions = paymentOption($code,'text');
    $user = \Illuminate\Support\Facades\Auth::user();
    $subject = __lang('order-details');
    $message = $instructions.'<br>';
    $message .= __lang('amount').': '.price($cart->getCurrentTotal()).'<br/>';
    $message .= __lang('invoice-id').': '.$invoice->id;

    sendEmail($user->email,$subject,$message);
    $cart->clear();
    flashMessage(__lang('order-complete'));
    return redirect()->route('student.student.invoices');

}
