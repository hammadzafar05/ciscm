<?php

namespace Site\Form;


use Laminas\Form\Element\Captcha;
use Laminas\Captcha\AdapterInterface as CaptchaAdapter;
use Laminas\Form\Element;
use App\Lib\BaseForm;

class MerchantRegistrationForm extends BaseForm {


	function __construct($serviceLocator,CaptchaAdapter $captchaAdapter = null) {
		parent::__construct('MerchantRegistration');
		$this->setAttribute('method', 'post');
		$this->setServiceLocator($serviceLocator);

		$fields = array(
				'merchant_firstname'=>array('type'=>'text','label'=>__lang('Firstname'),'required'=>true),
				'merchant_lastname'=>array('type'=>'text','label'=>__lang('Lastname'),'required'=>true),
				'merchant_tel'=>array('type'=>'text','label'=>__lang('Telephone Number')),
				'merchant_email'=>array('type'=>'text','label'=>__lang('Email'),'required'=>true),
				'merchant_password'=>array('type'=>'password','label'=>__lang('Password'),'required'=>true),
				'confirm_password'=>array('type'=>'password','label'=>__lang('Confirm Password'),'required'=>true),
				'username'=>array('type'=>'text','label'=>__lang('Username'),'required'=>true)
		);

		$this->createElements($fields);

		//add captcha

		$captcha = new Captcha('captcha');
        $captcha->setCaptcha($captchaAdapter);
        $captcha->setOptions(array('label' => __lang('captcha-label')));
        $this->add($captcha);

		$csrf = new Element\Csrf('security');


		 $this->add($csrf);




	}


}

?>
