<?php
/**
 * @string  $message
 * @param   $recipients
 */
function traineasy_send($message,$recipients){
    $code = 'smartsms';
    $smsUsername  = messagingOption($code,'username');
    $smsPassword= messagingOption($code,'password');
    $senderName = messagingOption($code,'sender_name');
    $numbers = [];

    if(is_array($recipients)){
        $numbers = $recipients;
    }
    else{
        $numbers[] = $recipients;
    }

    $apiGetEndpoint = 'http://api.smartsmssolutions.com/smsapi.php';

    $pendingNumberlist = '';
    if(is_array($recipients)){
        foreach($recipients as $value){
            $pendingNumberlist .= $value.',';
        }

    }
    else{
        $pendingNumberlist = $recipients;
    }
    $smsArray = [
        'username'=>$smsUsername,
        'password'=>$smsPassword,
        'message'=>$message,
        'sender'=>$senderName,
        'recipient'=>$pendingNumberlist
    ];

    $data = sendsms_post($apiGetEndpoint,$smsArray);

    return $data;
}

function sendsms_post ($url, array $params) {     $params = http_build_query($params);     $ch = curl_init();           curl_setopt($ch, CURLOPT_URL,$url);     curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);     curl_setopt($ch, CURLOPT_POST, 1);     curl_setopt($ch, CURLOPT_POSTFIELDS, $params);          $output=curl_exec($ch);       curl_close($ch);     return $output;         }
