<?php
// filename : module/Users/src/Users/Form/RegisterForm.php
namespace App\V2\Form;

use App\Lib\BaseForm;
use Laminas\Form\Form;

class LoginForm extends BaseForm
{
    public function __construct($name = null)
    {
        parent::__construct('Login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        $this->addCSRF();

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
				'required' => 'required'
            ),
            'options' => array(
                'label' => __lang('Email'),
            ),
        ));

	$this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
				'required' => 'required'
            ),
            'options' => array(
                'label' => __lang('Password'),
            ),
        ));

	$this->add(array(
			'name'=>'rememberme',
			'type' => 'Zend\Form\Element\Checkbox',
			'options'=>array('label'=>__lang('Remember Me'),'checked_value'=>1,'unchecked_value'=>0),
	));



        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => __lang('Login')
            ),
        ));
    }
}
