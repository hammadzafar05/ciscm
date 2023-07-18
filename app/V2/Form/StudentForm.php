<?php

namespace App\V2\Form;

use App\Lib\BaseForm;
use App\V2\Model\AgeRangeTable;
use App\V2\Model\MaritalStatusTable;
use App\V2\Model\RegistrationFieldTable;
use Laminas\Form\Element\File;
use Laminas\Form\Form;

class StudentForm extends BaseForm {
    public function __construct($name = null,$serviceLocator,$activeOnly=false)
    {
        $this->setServiceLocator($serviceLocator);
    	// we want to ignore the name passed
    	parent::__construct('user');
    	$this->setAttribute('method', 'post');
        $this->addCSRF();
        $this->translate = false;


    	$this->add(array(
    		'name'=>'name',
    	    'attributes' => array(
    	    'type'=>'text',
    	    		'class'=>'form-control ',
    	    		'required'=>'required',
    	        ),
    	    'options'=>array('label'=>__lang('First name')),
    	));

    	$this->add(array(
    			'name'=>'last_name',
    	    'attributes' => array(
    			'type'=>'text',
    	    		'class'=>'form-control ',
    	    		'required'=>'required',
    	        ),
    			'options'=>array('label'=>__lang('Last name')),
    	));





    	$this->add(array(
    			'name'=>'mobile_number',
    			'attributes' => array(
    					'type'=>'text',
    					'class'=>'form-control ',
    			),
    			'options'=>array('label'=>__lang('Mobile Number')),
    	));



    	$this->add(array(
    		'name'=>'email',
    	    'attributes' => array(
    	    'type'=>'email',
    	    'class'=>'form-control ',
    	    		'required'=>'required',
    	        ),
    	    'options'=>array('label'=>__lang('Email')),
    	));

        $this->createSelect('status','Status',['1'=>__lang('Active'),'0'=>__lang('Inactive')],true,false);

        //create new form
        $registrationFieldsTable = new \App\V2\Model\RegistrationFieldTable($serviceLocator);
        if($activeOnly)
        {
            $rowset= $registrationFieldsTable->getActiveFields();
        }
        else{
            $rowset = $registrationFieldsTable->getAllFields();
        }

        foreach($rowset as $row){

            switch($row->type){

                case 'checkbox':
                    $this->createCheckbox('custom_'.$row->id,$row->name,1);
                    break;
                case 'radio':
                    $vals = nl2br($row->options);
                    $options = explode('<br />',$vals);

                    $selectOptions =[];
                    foreach($options as $value){
                        $selectOptions[$value]=$value;
                    }

                    $this->add(array(
                        'type' => 'Zend\Form\Element\Radio',
                        'name' => 'custom_'.$row->id,
                        'options' => array(
                            'label' => $row->name,
                            'value_options' => $selectOptions,
                        )
                    ));
                    break;
                case 'text':
                    $this->createText('custom_'.$row->id,$row->name,!empty($row->required),null,null,$row->placeholder);
                    break;
                case 'textarea':
                    $this->createTextArea('custom_'.$row->id,$row->name,!empty($row->required),null,$row->placeholder);
                    break;
                case 'select':
                    $vals = nl2br($row->options);
                    $options = explode('<br />',$vals);

                    $selectOptions =[];
                    foreach($options as $value){
                        $selectOptions[$value]=$value;
                    }
                    $this->createSelect('custom_'.$row->id,$row->name,$selectOptions,!empty($row->required));
                    break;
                case 'file':
                    $file = new File('custom_'.$row->id);
                    $file->setLabel($row->name)
                        ->setAttribute('id','custom_'.$row->id);
                    $this->add($file);


            }

        }


        $file = new File('picture');
        $file->setLabel(__lang('Display Picture'))
            ->setAttribute('id','picture');
        $this->add($file);

    }

    public function addPasswordField(){

        $this->createPassword('password','Password',true);
    }

}

?>
