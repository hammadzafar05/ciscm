<?php



function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();
  $transaction = $cart->getTransaction();


  return view("payment.{$code}.views.pay",compact('cart','method','invoice','code','transaction'));
}

function traineasy_callback(){
    $cart = getCart();
    $method = $cart->getPaymentMethod();
    $code = $method->directory;
    $invoice= $cart->getInvoiceObject();

    $transactionState = 'failure';
    try {
        $message = '';

        if(!empty($_GET['PayUReference'])) {
            //Creating get transaction soap data array
            $getTransactionData = array();
            $getTransactionData['AdditionalInformation']['payUReference'] = $_GET['PayUReference'];
            $config = array();
            $config['safe_key'] = paymentOption($code,'safe_key');
            $config['api_username'] = paymentOption($code,'api_username');
            $config['api_password'] = paymentOption($code,'api_password');
            $config['logEnable'] = paymentOption($code,'debug')==1;
            $config['extended_debug'] = paymentOption($code,'debug')==1;
            if(paymentOption($code,'transaction_mode') == 'production') {
                $config['production'] = true;
            }
            require_once 'gateways/payment/payu/lib/classes/PayUEasyPlus.php';
            $payUEasyPlus = new \PayUEasyPlus($config);
            $response = $payUEasyPlus->doGetTransaction($getTransactionData);

            //var_dump();
            //exit;
            $message = $response['soap_response']['displayMessage'];

            //Checking the response from the SOAP call to see if successfull
            if(isset($response['soap_response']['successful'])
                && $response['soap_response']['successful'])
            {
                if(isset($response['soap_response']['transactionType'])
                    && $response['soap_response']['transactionType'] == strtoupper(paymentOption($code,'transaction_type')))
                {
                   // $MerchantReferenceCheck = $this->session->data['order_id'];
                    $invoiceId = $invoice->id;
                    $MerchantReferenceCallBack = $response['soap_response']['merchantReference'];

                    $transaction= \App\InvoiceTransaction::find($MerchantReferenceCallBack);
                    if($transaction->invoice_id==$invoiceId){
                        $gatewayReference = $response['soap_response']['paymentMethodsUsed']['gatewayReference'];
                        $transactionState = 'paymentSuccessfull';
                    }
                    else{
                        $message = __lang('Invalid payment');
                    }

                }
            } else {
                $message = $response['soap_response']['displayMessage'];
            }
        }
    } catch(\Exception $e) {
        $message = $e->getMessage();
    }

    //Now doing db updates for the orders
    if($transactionState == 'paymentSuccessfull')
    {
        $message = '---Payment Successful---'."\r\n";
        $message .= 'Order ID: ' . $invoice->id . "\r\n";
        $message .= 'PayU Reference: ' . request()->get('PayUReference') . "\r\n";
        foreach ($response['soap_response']['paymentMethodsUsed'] as $key => $value) {
            $message .= ucwords($key) . ': ' . $value . "\r\n";
        }

       flashMessage($message);
        $total = $cart->approve($invoice->user_id);
        $message = __lang('enroll-success-msg',['total'=>$total]);
        flashMessage($message);
        return redirect()->route('student.student.mysessions');


    } else if($transactionState == "failure") {

        $message = __lang("payment-failed-reason") . $message;
       flashMessage($message);
        return redirect()->route('cart');
    }


}

function traineasy_ipn(){

    try{

        $ipnData = file_get_contents('php://input');
        $xml = @simplexml_load_string($ipnData);

        if(false === $xml)
        {

            exit('False xml');
        }

        $ipn = traineasy_parseXMLToArray($xml);

        if(false === $ipn)
        {
            exit('False ipn');
        }


        if (!isset($ipn['MerchantReference'])) {
            exit('False merchant reference');
        }

        $orderid = $ipn['MerchantReference'];
        $invoiceTransaction = \App\InvoiceTransaction::findOrFail($orderid);
        $invoice = $invoiceTransaction->invoice;
        $cart = unserialize($invoice->cart);
        $cart->setInvoice($invoice->id);

        // $order_info = $this->model_checkout_order->getOrder($orderid);
        $transaction = $invoiceTransaction;

        $payUReference = intval($ipn['PayUReference']);
        $txn_type = $ipn['TransactionType'];
        $payment_amount = (float)$ipn['PaymentMethodsUsed']['AmountInCents'] / 100;
        $payment_currency = $ipn['Basket']['CurrencyCode'];
        $payment_status = $ipn['TransactionState'];
        $hash = $ipn['IpnExtraInfo']['ResponseHash'];

        if($transaction) {
            $order_id = $transaction->id;
            $ipnNote = '-----PAYU IPN RECIEVED---' . "\r\n";
            $ipnNote .= 'PayU Reference: ' . $payUReference . "\r\n";
            switch ($payment_status) {
                case 'SUCCESSFUL':
                    if (abs($payment_amount - $transaction->amount) > 0.01) {
                        $ipnNote .= 'Payment did not equal the order total. ';
                        exit();
                    }
                    $transaction->status = 's';
                    $transaction->save();
                    $cart->approve($invoice->user_id);


                    break;

                case 'EXPIRED':
                    $transaction->status = 'f';
                    $transaction->save();
                    break;

                case 'FAILED':
                    $transaction->status = 'f';
                    $transaction->save();
                    break;

                case 'AWAITING_PAYMENT':
                    //$this->model_checkout_order->update($order_id, 1, $ipnNote . 'Awating Payment confirmation for EFT PRO at PayU: ' . $ipnData['resultMessage']);
                    break;

                case 'PROCESSING':
                    //$this->model_checkout_order->update($order_id, 2, $ipnNote . 'A payment has been created but not finalized.');
                    break;

                case 'TIMEOUT':
                    $transaction->status = 'f';
                    $transaction->save();
                    break;
            }
        } else {
            $transaction->status = 'f';
            $transaction->save();
        }

        header("HTTP/1.1 200 Ok");
        exit();

    }
    catch(\Exception $ex){
        exit();
    }

}

function traineasy_send(){
    $tid = request()->get('tid');
    $transaction = \App\InvoiceTransaction::findOrFail($tid);
    $invoice = $transaction->invoice;
    $cart = unserialize($invoice->cart);
    $cart->setInvoice($transaction->id);
    $setTransactionData = array();
    $code = 'payu';

    $setTransactionData['TransactionType'] = strtoupper(paymentOption($code,'transaction_type'));
    $data = [];
    // Creating Basket Array
    $basket = array();
    $basket['amountInCents'] = $invoice->amount*100;
    if (strpos($basket['amountInCents'],'.') !== false) {
        list($basket['amountInCents'],$tempVar) = explode(".", $basket['amountInCents'], 2);
        $basket['amountInCents'] = $basket['amountInCents']+1;
    }

    $basket['description'] = __lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');
    $basket['currencyCode'] = paymentOption($code,'billing_currency');
    $setTransactionData = array_merge($setTransactionData, array('Basket' => $basket));
    $basket = null;
    unset($basket);

    $user = $invoice->user;
    // Creating Customer Array
    $customer = array();
    $customer['firstName'] = $user->name;
    $customer['lastName'] = $user->last_name;
    $customer['mobile'] = $user->student->mobile_number;
    $customer['email'] = $user->email;
    $customer['ip'] = getClientIp();
    $setTransactionDataArray = array_merge($setTransactionData, array('Customer' => $customer));
    $customer = null;
    unset($customer);

    //Creating Additional Information Array
    $additionalInformation = array();

    $paymentMethods ='';

    $paymentMethodList = paymentOption($code,'payment_methods');
    $methodList = [];
    foreach($paymentMethodList as $method){
        $methodList[$method] = $method;
    }

    if(!empty($methodList['payu_easyplus_method_credit_card']))
    {
        $paymentMethods.= 'CREDITCARD,';
    }
    if(!empty($methodList['payu_easyplus_method_discovery_miles']))
    {
        $paymentMethods.= 'DISCOVERYMILES,';
    }
    if(!empty($methodList['payu_easyplus_method_ebucks']))
    {
        $paymentMethods.= 'EBUCKS,';
    }
    if(!empty($methodList['payu_easyplus_method_eft']))
    {
        $paymentMethods.= 'EFT,';
    }
    if(!empty($methodList['payu_easyplus_method_masterpass']))
    {
        $paymentMethods.= 'MASTERPASS,';
    }
    if(!empty($methodList['payu_easyplus_method_rcs']))
    {
        $paymentMethods.= 'RCS,';
    }
    if(!empty($methodList['payu_easyplus_method_eft_pro']))
    {
        $paymentMethods.= 'EFT_PRO,';
    }
    if(!empty($methodList['payu_easyplus_method_creditcard_vco']))
    {
        $paymentMethods.= 'CREDITCARD_VCO,';
    }
    if(!empty($methodList['payu_easyplus_method_mobicred']))
    {
        $paymentMethods.= 'MOBICRED,';
    }

    $additionalInformation['supportedPaymentMethods'] = $paymentMethods;
    $additionalInformation['cancelUrl'] = route('cart');
    $additionalInformation['notificationUrl'] = route('cart.ipn',['code'=>$code,'tid'=>$transaction->id]);
    $additionalInformation['returnUrl'] = route('cart.callback',['code'=>$code,'tid'=>$transaction->id]);
    $additionalInformation['merchantReference'] = $transaction->id;
    $setTransactionData = array_merge($setTransactionData, array('AdditionalInformation' => $additionalInformation));
    $additionalInformation = null;
    unset($additionalInformation);

    //Creating a config array for RPP instantiation
    $config = array();
    $config['safe_key'] = paymentOption($code,'safe_key'); ;
    $config['api_username'] = paymentOption($code,'api_username');
    $config['api_password'] = paymentOption($code,'api_password');

    $config['logEnable'] = true;
    $config['extended_debug'] = true;

    if(strtolower(paymentOption($code,'transaction_mode')) == 'production') {
        $config['production'] = true;
        $config['logEnable'] = false;
        $config['extended_debug'] = false;
    }

    $json['error'] = 'Unable to contact PayU service. Please contact merchant.';
    $message = '';
    try{
        require_once 'gateways/payment/payu/lib/classes/PayUEasyPlus.php';
        $payUEasyPlus = new \PayUEasyPlus($config);

        $setTransactionResponse = $payUEasyPlus->doSetTransaction($setTransactionData);

        if(isset($setTransactionResponse['payu_easyplus_url'])) {
            $json['redirect'] = $setTransactionResponse['payu_easyplus_url'];
            $message = 'Redirected to PayU for payment, ';
            $message .= 'PayU Reference: ' . $setTransactionResponse['soap_response']['payUReference'];

        } else {

      //      flashMessage($json['error']);
           exit(json_encode($json));

        }
    } catch(\Exception $e) {
        $json['error'] = $e->getMessage().$e->getTraceAsString();
    }

    if(isset($json['redirect'])) {
        unset($json['error']);
    }

    exit(json_encode($json));

}


