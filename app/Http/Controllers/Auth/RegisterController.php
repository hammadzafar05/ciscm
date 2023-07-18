<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\StudentField;
use App\PendingStudent;
use App\Lib\HelperTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\WelcomeEmailJob;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use HelperTrait;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile_number' => ['required', 'string', 'max:255'],
            'agree'=>'required'
        ];

        if(setting('regis_captcha_type')=='image'){
            $rules['captcha'] = 'required|captcha';
        }
        if(setting('regis_captcha_type')=='google'){
            $rules['captcha_token'] = 'required';
        }

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

        return Validator::make($data,$rules);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $enableRegistration = !empty(setting('regis_enable_registration'));
        if (!$enableRegistration){
             abort(401);
        }

        if(setting('regis_captcha_type')=='google'){
            $recaptcha_secret = setting('regis_recaptcha_secret');
            $recaptcha_response = $request->captcha_token;



            $curl = curl_init();

            $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";

            curl_setopt($curl, CURLOPT_URL,$captcha_verify_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=".$recaptcha_secret."&response=".$recaptcha_response);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $captcha_output = curl_exec($curl);

            curl_close ($curl);

            $recaptcha = json_decode($captcha_output);



            // Take action based on the score returned:
            try{
                if ($recaptcha->score < 0.5) {
                    // Verified - send email
                    flashMessage(__lang('invalid-captcha'));
                    return back();
                }
            }
            catch(\Exception $ex){
                flashMessage($ex->getMessage());
                return back();
            }



        }

        $this->validator($request->all())->validate();

        //check if email verification is required
        if(setting('regis_confirm_email')==1){

            do{
                $hash = Str::random(30);
            }while(PendingStudent::where('hash',$hash)->first());


            $formData  = $_POST;

            $formData['role_id'] = 2;

            $pendingStudent = PendingStudent::create([
                'role_id'=>2,
                'data'=> serialize($formData),
                'hash'=> $hash
            ]);
            $fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();
            //scan for files
            foreach($fields as $field){
                if(isset($requestData['field_'.$field->id]) && $field->type=='file' && $request->hasFile('field_'.$field->id))
                {
                    //generate name for file

                    $name = $_FILES['field_'.$field->id]['name'];

                    //dd($name);


                    $extension = $request->{'field_'.$field->id}->extension();
                    //  dd($extension);

                    $name = str_ireplace('.'.$extension,'',$name);

                    $name = $pendingStudent->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                    $path =  $request->file('field_'.$field->id)->storeAs(PENDING_USER_FILES,$name,'public_uploads');



                    $file = UPLOAD_PATH.'/'.$path;
                    $pendingStudent->pendingStudentFiles()->create([
                        'file_name'=>$_FILES['field_'.$field->id]['name'],
                        'file_path'=>$file,
                        'field_id'=>$field->id
                    ]);

                }


            }

            //send email to user
            $link = route('confirm.student',['hash'=>$hash],true);

            $this->sendEmail($request->email,__('default.confirm-your-email'),__('default.confirm-email-mail',['link'=>$link]));
            $email = $request->email;
            $emailJob = (new WelcomeEmailJob($email))->delay(now()->addMinutes(30));
            dispatch($emailJob);
            return redirect()->route('register.confirm');
        }



        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $user= User::create([
            'name' => $data['name'],
            'last_name'=>$data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'=>2
        ]);

        $user->student()->create([
           'mobile_number'=>$data['mobile_number']
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
                    $customValues[$field->id] = ['value'=>$data['field_'.$field->id]];
                }
            }


        }

        $user->student->studentFields()->sync($customValues);

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$data['email'],
            'password'=>$data['password'],
            'link'=> url('/login')
        ]);

        if (!empty(setting('regis_email_message')))
        {
            $message .= '<br/>'.setting('regis_email_message');
        }

        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($data['email'],$subject,$message);


        if (setting('regis_signup_alert')==1){
            $this->notifyAdmins(__lang('New registration'),$data['name'].' '.$data['last_name'].' '.__lang('just registered'));
        }

        return $user;
    }

    public function showRegistrationForm()
    {
          $enableRegistration = !empty(setting('regis_enable_registration'));
          if (!$enableRegistration){
              return abort(401);
          }
          $fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();
          return tview('auth.register',compact('enableRegistration','fields'));
    }

    public function confirmStudent($hash){
        //get pending user
        $pendingStudent = PendingStudent::where('hash',$hash)->first();
        if(!$pendingStudent){
            abort(404);
        }

        $requestData = unserialize($pendingStudent->data);
        $password = $requestData['password'];
        $requestData['password'] = Hash::make($password);

        //check for profile picture and move to new directory
        if(!empty($requestData['picture']) && file_exists($requestData['picture'])){

            $file = basename($requestData['picture']);

            $newPath = UPLOAD_PATH.'/'.STUDENT_FILES.'/'.$file;
            rename($requestData['picture'],$newPath);
            $requestData['picture'] = $newPath;

        }




        //First create user
        $user= User::create($requestData);



        $user->student()->create(['mobile_number'=>$requestData['mobile_number']]);




        $fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){

            if($field->type=='file'){
                $pendingFile = $pendingStudent->pendingStudentFiles()->where('field_id',$field->id)->first();

                if($pendingFile){

                    //generate name for file
                    $name = $pendingFile->file_name;

                    $info = new \SplFileInfo($name);

                    $extension = $info->getExtension();

                    $name = str_ireplace('.'.$extension,'',$name);

                    $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                    $file = UPLOAD_PATH.'/'.STUDENT_FILES.'/'.$name;

                    rename($pendingFile->file_path,$file);

                    $customValues[$field->id] = ['value'=>$file];
                }
            }
            elseif(isset($requestData['field_'.$field->id])){
                $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
            }


        }

        $user->student->studentFields()->sync($customValues);
        $pendingStudent->delete();

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$requestData['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($requestData['email'],$subject,$message);

        //now login user
        Auth::login($user, true);

        //redirect to relevant page

         return redirect()->route('home');

    }

    public function confirm(){
        return tview('auth.confirm');
    }

    public function newCaptcha(){
        echo captcha_img();
    }

    public function redirectPath()
    {
        $link = getRedirectLink();
        if (!empty($link)){
            return $link;
        }

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

}
