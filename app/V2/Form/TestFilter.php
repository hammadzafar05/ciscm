<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class TestFilter extends InputFilter {
    function __construct() {
    
        $this->add(array(
            'name'=>'exam_type',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));
    
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



        $this->add(array(
            'name'=>'minutes',
            'required'=>false,
            'validators'=>array(
                array(
                    'name'=>'Digits'
                )
            )
        ));


        $this->add(array(
            'name'=>'allow_multiple',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));


        $this->add(array(
            'name'=>'passmark',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                ),
                array(
                    'name'=>'IsFloat'
                )
            )
        ));

        $this->add(array(
            'name'=>'private',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'show_result',
            'required'=>false,
        ));

    }
}

?>
