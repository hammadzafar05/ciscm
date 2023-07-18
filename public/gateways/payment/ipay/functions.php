<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();

  $transaction = $invoice->invoiceTransactions()->create([
      'amount'=>$invoice->amount,
      'status'=>'p'
  ]);

  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','transaction'));
}

function traineasy_callback(){

}

function traineasy_ipn(){


    $tid = trim(request()->get('invoice_id'));
    $invoiceTransaction = \App\InvoiceTransaction::findOrFail($tid);

    $invoice = $invoiceTransaction->invoice;
    $merchantKey = paymentOption('ipay','key');

    $url = 'https://community.ipaygh.com/v1/gateway/status_chk?invoice_id='.$tid.'&merchant_key='.$merchantKey;
    $status = file_get_contents($url);

    $statusArray = explode('~',$status);

    if(trim($statusArray[1])=='paid'){
        $invoiceTransaction->status = 's';
        $invoiceTransaction->save();

        $cart = unserialize($invoice->cart);
        $cart->setInvoice($invoice->id);
        $cart->approve($invoice->user_id);
    }
    exit('');
}

