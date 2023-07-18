<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/1/2017
 * Time: 1:37 PM
 */
/*--MARUF START--*/
namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class StudentTestWrittenAnswerTable extends BaseTable {
    protected $tableName = 'student_test_test_written';
    protected $timeStamp = false;
    //protected $primary = 'student_test_option_id';

    public function getTestRecords($id)
    {
        /*$select = new Select($this->tableName);
        $select->where(['student_test_id'=>$id]);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;*/
	
	    $select = new Select($this->tableName);
	    $select->where(['student_test_id'=>$id])
		    ->join($this->getPrefix().'test_options',$this->tableName.'.test_option_id='.$this->getPrefix().'test_options.id')
		    ->join($this->getPrefix().'test_questions',$this->getPrefix().'test_options.test_question_id='.$this->getPrefix().'test_questions.id');
	    $rowset = $this->tableGateway->selectWith($select);
	    return $rowset;
	    
    }

}


