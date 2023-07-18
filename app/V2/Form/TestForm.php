<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/1/2017
 * Time: 1:55 PM
 */

namespace App\V2\Form;


use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionTable;
use App\Lib\BaseForm;

class TestForm extends BaseForm{

    public function __construct($name = null,$serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->createInputNumber('number_of_questions','number_of_questions',true);
        $this->createText('name','Test Name',true);
        $this->createTextArea('description','Instructions');
	    $this->createSelect('exam_type','Type',[''=>__lang('select-an-element'),1=>__lang('shomvabona-written'),0=>__lang('shomvabona-mcq')],true,false);
        $this->createSelect('enabled','Status',[1=>__lang('Enabled'),0=>__lang('Disabled')],true,false);
        $this->createText('minutes','time-allowed-mins',null,'form-control number',null,__lang('digits-only-no-limit'));
        $this->createSelect('allow_multiple','Allow Multiple Attempts',['1'=>__lang('Yes'),'0'=>__lang('No')],true,false);
        $this->get('description')->setAttribute('id','description');
        $this->createText('passmark','passmark-percent',true,'form-control number',null,__lang('Digits Only'));
	
	    $this->createText('due_date','Due Date',true,'form-control date_time readonly');
        /*
        $sessionTable = new SessionTable($serviceLocator);
        $sessions = $sessionTable->getPaginatedRecords(true);
        $sessions->setCurrentPageNumber(1);
        $sessions->setItemCountPerPage(500);
        $options=array();
        foreach ($sessions as $row)
        {
            $options[$row->session_id]=$row->session_name;
        }

        $sessionInstructorTable = new SessionInstructorTable($serviceLocator);
        $rowset = $sessionInstructorTable->getAccountRecords(ADMIN_ID);
        foreach($rowset as $row){
            $options[$row->session_id] = $row->session_name;
        }

        $this->createSelect('session_id', 'Session/Course (Optional)', $options);
        $this->get('session_id')->setAttribute('class','form-control select2');
        */
        $this->createCheckbox('private','private-test-question',1);
        $this->get('private')->setValue(1);
        $this->createCheckbox('show_result','show-result-student',1);
        $this->get('show_result')->setValue(1);

    }

}
