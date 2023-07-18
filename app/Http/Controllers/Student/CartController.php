<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/11/2018
 * Time: 1:09 PM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\Student;
use Illuminate\Http\Request;
use App\Invoice;
use App\Course;
use App\V2\Model\PaymentMethodTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller {

 use HelperTrait;


    public function index(Request $request){

        if(isMobileApp()){
            return redirect()->route('mobile-close');
        }


        $this->data['pageTitle'] = __lang('Your Cart');

        $this->data['cart'] = getCart();
        if(request()->isMethod('post')){
            $discount = $request->post('code');
            if(isset($_POST['code'])){
                $this->data['message'] = getCart()->applyDiscount($discount);

            }
         }

        $currency = currentCurrency()->currency_id;

        $paymentMethodTable = new PaymentMethodTable();
        $this->data['paymentMethods'] = $paymentMethodTable->getMethodsForCurrency($currency);
        if($this->studentIsLoggedIn()){
            $this->layout('layout/student');
        }
        $this->data['loggedIn'] = $this->studentIsLoggedIn();
        return $this->data;
    }

    public function setsession(Request $request)
    {

        $id = $this->params('id');

        //check if session requires payment

        if(!$this->canEnrollToSession($id)){
            return back();
        }

        //check if requires payment
        $row = Course::find($id);




        if( (empty($row->payment_required) || $row->amount==0 ) && (empty($row->enrollment_closes) || $row->enrollment_closes > time())  && !empty($row->session_status))
        {

            return redirect()->route('application/default',['controller'=>'student','action'=>'setsession','id'=>$id]);
        }


        $cart = getCart();
        $cart->addSession($id);
        return redirect()->route('cart');
    }

    public function remove(Request $request){
        $id = $this->params('id');
        $cart = getCart();
        $cart->removeSession($id);
        return back();
    }

    public function removecoupon(Request $request){
        getCart()->removeDiscount();
        return back();
    }

    public function checkout(Request $request){

        if(request()->isMethod('post')){

            $cart = getCart();
            $method = $request->post('payment_method');
            $cart->setPaymentMethod($method);

            //now redirect to payment page
            return redirect()->route('application/default',['controller'=>'payment','action'=>'method']);
        }
        else{
            return redirect()->route('cart');
        }

    }

    public function clear(Request $request){
        getCart()->clear();
        $session = new Container('client');
        if($session->type=='mobile'){
            return redirect()->route('mobile-close');
        }
        else{
            return redirect()->route('home');
        }

    }

}
