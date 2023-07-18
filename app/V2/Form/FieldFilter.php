<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class FieldFilter extends InputFilter {
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
            'name'=>'sort_order',
            'required'=>false,
            'validators'=>array(
                array(
                    'name'=>'Digits'
                )
            )
        ));

        $this->add(array(
            'name'=>'type',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $this->add(array(
            'name'=>'options',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'placeholder',
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

?>
