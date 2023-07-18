<?php

namespace App\V2\Form;

use App\V2\Model\CountryTable;
use App\V2\Model\SettingTable;
use App\Lib\BaseForm;
use Laminas\Form\Element\Radio;
use Laminas\Form\Form;

class SettingForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {
        // we want to ignore the name passed
        parent::__construct('setting');
        $this->setAttribute('method', 'post');
        $settingTable = new SettingTable($serviceLocator);
        $countryTable = new CountryTable($serviceLocator);
        $rowset = $settingTable->getRecords();



        foreach($rowset as $row)
        {
            $placeholder=$row->placeholder;
                if(!empty($placeholder)){
                    $placeholder = __lang($row->key.'-plac');
                }
            switch($row->type){
                case 'text':
                    $class='form-control';
                    if(!empty($row->class)){
                        $class='form-control '.$row->class;
                    }

                    $this->createText($row->key,$row->key,false,$class,null,$placeholder);
                    break;
                case 'textarea':
                    $this->createTextArea($row->key,$row->key,false,null,$placeholder);
                    if($row->class=='rte'){
                        $this->get($row->key)->setAttribute('id','rte_'.$row->key);
                    }

                    break;
                case 'hidden':
                    $this->add(array(
                        'name' => $row->key,
                        'attributes' => array(
                            'type'  => 'hidden',
                            'id'=>$row->key
                        ),
                        'options' => array(
                            'label' => $row->key,
                        ),
                    ));
                    break;
                case 'select':
                    if(!empty($row->options)){
                        $options = explode(',',$row->options);
                        $foptions = [];

                        foreach($options as $option){
                            if(preg_match('#=#',$option)) {
                                $temp = explode('=', $option);
                                $foptions[$temp[0]] = $temp[1];
                            }
                            else{
                                $foptions[$option]=$option;
                            }

                        }

                    }
                    else{
                        $foptions=[];
                    }
                    $this->createSelect($row->key,$row->key,$foptions,false,false);
                    break;
                case 'checkbox':
                    $this->createCheckbox($row->key,$row->key,1);
                    break;
                case 'radio':
                    $foptions = [];
                    if(!empty($row->options)){
                        $options = explode(',',$row->options);


                        foreach($options as $option){
                            $temp = explode('=',$option);
                            $foptions[$temp[0]]= __lang($temp[1]);
                        }

                    }

                    $this->add(array(
                        'type' => Radio::class,
                        'name' => $row->key,
                        'options' => array(
                            'label' => __lang($row->key),
                            'value_options' => $foptions,
                        )
                    ));
                    break;

            }



        }

        $countries = [];
        $rowset = $countryTable->getRecords();
        foreach($rowset as $row){
            $countries[$row->id] = $row->name.'/'.$row->currency_code;
        }

        $this->get('country_id')->setValueOptions($countries);

        $elements = $this->getElements();
        foreach($elements as $element){
           if(preg_match('#color_#',$element->getName())){
               $element->setAttribute('class','colorpicker-full form-control');
               $element->setAttribute('style','width:80px; display: inline;');
           }

        }

    }

}

?>
