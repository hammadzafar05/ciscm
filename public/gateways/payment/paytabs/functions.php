<?php


function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();

  $dialCode = getCountryDialCode($invoice->currency->country->iso_code_2);



    //get list of products in cart
    $cartItems = getCart()->getSessions();
    $itemList = '';
    foreach ($cartItems as $session){
        $itemList.= $session->name.',';
    }


    $user = \Illuminate\Support\Facades\Auth::user();
    setBillingDefaults($user);



  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','dialCode','itemList','user'));
}

function traineasy_send(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();
    $transaction = $cart->getTransaction();

    $profileID = paymentOption($code,'profile_id');
    $serverKey = paymentOption($code,'server_key');

    //create request
    $headers = array('Authorization'=>$serverKey,'content-type'=>'application/json');

    $user= $invoice->user;
    $sessionKey = config('session.cookie') . '=' . session()->getId();
    $user = \Illuminate\Support\Facades\Auth::user();
    $token = $user->student->api_token;
    if (empty($token)){
        $user->api_token = uniqid().uniqid();
        $user->student->save();
        $token = $user->student->api_token;
    }
    $sessionKey = 'token='.$token;
    $successUrl = route('cart.method',['code'=>$code,'function'=>'traineasy_callback']) . '?' . $sessionKey;

    if(\App\Country::find($user->billing_country_id)){
        $countryCode = \App\Country::find($user->billing_country_id)->iso_code_2;
    }else{
        $countryCode = '';
    }

    $transaction = uniqid().'-'.$invoice->id;

    $data =[
        "profile_id" => $profileID,
        'tran_type'=>'sale',
        'tran_class'=>'ecom',
        'cart_description'=>__lang('payment-for-enrollment'),
        'cart_id'=>$transaction,
        'cart_currency'=>$invoice->currency->country->currency_code,
        'cart_amount'=>$invoice->amount,
        //'callback'=>$successUrl,
        'return'=>$successUrl,
        'customer_details'=>[
            'name'=>$user->billing_firstname.' '.$user->billing_lastname,
            'email'=>$invoice->user->email,
            'street1'=>$user->billing_address_1,
            'stree2'=>$user->billing_address_2,
            'city'=>$user->billing_city,
            'state'=>$user->billing_state,
            'country'=>$countryCode,
            'ip'=>getClientIp()
        ]

    ];

    $data = json_encode($data);

    $response = \Unirest\Request::post("https://secure-global.paytabs.com/payment/request",$headers,
        $data
    );



    if(isset($response) && !empty($response->body->redirect_url)){
        request()->session()->save();
        return redirect($response->body->redirect_url);
    }
    else{
        flashMessage(__lang('payment-failed!').': '.$response->body->message);
        return back();
    }


}

function traineasy_callback(){
    $token = request()->token;
    $student = \App\Student::where('api_token',$token)->first();
    if(!$student){
        exit('invalid user');
    }
    $user = $student->user;
    \Illuminate\Support\Facades\Auth::login($user);

    $data = request()->all();

    $orderId = $data['cartId'];
    $transRef = $data['tranRef'];

    $invoiceId = substr($orderId,stripos($orderId,'-')+1);
    $invoice = \App\Invoice::findOrFail($invoiceId);
    if ($invoice->user_id != $user->id){
        exit('invalid invoice');
    }
    $cart = unserialize($invoice->cart);
    $cart->setInvoice($invoice->id);


    $method = $cart->getPaymentMethod();
    $code = $method->directory;



    $profileID = paymentOption($code,'profile_id');
    // Profile Key (ServerKey)
    $serverKey = paymentOption($code,'server_key');; // Example

    $headers = array('Authorization'=>$serverKey,'content-type'=>'application/json');

    $data =[
        "profile_id" => $profileID,
        'tran_ref'=>$transRef
    ];

    $data = json_encode($data);

    $response = \Unirest\Request::post("https://secure-global.paytabs.com/payment/query",$headers,
        $data
    );

    if($response->body->cart_amount != $invoice->amount){
        flashMessage(__lang('payment-failed!').': Invalid amount');
        return redirect()->route('cart');
    }


    if ($response->body->payment_result->response_status=='A') {
        $total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
        $message = __lang('enroll-success-msg',['total'=>$total]);
        flashMessage($message);
        return redirect()->route('student.student.mysessions');


    }else{
        flashMessage(__lang('payment-failed!').': '.request()->respMessage);
        return redirect()->route('cart');
    }



}

function traineasy_ipn(){


    $code = 'paytabs';

    $ref = request()->order_id;
    $transaction = \App\InvoiceTransaction::findOrFail($ref);
    $invoice = $transaction->invoice;
    $cart = unserialize($invoice->cart);
    $cart->setInvoice($invoice->id);

    //login user
    \Illuminate\Support\Facades\Auth::login($invoice->user);

    $endPoint = 'https://www.paytabs.com/apiv2/verify_payment_transaction';

    $data = array(
        'merchant_email' => trim(paymentOption($code,'merchant_email')),
        'secret_key' => trim(paymentOption($code,'secret_key')),
        'transaction_id'=>request()->transaction_id,
        'order_id' => request()->order_id
    );

    // make request to endpoint using unirest.
    //$headers = array('Content-Type' => 'application/json');
    $headers = [];
    $body = \Unirest\Request\Body::Form($data);

    try{

        $response = \Unirest\Request::post($endPoint, $headers, $body);

        //check the status is success
        if ($response->body->response_code === "100") {

            //confirm that the amount is the amount you wanted to charge
            if (floatval($response->body->amount) === floatval($transaction->amount)) {

                $transaction->status = 's';
                $transaction->save();

                $total = $cart->approve($invoice->user_id);
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


