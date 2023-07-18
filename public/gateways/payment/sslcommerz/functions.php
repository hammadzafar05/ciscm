<?php
/*--MARUF START--*/

use App\Invoice;
use App\Library\SslCommerz\SslCommerzNotification;
use App\User;
use App\V2\Model\CountryTable;

function traineasy_pay() {
	
	/*SslCommerz Checkout*/

	
	
	$cart = getCart();
	$method = $cart->getPaymentMethod();
	$code = $method->directory;
	
	$invoice = $cart->getInvoiceObject();
	$user = User::find($invoice->user_id);
	
	$emi_selected_inst = 0;
	$emi_status = $invoice->emi_status;
	$emi_selected_inst = $invoice->emi_installment;
	
 
	$emi_allow_only 	= 0;
	if($emi_status == 1){
		$emi_allow_only = 1;
	}
	
	$payment_mode = env("SslCommerz_mode");
	
	$post_data = array();
	
	if($payment_mode == 'sandbox'){
		$post_data['store_id'] = env("Sandbox_STORE_ID");/*Mandatory*/
		$post_data['store_passwd'] = env("Sandbox_STORE_PASSWORD");/*Mandatory*/
	}else{
   		$post_data['store_id'] = env("Live_STORE_ID");/*Mandatory*/
   		$post_data['store_passwd'] = env("Live_STORE_PASSWORD");/*Mandatory*/
	}

	$post_data['total_amount'] = number_format(floatval($invoice->amount), 2, '.', '');/*Mandatory*/
	
	$currency = \App\Currency::find($invoice->currency_id);
	$countryTable = new CountryTable();
	$currency_code = $countryTable->getRecord($currency->country_id);
 
	//$post_data['currency'] = $invoice->currency->country->currency_code;/*Mandatory*/
	$post_data['currency'] = $currency_code->currency_code;/*Mandatory*/
	$post_data['tran_id'] = $invoice->id; /*Mandatory Unique transaction ID to identify your order in both your end and SSLCommerz*/
	//$post_data['success_url'] = "http://localhost/sites/zaidi/worldacademy.uk/public/success";/*Mandatory*/
	//$post_data['fail_url'] = "http://localhost/sites/zaidi/worldacademy.uk/public/fail";/*Mandatory*/
	//$post_data['cancel_url'] = "http://localhost/sites/zaidi/worldacademy.uk/public/cancel";/*Mandatory*/

	//$post_data['success_url'] = route('success', ['code' => $code], TRUE);/*Mandatory*/
	$post_data['success_url'] = route('cart.callback', ['code' => $code], TRUE);/*Mandatory*/
	$post_data['fail_url'] = route('cart.callback', ['code' => $code], TRUE);/*Mandatory*/
	$post_data['cancel_url'] = route('cart', [], TRUE);/*Mandatory*/

	/*$transaction = $gateway->authorize(array(
		'amount' => number_format(floatval($invoice->amount), 2, '.', ''),
		'currency' => $invoice->currency->country->currency_code,
		'description' => __lang('Enrollment for') . ' ' . getCart()->getTotalItems() . ' ' . __lang('items'),
		'returnUrl' => route('cart.callback', ['code' => $code], TRUE),
		'cancelUrl' => route('cart', [], TRUE),
	
	));*/
	# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

	# EMI INFO
	$post_data['emi_option'] = $emi_status;/*Mandatory*/
	$post_data['emi_max_inst_option'] = $emi_selected_inst;/*"12";*/
	$post_data['emi_selected_inst'] = $emi_selected_inst;
	$post_data['emi_allow_only'] = $emi_allow_only;

# CUSTOMER INFORMATION
	if ($user->billing_firstname == '') {
		$billing_name = $user->name . ' ' . $user->last_name;
	} else {
		$billing_name = $user->billing_firstname . ' ' . $user->billing_lastname;
	}
	if ($user->billing_address_2 == '') {
		$billing_address = $user->billing_address_1;
	} else {
		$billing_address = $user->billing_address_1 . ' ' . $user->billing_address_2;
	}
	$post_data['cus_name'] = $billing_name;
	$post_data['cus_email'] = $user->email;
	$post_data['cus_add1'] = $user->billing_address_1;
	$post_data['cus_add2'] = $user->billing_address_2;
	$post_data['cus_city'] = $user->billing_city;
	$post_data['cus_state'] = $user->billing_state;
	$post_data['cus_postcode'] = $user->billing_zip;
	$post_data['cus_country'] = $user->billing_country_id;
	$post_data['cus_phone'] = "";
	$post_data['cus_fax'] = "";

# SHIPMENT INFORMATION
	$post_data['ship_name'] = $billing_name;
	$post_data['ship_add1'] = $user->billing_address_1;
	$post_data['ship_add2'] = $user->billing_address_2;
	$post_data['ship_city'] = $user->billing_city;
	$post_data['ship_state'] = $user->billing_state;
	$post_data['ship_postcode'] = $user->billing_zip;
	$post_data['ship_phone'] = "";
	$post_data['ship_country'] = $user->billing_country_id;

# OPTIONAL PARAMETERS
	$post_data['value_a'] = $invoice->user_id;
	/*$post_data['value_b '] = "ref002";
	$post_data['value_c'] = "ref003";
	$post_data['value_d'] = "ref004";*/

# CART PARAMETERS
	$post_data['cart'] = json_encode(array(
		array("product" => __lang('Enrollment for') . ' ' . getCart()->getTotalItems() . ' ' . __lang('items'), "amount" => number_format(floatval($invoice->amount), 2, '.', '')),
		/*	array("product"=>"DHK TO BRS AC A2","amount"=>"200.00"),
			array("product"=>"DHK TO BRS AC A3","amount"=>"200.00"),
			array("product"=>"DHK TO BRS AC A4","amount"=>"200.00")    */
	));
	/*$post_data['product_amount'] = "100";
	$post_data['vat'] = "5";
	$post_data['discount_amount'] = "5";
	$post_data['convenience_fee'] = "3";*/
	
	//dd($post_data);


# REQUEST SEND TO SSLCOMMERZ
	if($payment_mode == 'sandbox') {
		$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";
	}else{
		$direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v3/api.php";
	}
	
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $direct_api_url);
	curl_setopt($handle, CURLOPT_TIMEOUT, 30);
	curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($handle, CURLOPT_POST, 1);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
	
	$content = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	
	if ($code == 200 && !(curl_errno($handle))) {
		curl_close($handle);
		$sslcommerzResponse = $content;
	} else {
		curl_close($handle);
		//echo "FAILED TO CONNECT WITH SslCommerz API";
		return redirect()->route('cart')->with('flash_message',"FAILED TO CONNECT WITH SslCommerz API");
		exit;
	}

# PARSE THE JSON RESPONSE
	$sslcz = json_decode($sslcommerzResponse, TRUE);
	/*echo 'PARSE THE JSON RESPONSE--><br>';
	echo '<pre>';
	print_r($sslcz);
	echo '</pre>';
	exit();*/
	if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
		/*# THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
		# echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";*/
		echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
		# header("Location: ". $sslcz['GatewayPageURL']);
		exit;
	} else {
		//echo "JSON Data parsing error!";
		return redirect()->route('cart')->with('flash_message',"JSON Data parsing error!");
	}
}

function traineasy_callback() {
	
	$payment_mode = env("SslCommerz_mode");
	
	if($payment_mode == 'sandbox'){
		$store_id = env("Sandbox_STORE_ID");/*Mandatory*/
		$store_passwd = env("Sandbox_STORE_PASSWORD");/*Mandatory*/
	}else{
		$store_id = env("Live_STORE_ID");/*Mandatory*/
		$store_passwd = env("Live_STORE_PASSWORD");/*Mandatory*/
		
		$post_data['store_id'] = env("Live_STORE_ID");/*Mandatory*/
		$post_data['store_passwd'] = env("Live_STORE_PASSWORD");/*Mandatory*/
	}
	
	$val_id = urlencode($_REQUEST['val_id']);
	
	//dd($_REQUEST);

	
	if($payment_mode == 'sandbox') {
		$requested_url = ("https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");
	}else{
		$requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");
	}
	
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $requested_url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC
	
	$result = curl_exec($handle);
	
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	
	if($code == 200 && !( curl_errno($handle)))
	{
		
		# TO CONVERT AS ARRAY
		# $result = json_decode($result, true);
		# $status = $result['status'];
		
		# TO CONVERT AS OBJECT
		$result = json_decode($result);
		
		# TRANSACTION INFO
		$status = $result->status;
		$tran_date = $result->tran_date;
		$tran_id = $result->tran_id;
		$val_id = $result->val_id;
		$amount = $result->amount;
		$store_amount = $result->store_amount;
		$bank_tran_id = $result->bank_tran_id;
		$card_type = $result->card_type;
		
		# EMI INFO
		$emi_instalment = $result->emi_instalment;
		$emi_amount = $result->emi_amount;
		$emi_description = $result->emi_description;
		$emi_issuer = $result->emi_issuer;
		
		# ISSUER INFO
		$card_no = $result->card_no;
		$card_issuer = $result->card_issuer;
		$card_brand = $result->card_brand;
		$card_issuer_country = $result->card_issuer_country;
		$card_issuer_country_code = $result->card_issuer_country_code;
		
		# API AUTHENTICATION
		$APIConnect = $result->APIConnect;
		$validated_on = $result->validated_on;
		$gw_version = $result->gw_version;
	
		if ($result->status == 'VALID'){
			// Find the authorization ID
			$cart = getCart();
			$total = $cart->approve(\Illuminate\Support\Facades\Auth::id());
			
			/*--Approve Invoice Start--*/
			$invoice = Invoice::find($_REQUEST['tran_id']);
			$invoice->paid = 1;
			$invoice->save();
			$cart = unserialize($invoice->cart);
			$cart->approve($invoice->user_id);
			/*--Approve Invoice End--*/
			
			$message = __lang('enroll-success-msg', ['total' => $total]);
			flashMessage($message);
			return redirect()->route('student.student.mysessions');
		}else{
			flashMessage(__lang('Transaction failed!'));
		}
		
	} else {
		flashMessage(__lang('payment-unsuccessful') . "Failed to connect with SSLCommerz");
	}
	return redirect()->route('cart');
}



