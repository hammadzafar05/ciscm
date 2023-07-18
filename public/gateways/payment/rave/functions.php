<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $country = $invoice->currency->country_id;
  $transaction = $cart->getTransaction();
  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','country','transaction'));
}

function traineasy_callback(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();
    $response = $_REQUEST['resp'];

    $responsObj= json_decode($response);

    $ref = $responsObj->tx->txRef;

    $data = array('txref' => $ref,
        'SECKEY' => paymentOption($code,'secret_key'), //secret key from pay button generated on rave dashboard
        'include_payment_entity' => 1
    );




    if(paymentOption($code,'mode')==0){
        $endPoint = 'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify';
    }
    else{
        $endPoint = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify';
    }


    // make request to endpoint using unirest.
    $headers = array('Content-Type' => 'application/json');
    $body = \Unirest\Request\Body::json($data);


    try{

        $response = \Unirest\Request::post($endPoint, $headers, $body);

        $transaction = \App\InvoiceTransaction::findOrFail($ref);

        //check the status is success
        if ($response->body->status === "success" && $response->body->data->chargecode === "00") {



            //confirm that the amount is the amount you wanted to charge
            if (floatval($response->body->data->amount) === floatval($transaction->amount)) {

                $transaction->status = 's';
                $transaction->save();
                $total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
                $message = __lang('enroll-success-msg',['total'=>$total]);
                flashMessage($message);
                return redirect()->route('student.student.mysessions');
            }
            else{

                throw new \Exception(__lang('Invalid amount received'));
            }
        }
        else{

            throw new \Exception(__lang('Payment failed!'));

        }


    }
    catch(\Exception $ex){
        flashMessage(__lang('payment-unsuccessful').$ex->getMessage());
        return redirect()->route('cart');
    }

}

function traineasy_ipn(){

}
