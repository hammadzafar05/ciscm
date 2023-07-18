<?php

namespace App\V2\Form;

use App\V2\Model\RegistrationFieldTable;
use Laminas\InputFilter\InputFilter;

class DiscussionFilter extends InputFilter {
    function __construct() {


        $this->add(array(
            'name'=>'subject',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            ),
            'filters'=>[

                [
                    'name'=>'StripTags'
                ]
            ]

        ));

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
            'name'=>'admin_id[]',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'course_id',
            'required'=>false,
        ));








    }
}

?>
