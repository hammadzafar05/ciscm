<?php

namespace App\V2\Form;

use App\Lib\BaseForm;
use Laminas\Form\Form;

class LectureForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');



        $this->add(array(
            'name'=>'title',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control ',
                'required'=>'required',
            ),
            'options'=>array('label'=>__lang('Lecture Title')),
        ));

        $this->add(array(
            'name'=>'sort_order',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control number',
                'placeholder'=>'Digits only'
            ),
            'options'=>array('label'=>__lang('Sort Order').' ('.__lang('optional').')'),
        ));






    }

}

?>
