<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/28/2017
 * Time: 12:57 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class StudentSessionLogTable extends BaseTable {

    protected $tableName ='student_course_logs';
    //protected $primary ='student_course_log_id';

    public function getLogRecord($id){

    }

    public function getStudentTotal($id,$sessionId){
        $total = $this->tableGateway->select(['student_id'=>$id,'course_id'=>$sessionId])->count();
        return $total;
    }

    public function getStudentRecords($id,$sessionId){
        $select = new Select($this->tableName);
        $select->where(['student_id'=>$id,$this->tableName.'.course_id'=>$sessionId])
                ->join($this->getPrefix().'lessons',$this->tableName.'.lesson_id='.$this->getPrefix().'lessons.id',['lesson_name','lesson_sort_order'=>'sort_order'])
                ->join($this->getPrefix().'lectures',$this->tableName.'.lecture_id=lecture.lecture_id',['lecture_title','lecture_sort_order'=>'sort_order']);
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;
    }

    public function getLastLesson($studentId,$sessionId){

        $select = new Select($this->tableName);
        $select->where(['student_id'=>$studentId,$this->tableName.'.course_id'=>$sessionId])
            ->join($this->getPrefix().'lessons',$this->tableName.'.lesson_id='.$this->getPrefix().'lessons.id',['lesson_name','lesson_sort_order'=>'sort_order'])
            ->order('lesson.sort_order desc')
            ->group($this->tableName.'.lesson_id');

        $row =  $this->tableGateway->selectWith($select)->current();
        return $row;
    }
	
	public function getLastRecordedLesson($studentId,$sessionId){
		
		$select = new Select($this->tableName);
		$select->where(['student_id'=>$studentId,$this->tableName.'.course_id'=>$sessionId])
			->order('id desc')
			->limit(1);
		
		$row =  $this->tableGateway->selectWith($select)->current();
		if(empty($row)){
			return false;
		}
		else{
			$lecture_id = $row->lecture_id;
			return $lecture_id;
		}
	}

    public function hasAttendance($studentId,$sessionId,$lecture_id){

        $select = new Select($this->tableName);
        $select->where(array('course_id'=>$sessionId,'student_id'=>$studentId,'lecture_id'=>$lecture_id));
        $select->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $row = $this->tableGateway->selectWith($select);

        /*
        return false;
        $rowset = $this->tableGateway->select(array('course_id'=>$sessionId,'lesson_id'=>$lessonId,'student_id'=>$studentId));
        $total = $rowset->count();
        */
        $total = $row->current()->num;
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function getAttendance($studentId,$sessionId,$lecture_id){

        $select = new Select($this->tableName);
        $select->where(array('course_id'=>$sessionId,'student_id'=>$studentId,'lecture_id'=>$lecture_id));

        $row = $this->tableGateway->selectWith($select)->current();
        return $row;

    }


    public function setAttendance($data){

        if(empty($data['log_date'])){
            $data['log_date'] = time();
        }

        $select = new Select($this->tableName);
        $select->where(array('student_id'=>$data['student_id'],'course_id'=>$data['course_id'],'lesson_id'=>$data['lesson_id']));
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        $total = $rowset->count();

        if(empty($total)){
            $id = $this->addRecord($data);
            return $id;
        }
        else{
            return $rowset->current()->student_course_log_id;
        }
    }

}
