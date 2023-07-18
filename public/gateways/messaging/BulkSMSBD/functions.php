<?php
/**
 * @string  $message
 * @param   $recipients
 */
function traineasy_send($message,$recipients){
    $code = 'BulkSMSBD';
    $username  = messagingOption($code,'username');
    $password= messagingOption($code,'password');
   
    
    $numbers = [];

    if(is_array($recipients)){
        $numbers = $recipients;
    }
    else{
        $numbers[] = $recipients;
    }
    $count = 0;
    $messages=[];

    /*foreach($numbers as $value){
        $messages[]= [
	        'username'=>$username,
	        'password'=>$password,
	        'number'=>$value,
	        'message'=>$message
        ];
    }*/
	$mobiles = implode(',', $numbers);
 
	
	$url = "http://66.45.237.70/api.php";
	$data= array(
		'username'=>$username,
		'password'=>$password,
		'number'=>"$mobiles",
		'message'=>"$message"
	);
	
	$ch = curl_init(); // Initialize cURL
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$smsresult = curl_exec($ch);
	$p = explode("|",$smsresult);
	$sendstatus = $p[0];

	
	if ($sendstatus == '1000'){
		$result = 'Invalid user or Password';
	}elseif ($sendstatus == '1002'){
		$result = 'Empty Number';
	}elseif ($sendstatus == '1003'){
		$result = 'Invalid message or empty message';
	}elseif ($sendstatus == '1004'){
		$result = 'Invalid number';
	}elseif ($sendstatus == '1005'){
		$result = 'All Number is Invalid';
	}elseif ($sendstatus == '1006'){
		$result = 'Insufficient Balance';
	}elseif ($sendstatus == '1009'){
		$result = 'Inactive Account';
	}elseif ($sendstatus == '1010'){
		$result = 'Max number limit exceeded';
	}elseif ($sendstatus == '1101'){
		$result = 'Message sent successfully';
	}else{
		$result = 'Unknown error occurred';
	}
	
	return $result;
	
    /*$result = send_message( json_encode($messages), 'http://66.45.237.70/api.php', $username, $password );

    if ($result['http_status'] != 201) {
        return "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
    } else {
        return  $result['server_response'];
        // Use json_decode($result['server_response']) to work with the response further
    }*/

}

function send_message ( $post_body, $url, $username, $password) {
    $ch = curl_init( );
    $headers = array(
        'Content-Type:application/json',
        'Authorization:Basic '. base64_encode("$username:$password")
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
    // Allow cUrl functions 20 seconds to execute
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
    // Wait 10 seconds while trying to connect
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    $output = array();
    $output['server_response'] = curl_exec( $ch );
    $curl_info = curl_getinfo( $ch );
    $output['http_status'] = $curl_info[ 'http_code' ];
    $output['error'] = curl_error($ch);
    curl_close( $ch );
    return $output;
}
