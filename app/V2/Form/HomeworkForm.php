<?php

namespace App\V2\Form;

use App\V2\Model\LessonTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionTable;
use Laminas\Form\Form;
use App\V2\Model\StudentCategoriesTable;
use App\Lib\BaseForm;

class HomeworkForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {
    	// we want to ignore the name passed
    	parent::__construct('user');
    	$this->setAttribute('method', 'post');



    	$this->add(array(
    		'name'=>'title',
    	    'attributes' => array(
    	    'type'=>'text',
    	    		'class'=>'form-control ',
    	    		'required'=>'required',
    	        ),
    	    'options'=>array('label'=>__lang('Title')),
    	));

    	$this->add(array(
    			'name'=>'content',
    	    'attributes' => array(
    			'type'=>'textarea',
    	    		'class'=>'form-control ',
    	    		'required'=>'required',
    	    		'id'=>'hcontent'
    	        ),
    			'options'=>array('label'=>__lang('Content')),
    	));



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

        $sessionInstructorTable = new SessionInstructorTable();
        $rowset = $sessionInstructorTable->getAccountRecords(ADMIN_ID);
        foreach($rowset as $row){
            $options[$row->course_id] = $row->name;
        }
    	$this->createSelect('course_id', 'Session/Course', $options);


        $this->get('course_id')->setAttribute('class','form-control select2');



        $lessonTable = new LessonTable();
        $rowset = $lessonTable->getRecords();
        $option= [];
        foreach($rowset as $row)
        {
            $option[$row->id] = $row->name;
        }

       $this->createSelect('lesson_id','Class',$option,true);


      //  $this->get('course_id')->setAttribute('data-ng-change',"loadBulkStudents()");

        $this->get('course_id')->setAttribute('required','required');
      //  $this->get('course_id')->setAttribute('data-ng-model','course_id');

      //  $this->get('lesson_id')->setAttribute('data-ng-model','lesson_id');
     //   $this->get('lesson_id')->setAttribute('data-ng-options','o.id as o.name for o in lessonList');

    	$this->add(array(
    			'name'=>'description',
    			'attributes' => array(
    					'type'=>'textarea',
    					'class'=>'form-control ',
    					'required'=>'required',
    			),
    			'options'=>array('label'=>__lang('Description').' ('.__lang('optional').')'),
    	));



    }

}

?>
