<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Form\ProfileFilter;
use App\V2\Form\ProfileForm;
use App\V2\Model\AccountsTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    use HelperTrait;
    public function email(Request $request)
    {

        $output = array();
        $user = $this->getAdmin();
        $email = $user->email;
        $accountsTable =new AccountsTable();

        if (request()->isMethod('post')) {
            $post = request()->all();
            $this->validate($request,[
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            $newEmail = $post['email'];
            $accountsTable->tableGateway->update(array('email'=>$newEmail),array('email'=>$email));
            $output['flash_message']= __lang('email-changed-to',['email'=>$newEmail]);
        }
        $output['pageTitle']=__lang('Change Your Email');
        return view('admin.account.email',$output);
    }


    public function password(Request $request)
    {
        $output = array();
        $user = $this->getAdmin();

        $accountsTable =new AccountsTable();
        if (request()->isMethod('post')) {
            $this->validate($request,[
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);


                $user->password = Hash::make($request->password);
                $user->save();
                $output['flash_message']=__lang('Password changed!');


        }
        $output['pageTitle']=__lang('Change Your Password');
        return view('admin.account.password',$output);
    }



    public function profile(Request $request)
    {

        $output = array();
        $accountsTable =new AccountsTable();
        $user = $this->getAdmin();
        $form = new ProfileForm(null,$this->getServiceLocator());
        $filter = new ProfileFilter();
        $form->setInputFilter($filter);

        if (request()->isMethod('post')) {
            $post = request()->all();
            $form->setData($post);

            if($form->isValid()){
                $data = $form->getData();
            //    $accountsTable->update($data,$user->id);
                $user->update($data);
                $user->admin->update([
                   'notify'=>$data['notify'],
                   'about'=>$data['about']
                ]);

                $output['flash_message']=__lang('Changes Saved!');
            }
            else{

                $output['flash_message']=__lang('Submission Failed');
            }

        }
        else{

            $form->setData([
                'name'=>$user->name,
                'last_name'=>$user->last_name,
                'notify'=>$user->admin->notify,
                'about'=>$user->admin->about
            ]);
        }

        if ($user->picture && file_exists(DIR_MER_IMAGE . $user->picture) && is_file(DIR_MER_IMAGE . $user->picture)) {
            $output['display_image'] = resizeImage($user->picture, 100, 100,$this->getBaseUrl());
        } else {
            $output['display_image'] = resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        }


        $output['no_image']= $this->getBaseUrl().'/'.resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());


        $output['form'] = $form;
        $output['pageTitle']=__lang('Profile');
        return view('admin.account.profile',$output);
    }

}
