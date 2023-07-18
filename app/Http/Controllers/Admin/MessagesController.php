<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\SmsTemplate;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    use HelperTrait;

    public function emails(Request $request){

        $this->data['pageTitle'] = __lang('Email Notifications');
        $this->data['templates'] = EmailTemplate::paginate(10);

        return view('admin.messages.emails',$this->data);
    }

    public function editemail(Request $request,$id){

        $emailTemplate= EmailTemplate::find($id);

        if(request()->isMethod('post')){
            $data = request()->all();

            if(!empty($data['message']) && !empty($data['subject'])){
                $emailTemplate->message = $data['message'];
                $emailTemplate->subject = $data['subject'];
                $emailTemplate->save();
                flashMessage(__lang('Changes Saved!'));
                return redirect()->route('admin.messages.emails');
            }

        }


        $this->data['pageTitle'] = __("Edit Email").": ".__lang('e-template-name-'.$id);
        $this->data['template'] = $emailTemplate;
        return view('admin.messages.edit-email',$this->data);

    }


    public function resetemail(Request $request){
        $id = request()->get('id');
        $template = EmailTemplate::find($id);

        $template->message = $template->default;
        $template->subject = $template->default_subject;
        $template->save();
        session()->flash('flash_message',__lang('Email reset completed'));
        return back();


    }








    public function sms(Request $request){

        $this->data['pageTitle'] = __lang('SMS Notifications');
        $this->data['templates'] = SmsTemplate::paginate(10);

        return view('admin.messages.sms',$this->data);
    }

    public function editsms(Request $request,$id){

        $smsTemplate= SmsTemplate::find($id);

        if(request()->isMethod('post')){
            $data = request()->all();

            if(!empty($data['message'])){
                $smsTemplate->message = $data['message'];
                $smsTemplate->save();
                flashMessage(__lang('Changes Saved!'));
                return redirect()->route('admin.messages.sms');
            }

        }


        $this->data['pageTitle'] = __("Edit SMS").": ".__lang('s-template-name-'.$id);
        $this->data['template'] = $smsTemplate;
        return view('admin.messages.edit-sms',$this->data);

    }


    public function resetsms(Request $request){
        $id = request()->get('id');
        $template = SmsTemplate::find($id);

        $template->message = $template->default;
        $template->save();
        session()->flash('flash_message',__lang('SMS reset completed'));
        return back();


    }
}
