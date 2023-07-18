<?php

namespace App\V2\Form;

use App\V2\Model\AgeRangeTable;
use App\V2\Model\MaritalStatusTable;
use App\V2\Model\RegistrationFieldTable;
use App\V2\Model\StudentSessionTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class DiscussionForm extends BaseForm {
    public function __construct($name = null,$id)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');
        $this->addCSRF();

        $this->createText('subject','Subject',true);
        $this->createTextArea('question','Your Question',true,null,__lang('Ask your question here'));

        $studentSessionTable = new StudentSessionTable();
        $rowset = $studentSessionTable->getSessionInstructors($id);
        $options = [];
        $options['admins'] = __lang('Administrators');
        foreach($rowset as $row){
            if(!empty($row->enable_discussion)){
                $options[$row->admin_id]= $row->first_name.' '.$row->last_name.' ('.$row->name.')';
            }

         }


        $this->createSelect('admin_id[]','Recipients'.'(Admins/Instructors)',$options,true);
        $this->get('admin_id[]')->setAttribute('multiple','multiple');
        $this->get('admin_id[]')->setAttribute('class','form-control select2');

        $rowset = $studentSessionTable->getStudentRecords(false,$id);
        $options = [];
        foreach($rowset as $row){
            if(!empty($row->enable_discussion)){
                $options[$row->course_id] = $row->name;
            }

        }
        $this->createSelect('course_id','course-session-(optional)',$options,false);
        $this->get('course_id')->setAttribute('class','form-control select2');

        $this->createHidden('lecture_id','');
    }



}

?>
