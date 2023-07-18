<?php

namespace App\V2\Form;

use App\V2\Model\RegistrationFieldTable;
use Laminas\InputFilter\InputFilter;

class ProfileFilter extends InputFilter {
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
            'name'=>'last_name',
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
