<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\PaymentMethod;
use App\Template;
use App\V2\Model\CountryTable;
use Illuminate\Http\Request;
use Laminas\Form\Element\Select;

class PaymentGatewayController extends Controller
{
    use HelperTrait;


    public function index(){
        //get list of all templates in templates folder
        $methods = getDirectoryContents(PAYMENT_PATH);
        $select = new Select('currency');
        $select->setAttribute('id','currencyselect')
            ->setAttribute('class','form-control')
            ->setAttribute('data-sort','currency');
        $select->setEmptyOption(__lang('Select Currency'));


        $options = [];
        $options['ANY'] = __lang('Any Currency');
        $countryTable = new CountryTable();
        $rowset = $countryTable->getRecords();
        foreach($rowset as $row)
        {
            $options[$row->currency_code] = $row->currency_name;
        }
        $select->setValueOptions($options);
        return view('admin.payment-gateway.index',compact('methods','select'));
    }

    public function install($method){

        //first check if this template exists yet
        $paymentMethod = PaymentMethod::where('directory',$method)->first();
        if(!$paymentMethod){
            $info = paymentInfo($method);
            $paymentMethod = PaymentMethod::create([
                'name'=>__($info['name']),
                'enabled'=>1,
                'directory'=>$method,
                'supported_currencies'=>$info['currencies'],
                'label'=>__($info['name']),
                'is_global'=>0,
            ]);

        }
        else{

            $paymentMethod->enabled = 1;
            $paymentMethod->save();
        }

        return back()->with('flash_message',__('default.installed'));

    }

    public function uninstall(PaymentMethod $paymentMethod){
        $paymentMethod->enabled = 0;
        $paymentMethod->save();
        return back()->with('flash_message',__('default.uninstalled'));
    }

    public function edit(PaymentMethod $paymentMethod){
            $settings = sunserialize($paymentMethod->settings);
            if (!is_array($settings)){
                $settings=[];
            }

            $form = 'payment.'.$paymentMethod->directory.'.views.setup';
            return view('admin.payment-gateway.edit',compact('settings','form','paymentMethod'));
    }

    public function save(Request $request,PaymentMethod $paymentMethod){
        $this->validate($request,[
            'label'=>'required'
        ]);

        $data = $request->all();

        unset($data['_token']);

        $paymentMethod->fill($data);
        $paymentMethod->settings = serialize($data);
        $paymentMethod->save();
        return redirect()->route('admin.payment-gateways')->with('flash_message',__('default.changes-saved'));

    }


}
