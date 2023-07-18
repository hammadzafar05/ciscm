<?php
/**
 * @string  $message
 * @param   $recipients
 */
function traineasy_send($message,$recipients){
    $code = 'clickatell';
    $apiKey  = messagingOption($code,'key');
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
        $url =  'https://platform.clickatell.com/messages/http/send?apiKey='.urlencode($apiKey).'&to='.$number.'&content='.urlencode($message);
      try{
          $response= file_get_contents($url);
          $response = json_decode($response);
          $response = (array) $response;

          $response = arrayToStringMsg($response);
      }catch (\Exception $ex){
          $response = $ex->getMessage().$ex->getTraceAsString();
      }


        $count++;
    }

    return __lang('message-sent-total',['total'=>$count]).' '.$response;
}
