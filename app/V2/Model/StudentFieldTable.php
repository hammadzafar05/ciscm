<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/13/2017
 * Time: 3:50 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class StudentFieldTable extends BaseTable {

    protected $tableName = 'student_student_field';
    protected $timeStamp =false;
    //protected $primary = 'student_field_id';

    public function saveField($studentId,$fieldId,$value)
    {
        $total = $this->tableGateway->select(['student_id'=>$studentId,'student_field_id'=>$fieldId])->count();

        if(empty($total)){
            $this->addRecord([
               'student_id'=>$studentId,
                'student_field_id'=>$fieldId,
                'value'=>$value
            ]);
        }
        else{
            $this->tableGateway->update(['value'=>$value],['student_id'=>$studentId,'student_field_id'=>$fieldId,]);
        }


    }

    public function getStudentFieldRecord($studentId,$fieldId){
         $select = new Select($this->tableName);
        $select->where(['student_id'=>$studentId,$this->getPrefix().'student_fields.id'=>$fieldId])
            ->join($this->getPrefix().'student_fields',$this->tableName.' .student_field_id=student_fields.id',['type','name']);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->current();

    }

    public function getStudentRecords($studentId){
        return $this->tableGateway->select(['student_id'=>$studentId]);
    }

    public function getStudentRecordsAll($studentId){
        $select = new Select($this->tableName);
        $select->where(['student_id'=>$studentId])
            ->join($this->getPrefix().'student_fields',$this->tableName.'.student_field_id='.$this->getPrefix().'student_fields.id');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

}
