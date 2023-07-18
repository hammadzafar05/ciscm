<?php

namespace App\V2\Form;

use App\V2\Model\SessionTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class DownloadForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');



        $this->add(array(
            'name'=>'name',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control ',
                'required'=>'required',
            ),
            'options'=>array('label'=>__lang('Download Name')),
        ));


        $this->add(array(
            'name'=>'description',
            'attributes' => array(
                'type'=>'textarea',
                'class'=>'form-control ',
                'required'=>'required',
                'id'=>'hcontent'
            ),
            'options'=>array('label'=>__lang('Description')),
        ));



     $this->createSelect('enabled','Enabled',[1=>__lang('Yes'),0=>__lang('No')],true,false);



    }

}

?>
