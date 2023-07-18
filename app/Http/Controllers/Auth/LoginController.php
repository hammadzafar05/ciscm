<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\StudentField;
use App\User;
use Hybridauth\Hybridauth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function redirectTo()
    {
        $link = getRedirectLink();
        if (!empty($link)){
            return $link;
        }

        $user = Auth::user();
        if($user->role_id==1){
            return route('admin.dashboard');
        }
        elseif($user->role_id==2){
            return route('student.dashboard');
        }
    }



    public function showLoginForm()
    {
        //attempt to auto login if token is present
        $url = URL::previous();
        $url_components = parse_url($url);

        if(isset($url_components['query'])){

            parse_str($url_components['query'], $params);
            if(isset($params['login-token']) && User::where('login_token',$params['login-token'])->where('login_token_expires','>=',Carbon::now()->toDateString())->first()){
                $user = User::where('login_token',$params['login-token'])->first();
                Auth::login($user);
                return redirect($url);
            }

        }



        $enableRegistration = true;
        if(empty(setting('regis_enable_registration'))){
            $enableRegistration=false;
        }

        return tview('auth.login',compact('enableRegistration'));
    }

    //login via selected network. Then ask the user to select their role.
    public function social($network,Request $request){

        /*   $userProfile = new \stdClass();
           $userProfile->firstName = 'Ayokunle';
           return tview('auth.social',compact('userProfile'));*/

        //create config
        $config = array('callback'=> route('social.login',['network'=>$network]));

        if (setting('social_enable_facebook')==1) {
            $config['providers']['Facebook']=  array (
                "enabled" => true,
                "keys"    => array ( "id" => trim(setting('social_facebook_app_id')), "secret" => trim(setting('social_facebook_app_secret')) ),
                "scope"   => "email",
                "trustForwarded" => false
            );
        }

        if (setting('social_enable_google')==1) {
            $config['providers']['Google']=  array (
                "enabled" => true,
                "keys"    => array ( "id" => trim(setting('social_google_app_id')), "secret" => trim(setting('social_google_app_secret')) ),

            );
        }

        $config['debug_mode']=true;
        $config['debug_file']='hybridlog.txt';

        try{

            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new Hybridauth( $config );

            // try to authenticate the user with twitter,
            // user will be redirected to Twitter for authentication,
            // if he already did, then Hybridauth will ignore this step and return an instance of the adapter
            $authSession = $hybridauth->authenticate($network);


            // get the user profile
            $userProfile = $authSession->getUserProfile();

            //check if the user exists
            $email = $userProfile->email;
            $user = User::where('email',$email)->first();
            if($user){
                //now login user
                Auth::login($user);
                return redirect()->route('home');


            }
            elseif(empty(setting('regis_enable_registration'))){
                return redirect()->route('login')->with('flash_message',__('default.registration-disabled'));
            }

            $userClass = new \stdClass();
            $userClass->firstName = $userProfile->firstName;
            $userClass->lastName = $userProfile->lastName;
            $userClass->email = $userProfile->email;
            $userClass->phone = $userProfile->phone;

            //store user in session
            $request->session()->put('social_user',serialize($userClass));


        }catch(\Exception $ex){
            return back()->with('flash_message',$ex->getMessage());
        }


        $fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();

        return tview('auth.social',compact('userProfile','fields','userClass'));
    }


    public function registerSocial(Request $request){


        $rules = [
            'mobile_number'=>'required'
        ];
        $fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();
        foreach($fields as $field){

            if($field->type=='file'){
                $required = '';
                if($field->required==1){
                    $required = 'required|';
                }

                $rules['field_'.$field->id] = 'nullable|'.$required.'max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
            }
            elseif($field->required==1){
                $rules['field_'.$field->id] = 'required';
            }
        }


        $this->validate($request,$rules);

        $socialUser = session('social_user');
        if(!$socialUser){
            return redirect()->route('login')->with('flash_message',__('default.invalid-login'));
        }

        $socialUser  = unserialize($socialUser);

        $data = $request->all();
        $password= Str::random(10);

        $data['name']= $socialUser->firstName;
        $data['last_name'] = $socialUser->lastName;
        $data['email'] = $socialUser->email;
        $data['password'] = Hash::make($password);
        $data['role_id'] = 2;

        $user= User::create($data);
        $user->student->create([
            'mobile_number'=>$request->mobile_number
        ]);

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
                    $customValues[$field->id] = ['value'=>$data['field_'.$field->id]];
                }
            }

        }

        $user->student->studentFields()->sync($customValues);

        return redirect($this->redirectPath());


    }



}
