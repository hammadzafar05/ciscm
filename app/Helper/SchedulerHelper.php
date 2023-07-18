<?php

namespace App\Helper;

use App\Model\ScheduleEmail;
use App\Model\ScheduleSms;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use File;

class SchedulerHelper
{
    public static function sms(){
	
	    /*$date1 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:40:00');
	    $date2 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:30:00');
	
	    $result = $date1->gte($date2);
	    dd($result);*/
		
	    $numbers = ScheduleSms::where([
		    ['is_sent', 'No'],
	    ])->orderBy('id', 'ASC')->get();
	
		$sent = 0;
	    foreach ($numbers as $number){
		    $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $number->sent_time);
		    $date2 = Carbon::now()->toDateTimeString();

		    $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $date2);
		    $result = $date2->gte($date1);
			if ($result) {
				$response = sendSms($number->gateway, $number->mobile_number, $number->message);
				if ($response == 'Message sent successfully') {
					$dbData = array('is_sent' => 'Yes');
					$number->update($dbData);
					$sent++;
				}
			}
	    }
		return $sent.' message sent successfully';
    }
	
	public static function email(){
	
	    /*$date1 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:40:00');
	    $date2 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:30:00');
	
	    $result = $date1->gte($date2);
	    dd($result);*/
		
	    $emails = ScheduleEmail::where([
		    ['is_sent', 'No'],
	    ])->orderBy('id', 'ASC')->get();
		//dd($emails);
	
		$sent = 0;
	    foreach ($emails as $email){
		    $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $email->sent_time);
		    $date2 = Carbon::now()->toDateTimeString();
	
		    $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $date2);
		    $result = $date2->gte($date1);
			
			if ($result) {
				$response = sendEmail($email->email, $email->subject, $email->message, null, $email->sender_name, $email->sender_email);
				//dd($response);
				if ($response) {
					$dbData = array('is_sent' => 'Yes');
					$email->update($dbData);
					$sent++;
				}
			}
	    }
		return $sent.' message sent successfully';
    }
}
