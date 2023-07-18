<?php
namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class SurveyFilter  extends InputFilter
{
    function __construct() {


        $this->add(array(
            'name'=>'name',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $this->add(array(
            'name'=>'description',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'enabled',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));


    }
}
