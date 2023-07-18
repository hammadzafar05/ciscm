<?php

namespace App\V2\Form;

use App\V2\Model\AccountsTable;
use App\V2\Model\LessonTable;
use App\Lib\BaseForm;
use App\V2\Model\SessionCategoryTable;
use Laminas\Form\Form;

class SessionForm extends BaseForm {
    public function __construct($name = null,$serviceLocator,$type=null)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');



        $this->add(array(
            'name'=>'session_name',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control',
                'required'=>'required',
            ),
            'options'=>array('label'=>__lang('Session Name')),
        ));
    
    
    
        $sessionCategoryTable = new SessionCategoryTable();
        $options = $sessionCategoryTable->getAllCategories();
        $this->createSelect('session_category_id','Session Categories (optional)',$options,FALSE,FALSE);
        //$this->get('session_category_id')->setAttribute('multiple','multiple');
        $this->get('session_category_id')->setAttribute('class','form-control ');

        $this->add(array(
            'name'=>'session_date',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control date',
            ),
            'options'=>array('label'=>__lang('Session Date')),
        ));

        $this->add(array(
            'name'=>'session_end_date',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control date',
            ),
            'options'=>array('label'=>__lang('Session End Date')),
        ));



        $this->createSelect('payment_required','Payment Required',['0'=>__lang('No'),'1'=>__lang('Yes')],true,false);
        $this->createText('amount','Session Fee',false,'form-control digit',null,__lang('digits-only-optional'));
        $this->createText('regular_fee','Regular Fee',false,'form-control digit',null,__lang('digits-only-optional'));
        $this->createSelect('session_status','Status',array('0'=>__lang('Disabled'),'1'=>__lang('Enabled')),true,false);
        $this->createTextArea('short_description','Short Description');
        $this->get('short_description')->setAttribute('maxlength',300);
        $this->add(array(
            'name'=>'enrollment_closes',
            'attributes' => array(
                'type'=>'text',
                'class'=>'form-control date',
            ),
            'options'=>array('label'=>__lang('Enrollment Closes')),
        ));


        $this->createTextArea('description','Description');
        $this->get('description')->setAttribute('id','description');
        $this->createTextArea('venue','Venue');


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
        
           $this->add(array(
            'name'=>'module_length',
            'attributes' => array(
                'type'=>'hidden',
                'class'=>'form-control ',
                'required'=>'required',
                'id'=>'module_length'
            ),
            'options'=>array('label'=>__lang('module_length')),
        ));     
        
        
       


        $accountsTable = new AccountsTable($serviceLocator);
        $rowset = $accountsTable->getRecordsSorted();
        $options = [];
        foreach($rowset as $row){
            $options[$row->id]= $row->user_name.' ('.$row->email.')';
        }

        $this->createSelect('session_instructor_id[]','Course Instructors (Optional)',$options,false);
        $this->get('session_instructor_id[]')->setAttribute('multiple','multiple');
        $this->get('session_instructor_id[]')->setAttribute('class','form-control select2');
        $this->createSelect('enable_forum','Enable Forum',['1'=>__lang('Yes'),'0'=>__lang('No')],true,false);
        $this->createSelect('enable_discussion','Enable Discussions',['1'=>__lang('Yes'),'0'=>__lang('No')],true,false);
        $this->createText('capacity',__lang('capacity'),false,'form-control digit',null,__lang('digits-only-optional'));
        $this->createSelect('enforce_capacity','Enforce Capacity',['0'=>__lang('No'),'1'=>__lang('Yes')],true,false);
    
        $this->createSelect('emi_status','EMI Enabled?',['Disabled'=>__lang('Disabled'),'Enabled'=>__lang('Enabled')],false,false);
        $this->createSelect('emi_installment','Number of EMI Installment',['0'=>0,'3'=>3,'6'=>6,'9'=>9,'12'=>12],false,false);
    
        $this->createTextArea('grading_system','grading_system',false);
        $this->get('grading_system')->setAttribute('id','grading_system');
       
        $this->createTextArea('module_description','module_description',false);
        $this->get('module_description')->setAttribute('id','module_description');
        
        
       
       
        
        $this->createTextArea('student_feedback','student_feedback',false);
        $this->get('student_feedback')->setAttribute('id','student_feedback');
        
        $this->createTextArea('course_faq','course_faq',false);
        $this->get('course_faq')->setAttribute('id','course_faq');
        
        $this->createTextArea('course_details','course_details',false);
        $this->get('course_details')->setAttribute('id','course_details');
    
    
    
        $this->createTextArea('accreditation','accreditation',false);
        $this->get('accreditation')->setAttribute('id','accreditation');
       
        $this->createTextArea('course_objective','course_objective',false);
        $this->get('course_objective')->setAttribute('id','course_objective');
    }

}

?>
