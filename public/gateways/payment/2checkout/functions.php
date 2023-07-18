<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $user = \Illuminate\Support\Facades\Auth::user();

  $mode = paymentOption($code,'mode');

  $action = 'https://www.2checkout.com/checkout/purchase';

  //validate billing
   setBillingDefaults($user);

$description =__lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');
  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','user','action','description','mode'));
}

function traineasy_callback(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $request = request();
    $invoiceId = $request->merchant_order_id;
    $invoice= getCart()->getInvoiceObject();
    $hashSecretWord = paymentOption($code,'secret_word');
    $hashSid = paymentOption($code,'account_number');
    $hashTotal = number_format((float)$invoice->amount, 2, '.', '');
    $hashOrder = $request->order_number;
    $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));

    if ($StringToHash != $request->key || $request->credit_card_processed != 'Y' ) {
        $result = __lang('payment-failed!');
        flashMessage($result);
        return redirect()->route('cart');

    } else {
        $total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
        $message = __lang('enroll-success-msg',['total'=>$total]);
        flashMessage($message);
        return redirect()->route('student.student.mysessions');
    }


}

function traineasy_ipn(){

}
