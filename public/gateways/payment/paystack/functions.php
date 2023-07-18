<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $transaction = $cart->getTransaction();
  $user = \Illuminate\Support\Facades\Auth::user();
  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','transaction','user'));
}

function traineasy_callback(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $request = request();
    $tid = $request->post('paystack-trxref');

    //check if transaction is successful


    try{
        if(!\App\InvoiceTransaction::find($tid))
        {
            throw new \Exception(__('Invalid Transaction'));
        }

        $trow = \App\InvoiceTransaction::find($tid);
        if($trow->status=='s'){
            return redirect()->route('cart.complete');
        }

        $authorization = "Authorization: Bearer ".paymentOption($code,'secret_key');
        $endpoint = 'https://api.paystack.co/transaction/verify/'.$tid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);

        curl_close($ch);
        $obj = json_decode($result);



        $statusCode = $obj->status;

        $statusMessage = $obj->message;
        if($statusCode==1){
            $responseAmount  = $obj->data->amount/100;
        }
        else{
            $responseAmount  = 0;
        }

        if($responseAmount  != floatval($trow->amount)) {
            throw new \Exception(__('approved-amount error',['statusCode'=>$statusCode,'respAmount'=>$obj->data->amount,'transAmount'=>$trow->amount]));
        }

        if ($obj->status != 1)
        {
            throw new \Exception(__('Payment failed!'));
        }
        else{
            $trow->status = 's';
            $trow->save();

            $total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
            $message = __lang('enroll-success-msg',['total'=>$total]);
            flashMessage($message);
            return redirect()->route('student.student.mysessions');

        }

    }
    catch(\Exception $ex)
    {
        flashMessage(__lang('payment-unsuccessful').$ex->getMessage());

        return redirect()->route('cart');
    }


}

function traineasy_ipn(){

}


