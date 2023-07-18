<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\SmsGateway;
use App\V2\Model\SettingTable;
use App\V2\Model\SmsGatewayTable;
use Illuminate\Http\Request;

class SmsgatewayController extends Controller
{

    use HelperTrait;

    public function index(Request $request)
    {
        //get gateways
        $gateways = getDirectoryContents(MESSAGING_PATH);
        $table = new SmsGatewayTable();
        $settingsTable = new SettingTable();
        $form = $this->getSmsForm();

        if(request()->isMethod('post')){
            $smsEnabled = $request->post('sms_enabled');
            $settingsTable->saveSetting('sms_enabled',$smsEnabled);
            flashMessage('Settings changed');
            return adminRedirect(['controller'=>'smsgateway','action'=>'index']);

        }

        $form->setData([
            'sms_enabled'=>$this->getSetting('sms_enabled')
        ]);



        return view('admin.smsgateway.index',['pageTitle'=>__lang('SMS GATEWAYS'),'form'=>$form,'enabled'=>$this->getSetting('sms_enabled'),
            'gateways'=>$gateways
            ]);
    }


    public function edit(SmsGateway $smsGateway){
        $settings = sunserialize($smsGateway->settings);
        if (!is_array($settings)){
            $settings=[];
        }
        $form = 'messaging.'.$smsGateway->directory.'.setup';
        return view('admin.smsgateway.edit',compact('settings','form', 'smsGateway'));
    }

    public function save(Request $request,SmsGateway $smsGateway){

        if ($request->default==1){
            SmsGateway::where('id','>','0')->update([
                'default'=>0
            ]);
        }

        $data = $request->all();
        unset($data['_token']);
        $smsGateway->fill($data);
        $smsGateway->settings = serialize($data);
        $smsGateway->save();

        return redirect()->route('admin.smsgateway.index')->with('flash_message',__('default.changes-saved'));
    }


    public function customize(Request $request){

        $output = [];
        $id = request()->get('id');
        $smsGatewayTable = new SmsGatewayTable();
        $smsFieldsTable = new SmsGatewayFieldTable();



        $smsGatewayForm = new SmsGatewayForm(null,$this->getServiceLocator(),$id);
        $rowset = $smsFieldsTable->getGatewayRecords($id);
        $rowset->buffer();

        if(request()->isMethod('post'))
        {

            $formData = request()->all();
            foreach($rowset as $row){
                $smsFieldsTable->saveField($id,$row->key,trim($formData[$row->key]));
            }
            $output['flash_message']=__lang('Changes Saved!');
            flashMessage('Gateway settings saved!');
            return adminRedirect(['controller'=>'smsgateway','action'=>'index']);
            $smsGatewayForm->setData($formData);


        }
        else{
            $data = [];
            foreach($rowset as $row){
                $data[$row->key] = $row->value;
            }
            $smsGatewayForm->setData($data);

        }

        $output['options'] = $rowset;
        $output['form']=$smsGatewayForm;
        $output['pageTitle'] = __lang('Configure Gateway').': '.$smsGatewayTable->getRecord($id)->gateway_name;
        $output['table'] = $smsFieldsTable;
        $output['id'] = $id;

        return $output;


    }

    public function install($gateway){

        //first check if this template exists yet
        $smsGateway = SmsGateway::where('directory',$gateway)->first();
        if(!$smsGateway){
            $info = smsInfo($gateway);
            $smsGateway = SmsGateway::create([
                'gateway_name'=>$info['name'],
                'enabled'=>1,
                'directory'=>$gateway,
                'settings'=>serialize([])
            ]);

        }
        else{

            $smsGateway->enabled = 1;
            $smsGateway->save();
        }

        return back()->with('flash_message',__('default.installed'));

    }



    public function uninstall(Request $request,SmsGateway $smsGateway){
         $smsGateway->enabled=0;
         $smsGateway->save();
        session()->flash('flash_message',__lang('Gateway uninstalled'));
        return back();
    }



    private function getSmsForm(){
        $form = new BaseForm();
        $form->createCheckbox('sms_enabled','Enable SMS?',1);
        return $form;
    }



}
