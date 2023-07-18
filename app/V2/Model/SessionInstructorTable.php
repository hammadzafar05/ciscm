<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/5/2017
 * Time: 11:33 AM
 */

namespace App\V2\Model;


use App\Course;
use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SessionInstructorTable extends BaseTable {

    protected $tableName = 'admin_course';
    //protected $primary = 'course_instructor_id';
    protected $timeStamp = false;

    public function clearSessionRecords($id){
        return $this->tableGateway->delete(['course_id'=>$id]);
    }

    public function getSessionRecords($id){
        $select = new Select($this->tableName);
        $select->where(['course_id'=>$id]);
        $select->join($this->getPrefix().'admins',$this->tableName.'.admin_id='.$this->getPrefix().'admins.id',['about']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'admins.user_id='.$this->getPrefix().'users.id',['name','email','user_picture'=>'picture','last_name']);
        return $this->tableGateway->selectWith($select);
    }

    public function getAccountRecords($accountId){
        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.admin_id'=>$accountId])
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['id','name','end_date','start_date','enabled','type'])
                ->limit(3000);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    public function isInstructor($sessionId,$accountId){
        if(Course::where('id',$sessionId)->where('admin_id',$accountId)->count() > 0){
            return true;
        }
        else{
            return false;
        }
    }

}
