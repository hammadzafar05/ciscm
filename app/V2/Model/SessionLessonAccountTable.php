<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 3/3/2017
 * Time: 1:01 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SessionLessonAccountTable extends BaseTable {
    protected $tableName='course_lesson_admins';
    //protected $primary='course_lesson_admin_id';

    public function accountExists($lessonId,$sessionId,$accountId){

        $total = $this->tableGateway->select(['lesson_id'=>$lessonId,'course_id'=>$sessionId,'admin_id'=>$accountId])->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function getTotalInstructors($lessonId,$sessionId){

        $total = $this->tableGateway->select(['lesson_id'=>$lessonId,'course_id'=>$sessionId])->count();
        return $total;
    }

    public function getInstructors($lessonId,$sessionId){
        $select = new Select($this->tableName);
        $select->where(['lesson_id'=>$lessonId,'course_id'=>$sessionId])
            ->join($this->getPrefix().'admins',$this->tableName.'.admin_id=admins.id',[])
            ->join($this->getPrefix().'users',$this->getPrefix().'admins.user_id='.$this->getPrefix().'users.id',['name','email','last_name']);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    public function clearSessionLessons($id,$lessonId){
        $this->tableGateway->delete(['course_id'=>$id,'lesson_id'=>$lessonId]);
    }


    public function getSessionRecords($id){
        $select = new Select($this->tableName);
        $select->where(['course_id'=>$id]);
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;
    }



}
