<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/24/2017
 * Time: 12:28 PM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;


use App\Invoice;
use App\PaymentMethodCurrency;
use App\V2\Model\PaymentMethodFieldTable;
use App\V2\Model\PaymentMethodTable;
use App\V2\Model\SessionTable;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;

class PaymentController extends Controller {

    use HelperTrait;

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $controller->layout('layout/student');
        }, 100);
    }

    /**
     * @return array|\Zend\Http\Response
     * @throws \Exception
     * This loads the cart/payment page where the user can select
     * a payment method
     */
    public function index(Request $request)
    {
        return redirect()->route('cart');

        $output = [];
        $session= new Container('enroll');
        $id = $session->id;
        if(empty($id))
        {
            return back();
        }
        $sessionTable = new SessionTable();
        $output['row'] = $sessionTable->getRecord($id);

        $paymentMethodsTable = new PaymentMethodTable();
        $output['methods'] = $paymentMethodsTable->getInstalledMethods();
        $output['pageTitle']= __lang('Make Payment');
        return $output;
    }

    /**
     * @return mixed|\Zend\Http\Response
     * The forwards to the selected payment method
     * which was supplied via POST
     */
    public function methodold(Request $request)
    {
        $fieldsTable = new PaymentMethodFieldTable();
        $session= new Container('enroll');
        $id = $session->id;
        if(empty($id))
        {
            return back();
        }
        if(request()->isMethod('post'))
        {
            $code = $request->post('code');

            if(empty($code))
            {
                return redirect()->route('application/payment');
            }

            $viewModel = $this->forward()->dispatch('Application\Controller\Method',['action'=>$code,'id'=>$id]);
            return $viewModel;
        }
        else{
            return back();
        }

    }

    public function method(Request $request)
    {
        $cart = getCart();

        //check if cart requires payment
        if(!$cart->requiresPayment()){
            $total = $cart->approve($this->getId());
            flashMessage(__("you-enrolled",['total'=>$total]));
            return redirect()->route('application/default',['controller'=>'student','action'=>'mysessions']);
        }



        if(!$cart->hasItems() || !$cart->getPaymentMethod())
        {
            return redirect()->route('cart');
        }

        //validate the currency of the payment method
        $currency = currentCurrency();
        $method = $cart->getPaymentMethod();
        if($method->is_global == 0 && PaymentMethodCurrency::where('payment_method_id',$method->payment_method_id)->where('currency_id',$currency->currency_id)->count()==0){
            return redirect()->route('cart');
        }


            $code = $cart->getPaymentMethod()->code;

        if(!$cart->hasInvoice()){
            //create invoice
            $invoice = Invoice::create([
                'student_id'=>$this->getId(),
                'currency_id'=>currentCurrency()->currency_id,
                'created_on'=>time(),
                'amount'=>priceRaw($cart->getCurrentTotal()),
                'cart' => serialize($cart),
                'paid'=> 0,
                'payment_method_id'=>$cart->getPaymentMethod()->payment_method_id
            ]);
            $cart->setInvoice($invoice->invoice_id);
        }
        else{
            $invoice = Invoice::find($cart->getInvoice());
            $invoice->amount = priceRaw($cart->getCurrentTotal());
            $invoice->payment_method_id = $cart->getPaymentMethod()->payment_method_id;
            $invoice->cart = serialize($cart);
            $invoice->currency_id = currentCurrency()->currency_id;
            $invoice->save();
        }


            $viewModel = $this->forward()->dispatch('Application\Controller\Method',['action'=>$code]);
            return $viewModel;


    }




}
