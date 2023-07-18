<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class CourseFilter extends InputFilter {
    function __construct() {


        $this->add(array(
            'name'=>'session_name',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $this->add(array(
            'name'=>'session_status',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));


        $this->add(array(
            'name'=>'payment_required',
            'required'=>false,
        ));


        $this->add(array(
            'name'=>'amount',
            'required'=>false,
            'validators'=>array(
                array(
                    'name'=>'Float'
                )
            )
        ));
        
          $this->add(array(
            'name'=>'regular_fee',
            'required'=>false,
           
        ));

        $this->add(array(
            'name'=>'session_date',
            'required'=>false,

        ));

        $this->add(array(
            'name'=>'session_end_date',
            'required'=>false,

        ));


        $this->add(array(
            'name'=>'enrollment_closes',
            'required'=>false,
        ));





        $this->add(array(
            'name'=>'description',
            'required'=>false,
        ));
        $this->add(array(
            'name'=>'short_description',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ],
            'filters'=>[
                [
                    'name'=>'StripTags'
                ]
            ]

        ));


        $this->add(array(
            'name'=>'picture',
            'required'=>false,
        ));
        
        $this->add(array(
            'name'=>'module_length',
            'required'=>false,
        ));


        $this->add(array(
            'name'=>'session_category_id[]',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'session_instructor_id[]',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'enable_discussion',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'enable_forum',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'capacity',
            'required'=>false,
        ));

        $this->add(array(
            'name'=>'enforce_capacity',
            'required'=>false,
        ));

    }
}

?>
