<?php

namespace App\Helper;


use App\User;
use App\Lib\HelperTrait;
use Illuminate\Contracts\Mail\Mailer;

class MailerFactory
{
    protected $mailer;
    protected $fromAddress = '';
    protected $fromName = 'Mini CRM';


    /**
     * MailerFactory constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;

        $this->fromAddress = getSetting("crm_email");
    }

 
    /**
     * send mailbox email
     *
     *
     * @param $mailbox
     * @param $receivers
     */
    public function sendMailboxEmail($mailbox)
    {
        try {

            foreach ($mailbox->receivers as $receiver) {

                $user = User::find($receiver->receiver_id);

                $this->mailer->send("emails.mailbox_send", ['user' => $user, 'mailbox' => $mailbox], function ($message) use ($user, $mailbox) {
	
	                $attachments = '';
	                if($mailbox->attachments->count() > 0) {
		                foreach($mailbox->attachments as $attachment) {
			                $attachments = public_path('uploads/mailbox/' . $attachment->attachment);
		                }
	                }
					
	
	                $mailbox->body = 'An email has been sent by '.$user->name.'<'.$user->email.'>.<br>Please login to your portal to read.<br><br>';
	                $this->sendEmail($user->email, $mailbox->subject, $mailbox->body, null, '', $attachments);
					
                    /*$message->from($this->fromAddress, $this->fromName)
                        ->to($user->email)->subject($mailbox->subject);

					
                    if($mailbox->attachments->count() > 0) {
                        foreach($mailbox->attachments as $attachment) {
                            $message->attach(public_path('uploads/mailbox/' . $attachment->attachment));
                        }
                    }*/

                });
            }
        } catch (\Exception $ex) {
            die("Mailer Factory error 10: " . $ex->getMessage());
        }
    }
	
	function sendEmail($recipientEmail,$subject,$message,$from=null,$cc=null,$attachments=null){
		
		$cc = extract_emails($cc);
		try{
			
			if(!empty($cc)){
				
				//generate array from cc
				$ccArray = explode(',',$cc);
				$allCC = [];
				foreach($ccArray as $key=>$value){
					$value = trim($value);
					$validator = \Illuminate\Support\Facades\Validator::make(['email'=>$value],['email'=>'email']);
					
					if(!$validator->fails()){
						$allCC[] = $value;
					}
					
				}
				
				\Illuminate\Support\Facades\Mail::to($recipientEmail)->cc($allCC)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
			}
			else{
				
				\Illuminate\Support\Facades\Mail::to($recipientEmail)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
			}
			return true;
			
			
			
		}
		catch(\Exception $ex){
			
			flashMessage(__('default.send-failed').': '.$ex->getMessage());
			return false;
		}
		
	}
}