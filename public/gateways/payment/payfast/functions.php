<?php

use App\InvoiceTransaction;

function traineasy_pay(){
  $cart = getCart();
  $method = $cart->getPaymentMethod();
  $code = $method->directory;
  $invoice= $cart->getInvoiceObject();


    $id = getCart()->getInvoice();

   $data = [];
   $data['tid'] = $cart->getTransaction()->id;


    $sandbox = empty(paymentOption($code,'mode'));
    $debug = paymentOption($code,'debug')==1;
    $merchantId = paymentOption($code,'merchant_id');
    $key = paymentOption($code,'merchant_key');

    $pfHost = ($sandbox ? 'sandbox' : 'www') . '.payfast.co.za';
    //if($data['payfast_debug']){$debug = true;}else{$debug = false;}
    define( 'PF_DEBUG', $debug );

    $data['sandbox'] = $sandbox;

    $data['action'] = 'https://'.$pfHost.'/eng/process';

    if(!$sandbox || !empty($merchantId))
    {
        $merchant_id = paymentOption($code,'merchant_id');
        $merchant_key = paymentOption($code,'merchant_key');

    }
    else{
        $merchant_id = '10000100';
        $merchant_key = '46f0cd694581a';

    }

    $user = \Illuminate\Support\Facades\Auth::user();
 //   $return_url = $this->url()->fromRoute('application/default',['controller'=>'callback','action'=>'payfast','id'=>$data['tid']],['force_canonical' => true]);//$this->url->link('checkout/success','',$config->get('config_use_ssl')?'SSL':'NONSSL');
    $return_url = route('cart.complete');

    $cancel_url = route('cart');;// $this->url->link('checkout/checkout','',$config->get('config_use_ssl')?'SSL':'NONSSL');

    $notify_url = route('cart.ipn',['code'=>$code,'tid'=>$data['tid']]);;// $this->url->link('payment/payfast/callback','',$config->get('config_use_ssl')?'SSL':'NONSSL');
    $name_first = html_entity_decode($user->name, ENT_QUOTES, 'UTF-8');
    $name_last = html_entity_decode($user->last_name, ENT_QUOTES, 'UTF-8');
    $email_address = $user->email;
    $m_payment_id = $data['tid'];
    $amount = $invoice->amount;
    $item_name = __lang('Enrollment for').' '.getCart()->getTotalItems().' '.__lang('items');

    $item_description = $item_name;
    $custom_str1 = $data['tid'];


    $payArray = array(
        'merchant_id'=>$merchant_id,
        'merchant_key'=>$merchant_key,
        'return_url'=>$return_url,
        'cancel_url'=>$cancel_url,
        'notify_url'=>$notify_url,
        'name_first'=>$name_first,
        'name_last'=>$name_last,
        'email_address'=>$email_address,
        'm_payment_id'=>$m_payment_id,
        'amount'=>$amount,
        'item_name'=>html_entity_decode( $item_name ),
        'item_description'=>html_entity_decode( $item_description ),
        'custom_str1'=>$custom_str1
    );
    $secureString = '';
    foreach($payArray as $k=>$v)
    {
        $secureString .= $k.'='.urlencode(trim($v)).'&';
        $data[$k] = $v;
    }


    $passphrase = paymentOption($code,'passphrase');
    if( !empty( $passphrase ) && !$sandbox )
    {
        $secureString = $secureString.'passphrase=' . urlencode( $passphrase );
    }
    else
    {
        $secureString = substr( $secureString, 0, -1 );
    }

    $securityHash = md5($secureString);
    $data['signature'] = $securityHash;

    $data['cart']=$cart;
    $data['invoice']=$invoice;
    $data['code']=$code;
    $data['method'] = $method;

  return view("payment.{$code}.views.pay",$data);
}

function traineasy_callback(){

}

function traineasy_ipn(){
    try{


        $id = request()->get('tid');

        $sandbox = empty(paymentOption('payfast','mode'));
        $debug = paymentOption('payfast','debug')==1;

        $pfHost = ($sandbox ? 'sandbox' : 'www') . '.payfast.co.za';
        define( 'PF_DEBUG', $debug );

        require_once 'gateways/payment/payfast/payfast_common.inc';
        $pfError = false;
        $pfErrMsg = '';
        $pfDone = false;
        $pfData = array();
        $pfParamString = '';
        if (isset($_POST['custom_str1']))
        {
            $order_id = $_POST['custom_str1'];
        } else {
            $order_id = 0;
        }


        pflog( 'PayFast ITN call received' );

        //// Notify PayFast that information has been received
        if( !$pfError && !$pfDone )
        {
            header( 'HTTP/1.0 200 OK' );
            flush();
        }

        //// Get data sent by PayFast
        if( !$pfError && !$pfDone )
        {
            pflog( 'Get posted data' );

            // Posted variables from ITN
            $pfData = pfGetData();
            $pfData['item_name'] = html_entity_decode( $pfData['item_name'] );
            $pfData['item_description'] = html_entity_decode( $pfData['item_description'] );
            pflog( 'PayFast Data: '. print_r( $pfData, true ) );

            if( $pfData === false )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_BAD_ACCESS;
            }
        }

        //// Verify security signature
        if( !$pfError && !$pfDone )
        {
            pflog( 'Verify security signature' );
            $passphrase = paymentOption('payfast','passphrase');
            $pfPassphrase = $sandbox ? null : ( !empty( $passphrase ) ? $passphrase : null );
            // If signature different, log for debugging
            if( !pfValidSignature( $pfData, $pfParamString, $pfPassphrase ) )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_INVALID_SIGNATURE;
            }
        }

        //// Verify source IP (If not in debug mode)
        if( !$pfError && !$pfDone && !PF_DEBUG )
        {
            pflog( 'Verify source IP' );

            if( !pfValidIP( $_SERVER['REMOTE_ADDR'] ) )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_BAD_SOURCE_IP;
            }
        }
        //// Get internal cart
        if( !$pfError && !$pfDone )
        {
            // Get order data

            $order_info = InvoiceTransaction::find($id);

            pflog( "Purchase:\n".$order_info->id   );
        }

        //// Verify data received
        if( !$pfError )
        {
            pflog( 'Verify data received' );

            $pfValid = pfValidData( $pfHost, $pfParamString );

            if( !$pfValid )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_BAD_ACCESS;
            }
        }

        //// Check data against internal order
        if( !$pfError && !$pfDone )
        {
            pflog( 'Check data against internal order' );

            $amount = $order_info->amount;
            // Check order amount
            if( !pfAmountsEqual( $pfData['amount_gross'],$amount ) )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_AMOUNT_MISMATCH;
            }

        }

        //// Check status and update order
        if( !$pfError && !$pfDone )
        {
            pflog( 'Check status and update order' );


            $transaction_id = $pfData['pf_payment_id'];

            switch( $pfData['payment_status'] )
            {
                case 'COMPLETE':
                    pflog( '- Complete' );

                    // Update the purchase status
                    $invoice = $order_info->invoice;
                    $order_info->status = 's';
                    $order_info->save();

                    $cart = unserialize($invoice->cart);
                    $cart->setInvoice($invoice->id);
                    $cart->approve($invoice->user_id);
                    break;

                case 'FAILED':
                    pflog( '- Failed' );

                    // If payment fails, delete the purchase log
                    $message = "Payment failed. Please try again.";
                    break;

                case 'PENDING':
                    pflog( '- Pending' );
                    $message = "Payment pending.";
                    // Need to wait for "Completed" before processing
                    break;

                default:
                    // If unknown status, do nothing (safest course of action)
                    $message = "Payment failed. Please try again.";
                    break;
            }

        }
        else
        {
            pflog( "Errors:\n". print_r( $pfErrMsg, true ) );
            $message = "Payment failed. ". print_r( $pfErrMsg, true );
            echo $message;
        }

        exit();
    }
    catch(\Exception $ex){
        pflog($ex->getMessage().' | '.$ex->getTraceAsString());
        exit();
    }
}



