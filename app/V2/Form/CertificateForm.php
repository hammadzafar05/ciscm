<?php

namespace App\V2\Form;

use App\V2\Model\SessionTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class CertificateForm extends BaseForm {
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
            'options'=>array('label'=>__lang('Certificate Name')),
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



        $this->add(array(
            'name'=>'image',
            'attributes' => array(
                'type'=>'hidden',
                'class'=>'form-control ',
                'required'=>'required',
                'id'=>'image'
            ),
            'options'=>array('label'=>__lang('Certificate Image')),
        ));

        $this->createSelect('orientation','Orientation',['p'=>__lang('Portrait'),'l'=>__lang('Landscape')],true,false);
        $this->createSelect('enabled','Enabled',[1=>__lang('Yes'),0=>__lang('No')],true,false);

        //get student categories
        $sessionTable =new SessionTable();
        $sessions = $sessionTable->getPaginatedRecords(true);
        $sessions->setCurrentPageNumber(1);
        $sessions->setItemCountPerPage(500);
        $options=array();
        foreach ($sessions as $row)
        {
            $options[$row->id]=$row->name;
        }

        $this->createSelect('course_id', 'Session/Course', $options);
        $this->get('course_id')->setAttribute('class','form-control select2');
        $this->createText('max_downloads','Maximum Downloads',false,'form-control number',null,__lang('Digits only'));
    }

}

?>
