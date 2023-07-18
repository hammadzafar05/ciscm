<?php

namespace App\V2\Form;

use Laminas\Form\Element\Captcha;
use Laminas\Captcha\AdapterInterface as CaptchaAdapter;
use Laminas\Form\Element;
use App\Lib\BaseForm;

class PasswordResetForm extends BaseForm {


	function __construct($serviceLocator,CaptchaAdapter $captchaAdapter = null) {
		parent::__construct('Password Reset');
		$this->setAttribute('method', 'post');
		$this->setServiceLocator($serviceLocator);
	    $this->addCSRF();
		$fields = array(
				'password'=>array('type'=>'password','label'=>'Password','required'=>true,'placeholder'=>'Enter your password'),
				'confirm_password'=>array('type'=>'password','label'=>'Confirm Password','required'=>true),
		 );

		$this->createElements($fields);






	}


}

?>
