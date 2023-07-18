<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class CertificateFilter extends InputFilter {
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
            'required'=>true,
            'validators'=>array(
            )
        ));
        $this->add(array(
            'name'=>'enabled',
            'required'=>true,
            'validators'=>array(
            )
        ));



        $this->add(array(
            'name'=>'image',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $this->add(array(
            'name'=>'orientation',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $this->add(array(
            'name'=>'max_downloads',
            'required'=>false,
            'validators'=>array(
                array(
                    'name'=>'Digits'
                )
            )
        ));

        $this->add(array(
            'name'=>'any_session',
            'required'=>false,
        ));


    }
}

?>
