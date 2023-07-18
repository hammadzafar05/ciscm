<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class LectureFilter extends InputFilter {
    function __construct() {


        $this->add(array(
            'name'=>'title',
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




    }
}

?>
