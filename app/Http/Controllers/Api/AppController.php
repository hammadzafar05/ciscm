<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseCategory;
use App\Http\Controllers\Controller;
use App\Currency;
use App\Setting;
use App\Student;
use App\StudentField;
use App\User;
use App\V2\Model\NewsflashTable;
use App\V2\Model\WidgetValueTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AppController extends Controller
{
    public function login(Request $request) {
        // your code
        // to access items in the container... $this->container->get('');
        $data = $request->all();
        $email = $data['email'];

        $password = Hash::make(trim($data['password']));

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = User::where('email',$email)->first();

            $student = $user->student;
            //check if student has token
            $token = $student->api_token;

            if(empty($token)){
                do{
                    $token = bin2hex(random_bytes(16));
                }while(!Student::where('api_token',$token));

                $student->api_token = $token;

            }

            $timestamp = time() + (86400 * 365);

            $student->token_expires = Carbon::createFromTimestamp($timestamp)->toDateTimeString();
            $student->save();



            return jsonResponse(['id'=>$student->id,'first_name'=>$user->name,'last_name'=>$user->last_name,'token'=>$token,'status'=>true]);
        }
        else{
            return jsonResponse(['status'=>false]);
        }

    }

    public function setup(Request $request){
//        $response->withStatus(200);


        $output = [];

        $settings = [];
        foreach(Setting::where('key','NOT LIKE','footer_%')->where('key','NOT LIKE','social_%')->where('key','NOT LIKE','mail_%')->where('key','NOT LIKE','color_%')->get() as $row){
            $settings[$row->key] = $row->value;
        }
        $exclude = ['general_chat_code','general_header_scripts','general_foot_scripts','footer_newsletter_code','footer_credits','sms_enabled','sms_sender_name','social_enable_facebook','social_facebook_secret','social_facebook_app_id','social_enable_google','social_google_secret','social_google_id'];

        foreach($exclude as $value){
            unset($settings[$value]);
        }
        $output['settings'] = $settings;
        //set default currency

        $output['currencies'] = [];
        foreach(Currency::get() as $currency){
            $output['currencies'][] = [
                'currency_id'=>$currency->id,
                'currency_code'=>$currency->country->currency_code,
                'currency_name'=>$currency->country->currency_name,
                'currency_symbol'=>$currency->country->symbol_left,
                'exchange_rate'=>$currency->exchange_rate
            ];
        }

        $currencyId = currentCurrency()->id;
        $output['student_currency'] = $currencyId;

        $baseUrl = url('/');
        $output['base_path'] = $baseUrl;

        $widgets = [];
        $widgetValueTable = new WidgetValueTable();
        foreach($widgetValueTable->getWidgets(1,'m') as $row){
            if($row->code=='courses'){

                $sessionList = [];
                $vals = unserialize($row->value);
                for($i=1; $i<=10; $i++){
                    if(!empty($vals['session'.$i]) && Course::find($vals['session'.$i])){
                        $record = Course::find($vals['session'.$i]);
                        $sessionList[] = [
                            'session_id'=>$record->id,
                            'session_name'=>$record->name,
                            'amount'=>$record->amount,
                            'payment_required'=>$record->payment_required,
                            'short_description'=>$record->short_description,
                            'session_type'=>$record->type,
                            'picture'=>$record->picture
                        ];
                    }
                }
                $widgets[] = [
                    'widget_code'=>'sessions',
                    'value'=> $sessionList
                ];

            }
            elseif($row->code=='blog'){
                $newsTable = new NewsflashTable();
                $rowSet = $newsTable->getNews(5);
                $data = [];
                foreach($rowSet as $blog){
                    $data[] = [
                        'id'=>$blog->id,
                        'title'=>$blog->title,
                        'content'=>limitLength(strip_tags($blog->content)),
                        'date'=>showDate('d M Y',$blog->publish_date),
                        'picture'=>$blog->cover_photo
                    ];
                }
                $widgets[] = [
                    'widget_code'=>$row->code,
                    'value'=> $data
                ];
            }
            else{
                $widgets[] = [
                    'widget_code'=>$row->code,
                    'value'=> unserialize($row->value)
                ];
            }

        }

        $output['widgets'] = $widgets;

        $registration = [];

        foreach(StudentField::where('enabled',1)->select('id as registration_field_id','name','sort_order','type','options','required','placeholder','enabled as status')->orderBy('sort_order')->get() as $row){
            $fieldData = $row->toArray();
            $fieldData['options'] = explode(PHP_EOL, $fieldData['options']);

            $registration[] = $fieldData;
        }

        $output['registration'] = $registration;

        $categories = [];

        foreach(CourseCategory::where('enabled',1)->orderBy('sort_order')->get() as $row){
            $categories[] = [
                'session_category_id'=>$row->id,
                'category_name'=>$row->name,
            ];
        }
        $output['categories'] = $categories;
        return jsonResponse($output);
    }

    public function update(Request $request) {
        $data = $request->all();
        $student = getApiStudent($request);

        $student->fill($data);
        $student->save();

        $student->user->fill($data);
        $student->user->save();

        return jsonResponse($student->toArray());

    }

}
