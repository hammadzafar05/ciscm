<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\PaymentMethod;
use App\V2\Model\SessionCategoryTable;
use App\V2\Model\SessionTable;
use Illuminate\Http\Request;
use Laminas\InputFilter\InputFilter;

class PaymentController extends Controller
{

    use HelperTrait;
    public function index(Request $request)
    {
        $table = new PaymentMethodTable();
        $countryTable = new CountryTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);

        $select = new Select('currency');
        $select->setAttribute('id','currencyselect')
            ->setAttribute('class','form-control')
            ->setAttribute('data-sort','currency');
        $select->setEmptyOption(__lang('Select Currency'));

        $options = [];
        $options['ANY'] = __lang('Any Currency');
        $rowset = $countryTable->getRecords();
        foreach($rowset as $row)
        {
            $options[$row->currency_code] = $row->currency_name;
        }
        $select->setValueOptions($options);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Payment Methods'),
            'select'=>$select
        ));

    }

    public function edit(Request $request){
        $paymentMethodTable = new PaymentMethodTable();
        $fieldsTable = new PaymentMethodFieldTable();
        $id = request()->get('id');
        $pmRow = $paymentMethodTable->getRecord($id);
        $form = new PaymentMethodForm(null,$this->getServiceLocator(),$id);
        $output = [];
        $fields = $fieldsTable->getRecordsForMethod($id);
        $fields->buffer();

        if(request()->isMethod('post')){
            $data = request()->all();
            $paymentMethodTable->update(['status'=>$data['status'],'sort_order'=>$data['sort_order'],'method_label'=>$data['method_label'],'is_global'=>$data['is_global']],$id);

            foreach($fields as $row){

                $fieldsTable->updateValue($data[$row->key],$row->key,$id);

            }

            session()->flash('flash_message',__lang('pm-settings-saved',['paymentMethod'=>$pmRow->payment_method]));
            return adminRedirect(['controller'=>'payment','action'=>'index']);

        }
        else{
            $formData = [];
            foreach($fields as $row){
                $formData[$row->key] = $row->value;

            }
            $formData['status'] = $pmRow->status;
            $formData['sort_order']= $pmRow->sort_order;
            $formData['method_label'] = $pmRow->method_label;
            $formData['is_global'] = $pmRow->is_global;
            $form->setData($formData);
        }
        $output['fields'] = $fields;
        $output['form'] = $form;
        $output['pageTitle'] = __lang('Edit Payment Method').': '.$pmRow->payment_method;
        $output['id'] = $id;

        return $output;

    }

    public function currencies(Request $request,$id){
        $currencies = Currency::get();
        $paymentMethod = PaymentMethod::findorFail($id);
        if(request()->isMethod('post')){

            $currency = $request->post('currency');

            if(!$paymentMethod->currencies()->where('id',$currency)->first())
            {
                $paymentMethod->currencies()->attach($currency);

            }
        }

        //get list of payment methods
        $rowset = $paymentMethod->currencies;
        return view('admin.payment.currencies',compact('currencies','rowset','paymentMethod'));
    }

    public function deletecurrency(Request $request,PaymentMethod $paymentMethod,$id){


        $paymentMethod->currencies()->detach($id);

        return app(PaymentController::class)->currencies($request,$paymentMethod->id);
/*
        $response = $this->forward()->dispatch('Admin\Controller\Payment',['action'=>'currencies','id'=>$method]);
        return $response;*/

    }

    public function coupons(Request $request){

        $this->data['coupons'] = Coupon::orderBy('id','desc')->paginate(20);
        $this->data['pageTitle'] = __lang('Coupons');
        return view('admin.payment.coupons',$this->data);
    }

    public function addcoupon(Request $request){

        $form = $this->couponForm();

        if(request()->isMethod('post')){

            $formData = request()->all();
            $form->setData($formData);
            if($form->isValid()){
                $data = $form->getData();
                $data = $this->setNull($data);
                $data['expires_on'] = getDateString($data['expires']);
                $data['date_start'] = getDateString($data['date_start']);
                $data['code'] = trim(strtolower(safeUrl($data['code'])));
                $data['discount'] = $this->checkDiscount($data['discount'],$data['type']);
                $coupon = Coupon::create($data);
                $coupon->courses()->attach($request->sessions);
                $coupon->courseCategories()->attach($request->categories);

                session()->flash('flash_message',__lang('Coupon created'));
                return adminRedirect(['controller'=>'payment','action'=>'coupons']);
            }
            else{
                $this->data['flash_message'] = $this->getFormErrors($form);
            }

        }

        $this->data['pageTitle'] = __lang('Add Coupon');
        $this->data['form'] = $form;
        return view('admin.payment.addcoupon',$this->data);

    }

    public function editcoupon(Request $request,$id){
        $form = $this->couponForm();
        $coupon = Coupon::find($id);
        if(request()->isMethod('post')){

            $formData = request()->all();
            $form->setData($formData);
            if($form->isValid()){
                $data = $form->getData();
                $data = $this->setNull($data);

                $data['expires_on'] = getDateString($data['expires']);
                $data['date_start'] = getDateString($data['date_start']);
                $data['code'] = trim(strtolower(safeUrl($data['code'])));
                $data['discount'] = $this->checkDiscount($data['discount'],$data['type']);

                $coupon->fill($data);
                $coupon->save();
                $coupon->courses()->attach($request->sessions);
                $coupon->courseCategories()->sync($request->categories);
                session()->flash('flash_message',__lang('Coupon saved'));
                return adminRedirect(['controller'=>'payment','action'=>'coupons']);
            }
            else{
                $this->data['message'] = $this->getFormErrors($form);
            }

        }
        else{

            $data = $coupon->toArray();
            $data['expires'] = showDate('Y-m-d',$coupon->expires_on);
            $data['date_start'] = showDate('Y-m-d',$coupon->date_start);

            foreach($coupon->courseCategories as $groupRow){
                $data['categories[]'][] = $groupRow->id;
            }

            foreach($coupon->courses as $groupRow){
                $data['sessions[]'][] = $groupRow->id;
            }


            $form->setData($data);
        }

        $this->data['pageTitle'] = __lang('Edit Coupon');
        $this->data['form'] = $form;
        $viewModel = viewModel('admin',__CLASS__,'addcoupon',$this->data);

        return $viewModel;
    }

    public function setNull($data){
        foreach($data as $key=>$value){
            $data[$key] = empty($value)? null:$value;
        }
        return $data;
    }

    private function saveCouponData(Coupon $coupon,$data){

        $coupon->couponCategories()->delete();
        $coupon->couponSessions()->delete();

        foreach($data['sessions'] as $key=>$value){

            CouponSession::create([
                'coupon_id'=>$coupon->coupon_id,
                'session_id'=>intval($value[0])
            ]);
        }

        foreach($data['categories'] as $key=>$value){

            CouponCategory::create([
                'coupon_id'=>$coupon->coupon_id,
                'session_category_id'=>intval($value[0])
            ]);
        }

    }

    private function checkDiscount($discount,$type){
        if($discount> 100 && $type=='P'){
            $discount = 100;
        }
        elseif($discount < 1){
            $discount =1;
        }

        return $discount;
    }

    public function deletecoupon(Request $request,$id){

        $coupon = Coupon::find($id);
        $coupon->delete();
        session()->flash('flash_message',__lang('Coupon deleted'));
        return back();
    }

    private function couponForm(){
        $form= new BaseForm();
        $form->createText('code','Coupon Code',true,null,null,__lang('code-not-case'));
        $form->createText('discount','Discount',true,'form-control digit',null,__lang('Numbers only'));
        $form->createText('expires','End Date',true,'form-control date');

        $form->createSelect('enabled','Status',[1=>__lang('Enabled'),0=>__lang('Disabled')],true,false);
        $form->createText('name','Coupon Name',true);
        $form->createSelect('type','Type',[
            'P'=>__lang('Percentage'),
            'F'=>__lang('Fixed Amount')
        ],true,false);
        $form->createText('total','Total Amount',false,'form-control digit');
        $form->createText('date_start','Start Date',true,'form-control date');
        $form->createText('uses_total','Uses Per Coupon',false,'form-control number');
        $form->createText('uses_customer','Uses Per Customer',false,'form-control number');

        $options = [];
        $sessionCategoryTable = new SessionCategoryTable();
        $rowset = $sessionCategoryTable->getLimitedRecords(5000);
        foreach($rowset as $row){
            $options[$row->id]=$row->name;
        }

        $form->createSelect('categories[]','Course Categories',$options,false);
        $form->get('categories[]')->setAttribute('multiple','multiple');
        $form->get('categories[]')->setAttribute('class','form-control select2');


        $options = [];
        $sessionTable = new SessionTable();
        $rowset = $sessionTable->getLimitedRecords(5000);
        foreach($rowset as $row){
            $options[$row->id]=$row->name;
        }

        $form->createSelect('sessions[]','Courses',$options,false);
        $form->get('sessions[]')->setAttribute('multiple','multiple');
        $form->get('sessions[]')->setAttribute('class','form-control select2');




        $form->setInputFilter($this->couponFilter());
        return $form;
    }

    private function couponFilter(){
        $filter = new InputFilter();
        $filter->add([
            'name'=>'code',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]

            ]
        ]);
        $filter->add([
            'name'=>'discount',
            'required'=>true,
            'validators'=>[
                ['name'=>'NotEmpty']
            ]
        ]);

        $filter->add([
            'name'=>'expires',
            'required'=>true,
            'validators'=>[
                ['name'=>'NotEmpty']
            ]
        ]);

        $filter->add([
            'name'=>'enabled',
            'required'=>false,
        ]);



        $filter->add([
            'name'=>'name',
            'required'=>true,
            'validators'=>[
                ['name'=>'NotEmpty']
            ]
        ]);

        $filter->add([
            'name'=>'type',
            'required'=>true,
            'validators'=>[
                ['name'=>'NotEmpty']
            ]
        ]);

        $filter->add([
            'name'=>'total',
            'required'=>false
        ]);

        $filter->add([
            'name'=>'date_start',
            'required'=>true,
            'validators'=>[
                ['name'=>'NotEmpty']
            ]
        ]);


        $filter->add([
            'name'=>'uses_total',
            'required'=>false
        ]);

        $filter->add([
            'name'=>'uses_customer',
            'required'=>false
        ]);

        $filter->add([
            'name'=>'categories[]',
            'required'=>false
        ]);

        $filter->add([
            'name'=>'sessions[]',
            'required'=>false
        ]);




        return $filter;
    }
}
