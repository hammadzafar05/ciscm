<?php
/**
 * @string  $message
 * @param   $recipients
 */
function traineasy_send($message,$recipients){
    $code = 'twilio';
    $twilioNumber  = messagingOption($code,'twilio_number');
    $accountSid  = messagingOption($code,'account_sid');
    $authToken  = messagingOption($code,'auth_token');

    $numbers = [];

    if(is_array($recipients)){
        $numbers = $recipients;
    }
    else{
        $numbers[] = $recipients;
    }
    $count = 0;
    foreach($numbers as $key=>$value){
        $number = str_ireplace('+','',$value);

        try{
            $client = new \Twilio\Rest\Client($accountSid, $authToken);

            $client->messages->create(
            // Where to send a text message (your cell phone?)
                $number,
                array(
                    'from' => $twilioNumber,
                    'body' =>$message
                )
            );

            $count++;
        }catch (\Exception $ex){
            return  $ex->getMessage();
        }

    }

    return __('message-sent-total',['total'=>$count]);

}
