<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class TestQuestionFilter extends InputFilter {
    function __construct() {


        $this->add(array(
            'name'=>'question',
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
        ));




    }
}

?>