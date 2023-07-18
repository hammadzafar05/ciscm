<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class DownloadFilter extends InputFilter {
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




    }
}

?>
