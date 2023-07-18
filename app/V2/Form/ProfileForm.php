<?php

namespace App\V2\Form;

use App\V2\Model\AgeRangeTable;
use App\V2\Model\MaritalStatusTable;
use App\V2\Model\RegistrationFieldTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class ProfileForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');



        $this->add(array(
            'name'=>'name',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control ',
                'required'=>'required',
            ),
            'options'=>array('label'=>__lang('Firstname')),
        ));

        $this->add(array(
            'name'=>'last_name',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control ',
                'required'=>'required',
            ),
            'options'=>array('label'=>__lang('Lastname')),
        ));

        $this->createSelect('notify','Receive Notifications',['1'=>__lang('Yes'),'0'=>__lang('No')],true,false);

        $this->createTextArea('about','About');

        $this->add(array(
            'name'=>'picture',
            'attributes' => array(
                'type'=>'hidden',
                'class'=>'form-control ',
                'required'=>'required',
                'id'=>'image'
            ),
            'options'=>array('label'=>__lang('Picture')),
        ));

    }



}

?>
