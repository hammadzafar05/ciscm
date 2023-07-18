<?php

namespace App\V2\Form;

use App\V2\Model\LessonGroupTable;
use App\V2\Model\TestTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class LessonForm extends BaseForm {
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
            'options'=>array('label'=>__lang('Class Name').' ('.__lang('Required').')'),
        ));

        $this->add(array(
            'name'=>'sort_order',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control number',
                'placeholder'=>__lang('Digits only')
            ),
            'options'=>array('label'=>__lang('Sort Order (optional)')),
        ));

        $this->add(array(
            'name'=>'description',
            'attributes' => array(
                'type'=>'textarea',
                'class'=>'form-control ',
                'id'=>'hcontent'
            ),
            'options'=>array('label'=>__lang('Brief Description')),
        ));

        $this->add(array(
            'name'=>'introduction',
            'attributes' => array(
                'type'=>'textarea',
                'class'=>'form-control ',
                'id'=>'hintroduction'
            ),
            'options'=>array('label'=>__lang('Introduction')),
        ));


        $this->add(array(
            'name'=>'picture',
            'attributes' => array(
                'type'=>'hidden',
                'class'=>'form-control ',
                'required'=>'required',
                'id'=>'image'
            ),
            'options'=>array('label'=>__lang('Picture')),
        ));

        $this->createSelect('type','Class Type',['s'=>__lang('Physical Location'),'c'=>__lang('Online Class')],true,true);

        $this->createCheckbox('test_required','Test Required',1);

        $testTable = new TestTable();
        $rowset = $testTable->getLimitedRecords(1000);

        $options = [];
        foreach($rowset as $row)
        {
            $options[$row->id] = $row->name;
        }

        $this->createSelect('test_id','Required Test',$options,false);
        $this->get('test_id')->setAttribute('class','form-control select2');

        $options = [];
        $lessonGroupTable = new LessonGroupTable();
        $rowset = $lessonGroupTable->getLimitedRecords(5000);
        foreach($rowset as $row){
            $options[$row->id]=$row->name;
        }
        $this->createSelect('lesson_group_id[]','Class Groups (Optional)',$options,false);
        $this->get('lesson_group_id[]')->setAttribute('multiple','multiple');
        $this->get('lesson_group_id[]')->setAttribute('class','form-control select2');

        $this->createCheckbox('enforce_lecture_order','Enforce Lecture Order',1);
        $this->get('enforce_lecture_order')->setValue(1);
    }

}

?>
