<?php

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();

  $id = getCart()->getInvoice();

  $data = [];
    $url = '';
    $mode = paymentOption($code,'mode');
    $key = paymentOption($code,'merchant_key');
    $salt = paymentOption($code,'salt');

    if($mode==0){
        $url = 'https://sandboxsecure.payu.in';
    }
    else{
        $url = 'https://secure.payu.in';
    }

    $user = \Illuminate\Support\Facades\Auth::user();
    $transaction = $cart->getTransaction();
    $data['action'] = $url.'/_payment';
    $data['surl'] = route('cart.ipn',['code'=>$code]);//HTTP_SERVER.'/index.php?route=payment/payu/callback';
    $data['furl'] = route('cart.method',['code'=>$code,'function'=>'traineasy_failure']);
    $data['curl'] = route('cart.method',['code'=>$code,'function'=>'traineasy_failure']);
    $key          =  trim($key);
    $amount       =  $transaction->amount;
    $productInfo  = __lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');
    $firstname    = $user->name;
    $email        = $user->email;
    $salt         = trim($salt);
    $udf5 		  = "traineasy";
    $Hash=hash('sha512', $key.'|'.$transaction->id.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt);
    $data['user_credentials'] = $key.':'.$email;
    $data['udf5'] = $udf5;
    $data['hash'] = $Hash;
    $data['key'] = $key;
    $data['student'] = $user;
    $service_provider = 'payu_paisa';
    $data['service_provider'] = $service_provider;
    $data['amount'] = $amount;
    $data['productinfo'] = $productInfo;
    $data['phone'] = $user->student->mobile_number;
    $data['lastname'] = $user->last_name;
    $data['firstname'] = $firstname;
    $data['udf5']= $udf5;
    $data['email'] = $email;

    $data['cart'] = $cart;
    $data['invoice'] = $invoice;
    $data['code'] = $code;
    $data['method'] = $method;
    $data['mode'] = $mode;
    $data['tid'] = $transaction->id;

  return view("payment.{$code}.views.pay",$data);
}

function traineasy_callback(){

    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();

    $mode = paymentOption($code,'mode');
    $key = paymentOption($code,'merchant_key');
    $salt = paymentOption($code,'salt');

    $tid = $_POST["txnid"];

    $status=$_POST["status"];
    $firstname=$_POST["firstname"];
    $amount=$_POST["amount"];
    $txnid=$_POST["txnid"];
    $posted_hash=$_POST["hash"];
    $key=$_POST["key"];
    $productInfo=$_POST["productinfo"];
    $email=$_POST["email"];
    $udf5 		  = "traineasy";


    //check if transaction is successful
    If (isset($_POST["additionalCharges"])) {
        $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnid.'|'.$key;
    } else {
        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnid.'|'.$key;
    }

    $hash = hash("sha512", $retHashSeq);

    try{

        $trow = \App\InvoiceTransaction::find($tid);

        if(!($tid))
        {
            throw new \Exception(__lang('Invalid Transaction'));
        }

        if($trow->status=='s'){
            return redirect()->route('student.student.mysessions');
        }

        if($amount  != floatval($trow->amount)) {
            throw new \Exception(__lang("invalid-amount-rec"));
        }

        if ($hash != $posted_hash)
        {
            throw new \Exception(__lang('Payment failed!'));
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
        // throw new \Exception('Error validating transaction. Please try again.');
    }

}

function traineasy_ipn(){
    $code = 'payuin';

    $cart = getCart();

    $mode = paymentOption($code,'mode');
    $key = paymentOption($code,'merchant_key');
    $salt = paymentOption($code,'salt');

    $tid = $_POST["txnid"];
    $trow = \App\InvoiceTransaction::find($tid);
    $invoice = $trow->invoice;
    \Illuminate\Support\Facades\Auth::login($invoice->user);
    $cart = unserialize($trow->invoice->cart);
    $cart->setInvoice($trow->invoice->id);

    $status=$_POST["status"];
    $firstname=$_POST["firstname"];
    $amount=$_POST["amount"];
    $txnid=$_POST["txnid"];
    $posted_hash=$_POST["hash"];
    $key=$_POST["key"];
    $productInfo=$_POST["productinfo"];
    $email=$_POST["email"];
    $udf5 		  = "traineasy";



    //check if transaction is successful
    If (isset($_POST["additionalCharges"])) {
        $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnid.'|'.$key;
    } else {
        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnid.'|'.$key;
    }

    $hash = hash("sha512", $retHashSeq);

    try{



        if(!($tid))
        {
            throw new \Exception(__lang('Invalid Transaction'));
        }

        if($trow->status=='s'){
            return redirect()->route('student.student.mysessions');
        }

        if($amount  != floatval($trow->amount)) {
            throw new \Exception(__lang("invalid-amount-rec"));
        }

        if ($hash != $posted_hash)
        {
            throw new \Exception(__lang('Payment failed!'));
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
        // throw new \Exception('Error validating transaction. Please try again.');
    }
}

function traineasy_failure(){
    $tid = $_POST["txnid"];
    $trow = \App\InvoiceTransaction::find($tid);
    $invoice = $trow->invoice;
    \Illuminate\Support\Facades\Auth::login($invoice->user);
    $cart = unserialize($trow->invoice->cart);
    $cart->setInvoice($trow->invoice->id);
    flashMessage(__lang('Payment failed!'));
    return redirect()->route('cart');
}
