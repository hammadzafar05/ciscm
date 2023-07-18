<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $item_name = __lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');
  $transaction = $cart->getTransaction();





    $url = 'https://my.ipaymu.com/payment.htm';  // URL Payment iPaymu
    $params = array(   // Prepare Parameters
        'key'      => paymentOption($code,'key'), // API Key Merchant / Penjual
        'action'   => 'payment',
        'product'  => $item_name,
        'price'    => $invoice->amount, // Total Harga
        'quantity' => 1,
        'ureturn'  => route('cart.complete').'?q=return',
        'unotify'  => route('cart.ipn',['code'=>$code,'tid'=>$transaction->id]),
        'ucancel'  => route('cart'),
        'format'   => 'json' // Format: xml atau json. Default: xml
    );

    $params_string = http_build_query($params);

    //open connection
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    //execute post
    $request = curl_exec($ch);

    if ( $request === false ) {
        flashMessage('Curl Error: ' . curl_error($ch));
        return back();
    } else {

        $result = json_decode($request, true);

        if( isset($result['url']) )
            return redirect($result['url']);
        else {
            flashMessage("Error ". $result['Status'] .":". $result['Keterangan']);
            return back();
        }
    }

    //close connection
    curl_close($ch);

  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code'));
}

function traineasy_callback(){

}

function traineasy_ipn(){
    $tid = request()->get('tid');
    $code = 'ipaymu';
    $invoiceTransaction = \App\InvoiceTransaction::findOrFail($tid);

    $invoice = $invoiceTransaction->invoice;
    $key = trim(paymentOption($code,'key'));
    $transactionId = $_POST['trx_id'];

    $url = 'https://my.ipaymu.com/api/transaksi?id='.$transactionId.'&key='.$key;
    $result = file_get_contents($url);
    $xml = @simplexml_load_string($result);

    if(false === $xml)
    {

        exit('False xml');
    }
    $response=  traineasy_parseXMLToArray($xml);
    $status = $response['Status'];


    if(trim($status)=='1'){

        $invoiceTransaction->status = 's';
        $invoiceTransaction->save();

        $cart = unserialize($invoice->cart);
        $cart->setInvoice($invoice->id);
        $cart->approve($invoice->user_id);
    }

    exit('Done. successful');


}

