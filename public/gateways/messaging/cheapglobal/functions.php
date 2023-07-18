<?php
/**
 * @string  $message
 * @param   $recipients
 */
function traineasy_send($message,$recipients){
    $code = 'cheapglobal';
    $subaccount  = messagingOption($code,'sub_account');
    $password = messagingOption($code,'password');
    $senderName = messagingOption($code,'sender_name');
    $numbers = [];

    if(is_array($recipients)){
        $numbers = $recipients;
    }
    else{
        $numbers[] = $recipients;
    }
    $numbers = implode(',',$numbers);
    $count = 0;

    $post_data=array(
        'sub_account'=>$subaccount,
        'sub_account_pass'=>$password,
        'action'=>'send_sms',
        'sender_id'=>$senderName,
        'recipients'=>$numbers,
        'message'=>$message
    );

    $api_url='http://cheapglobalsms.com/api_v1';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($response_code != 200)$response=curl_error($ch);
    curl_close($ch);

    if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
    else
    {
        $json=@json_decode($response,true);

        if($json===null)$msg="INVALID RESPONSE: $response";
        elseif(!empty($json['error']))$msg=$json['error'];
        else
        {
            $msg="SMS sent to ".$json['total']." recipient(s).";
            $sms_batch_id=$json['batch_id'];
        }
    }

    return $msg;

}
