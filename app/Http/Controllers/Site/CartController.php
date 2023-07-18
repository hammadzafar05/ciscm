<?php

namespace App\Http\Controllers\Site;

use App\Course;
use App\Currency;
use App\Helper\SchedulerHelper;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Lib\HelperTrait;
use App\PaymentMethod;
use App\PendingStudent;
use App\Student;
use App\StudentField;
use App\User;
use App\V2\Model\PaymentMethodTable;
use App\V2\Model\RegistrationFieldTable;
use App\V2\Model\StudentFieldTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CartController extends Controller
{
    use HelperTrait;
    public function index(Request $request){

      $cart = getCart();

      if ($request->isMethod('post') && !empty($request->code)){
            flashMessage($cart->applyDiscount($request->code));
      }

        $currency = currentCurrency()->id;

        $paymentMethodTable = new PaymentMethodTable();
        $paymentMethods = $paymentMethodTable->getMethodsForCurrency($currency);
        
        $currencies = Currency::get();

        return tview('site.cart.index',compact('cart','paymentMethods','currencies'));
    }

    public function currency(Request $request, Currency $currency){
        $request->session()->put('currency',$currency->id);
        return back();
    }

    public function save(Request $request){
        $code = $request->code;
        $msg = getCart()->applyDiscount($code);
        flashMessage($msg);
        return back();
    }

    /*----Cart add----*/
    public function add(Request $request,Course $course){
        if (!canEnroll($course->id)){
            return back();
        }
        getCart()->addSession($course->id);
        return redirect()->route('cart');
    }
	
	public function addGuest(Request $request,Course $course){
		/*if (!canEnroll($course->id)){
			return back();
		}
		getCart()->addSession($course->id);
		return redirect()->route('cart');*/
		$captchaUrl = captcha_src();
		$registrationFieldsTable = new RegistrationFieldTable();
		$studentFieldTable = new StudentFieldTable();
		$fields = $registrationFieldsTable->getAllFields();
		return tview('site.home.course_registration_guest',compact('captchaUrl','course','fields'));
	}
	
	public function registerGuest(Request $request,Course $course){
    	//dd($course->name);
    	//dd($request->all());
		/*if (!canEnroll($course->id)){
			return back();
		}
		getCart()->addSession($course->id);
		return redirect()->route('cart');*/
		
		$user = User::where('email','=',$request->input('email')) -> first();
		
		if($user){
			if(Auth::guest()){
				Auth::loginUsingId($user->id);
			}else{
				abort('401');
			}
		}else{
			//event(new Registered($user = app('App\Http\Controllers\Auth\RegisterController')->create($request->all())));
			$data = $request->all();
			$user= User::create([
				'name' => $request->input('name'),
				'last_name'=>$request->input('last_name'),
				'email' => $request->input('email'),
				'password' => Hash::make('123456'),
				'role_id'=>2
			]);
			
			$user->student()->create([
				'mobile_number'=>$request->input('mobile_number')
			]);
			
			$fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();
			
			$customValues = [];
			//attach custom values
			foreach($fields as $field){
				if(isset($data['field_'.$field->id]))
				{
					
					if($field->type=='file'){
						if(request()->hasFile('field_'.$field->id)){
							//generate name for file
							
							$name = $_FILES['field_'.$field->id]['name'];
							
							$extension = request()->{'field_'.$field->id}->extension();
							
							$name = str_ireplace('.'.$extension,'',$name);
							
							$name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;
							
							$path =  request()->file('field_'.$field->id)->storeAs(STUDENT_FILES,$name,'public_uploads');
							
							$file = UPLOAD_PATH.'/'.$path;
							$customValues[$field->id] = ['value'=>$file];
						}
					}
					else{
						$customValues[$field->id] = ['value'=>$request->input('field_'.$field->id)];
					}
				}
				
				
			}
			
			$user->student->studentFields()->sync($customValues);
			
			$message = __('mails.new-account',[
				'siteName'=>setting('general_site_name'),
				'email'=>$request->input('email'),
				'password'=>'123456',
				'link'=> url('/login')
			]);
			
			if (!empty(setting('regis_email_message')))
			{
				$message .= '<br/>'.setting('regis_email_message');
			}
			
			$subject = __('mails.new-account-subj',[
				'siteName'=>setting('general_site_name')
			]);
			$this->sendEmail($request->input('email'),$subject,$message);
			
			
			if (setting('regis_signup_alert')==1){
				$this->notifyAdmins(__lang('New registration'),$request->input('name').' '.$request->input('last_name').' '.__lang('just registered'));
			}
			Auth::loginUsingId($user->id);
		}
		
		if (!canEnroll($course->id)){
			return back();
		}
		getCart()->addSession($course->id);
		return redirect()->route('cart');
	}

    public function remove(Request $request,Course $course){
        getCart()->removeSession($course->id);
        flashMessage(__lang('course-removed'));
        return back();
    }

    public function removeCoupon(){
        getCart()->removeDiscount();
        return back();
    }

    public function process(Request $request){
        $cart = getCart();
        if ($cart->requiresPayment()){
            $this->validate($request,[
                'payment_method'=>'required'
            ]);
        }
        $method = $request->payment_method;

        $cart->setPaymentMethod($method);
        return redirect()->route('cart.checkout', ['emi_status'=>$request->input('emi_status'),'emi_installment'=>$request->input('emi_installment')]);
    }

    /*Main Checkout*/
    public function checkout(Request $request){
    	/*--MARUF START Checkout--*/

        $cart = getCart();
        $id = Auth::id();
        if (!$cart->requiresPayment()){
            $total = $cart->approve($id);
            flashMessage(__lang("you-enrolled",['total'=>$total]));
            return redirect()->route('student.student.mysessions');
        }

        if(!$cart->hasItems() || !$cart->getPaymentMethod())
        {
            return redirect()->route('cart');
        }

        //validate the currency of the payment method
        $currency = currentCurrency();
        $method = $cart->getPaymentMethod();
        if($method->is_global == 0 && $method->currencies()->where('id',$currency->id)->count()==0){
            return redirect()->route('cart');
        }

        $code = $method->directory;

        if(!$cart->hasInvoice()){
            //create invoice
            $invoice = Invoice::create([
                'user_id'=>$id,
                'currency_id'=>currentCurrency()->id,
                'amount'=>priceRaw($cart->getCurrentTotal()),
                'cart' => serialize($cart),
                'paid'=> 0,
                'payment_method_id'=>$method->id,
                'emi_status'=>$request->input('emi_status'),
                'emi_installment'=>$request->input('emi_installment')
            ]);
            $cart->setInvoice($invoice->id);
        }
        else{
            $invoice = Invoice::find($cart->getInvoice());
            $invoice->amount = priceRaw($cart->getCurrentTotal());
            $invoice->payment_method_id = $method->id;
            $invoice->cart = serialize($cart);
            $invoice->currency_id = currentCurrency()->id;
            $invoice->save();
        }

        //include function file
        if(!$this->setFunctions()){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('cart');
        }

        if (!function_exists('traineasy_pay')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('cart');
        }

        return traineasy_pay();

    }

    public function callback(Request $request,$code){
        $this->setFunctions($code);
        if (!function_exists('traineasy_callback')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('cart');
        }
        return traineasy_callback();
    }
	
	public function callbackForSSLCommerz(Request $request,$code){
		$this->setFunctions($code);
		if (!function_exists('traineasy_callback')){
			flashMessage(__lang('invalid-gateway'));
			return redirect()->route('cart');
		}
		return traineasy_callback($request->all());
	}

    public function ipn(Request $request,$code){
        $this->setFunctions($code);
        if (!function_exists('traineasy_ipn')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('cart');
        }
        return traineasy_ipn();
    }

    public function method(Request $request,$code,$function){
        $this->setFunctions($code);
        if (!function_exists($function)){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('cart');
        }

        return $function();

    }

    public function complete(Request $request){
        $cart = getCart();
        $cart->clear();
        return tview('site.cart.complete');
    }




    private function setFunctions($code=null){
        if (!$code){
            $cart = getCart();
            $code= $cart->getPaymentMethod()->directory;
        }

        $file = 'gateways/payment/'.$code.'/functions.php';
        if (file_exists($file)){
            require_once($file);
            return true;
        }
        else{
            return false;
        }
    }


    public function mobileClose(){
        exit('close');
    }

    public function mobileLoad(Request $request){
        $this->validate($request,[
            'token'=>'required',
            'invoice'=>'required'
        ]);

        $token = $request->token;
        $invoiceId = $request->invoice;

        $time = Carbon::now()->toDateTimeString();
        $student = Student::where('api_token',trim($token))->where('token_expires','>',$time)->first();
        if(!$student){
            exit('Invalid Token');
        }

        Auth::login($student->user);

        $invoice= Invoice::find($invoiceId);
        if(!$invoice || ($student->user->id != $invoice->user_id)){
            exit('Invalid invoice');
        }

        $cart =  unserialize($invoice->cart);
        $cart->setInvoice($invoiceId);
        $cart->store();

        session(['client' => 'mobile']);
        return redirect()->route('cart.checkout');
    }
	
	public function sms(){
		SchedulerHelper::sms();
	}
	public function email(){
		SchedulerHelper::email();
	}
}
