<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/5/2017
 * Time: 11:34 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class AssignmentTable extends BaseTable {

    protected $tableName = 'assignments';
    //protected $primary = 'id';
    protected $accountId = true;


    public function getPaginatedRecords($paginated=false,$sid=null)
    {
        $select = new Select($this->tableName);
        $select->join($this->getPrefix().'courses',$this->getPrefix()."{$this->tableName}.course_id=".$this->getPrefix()."courses.id",['course_name'=>'name']);

        if (isset($sid)) {
            $select->where(array($this->getPrefix().'courses.id'=>$sid));
        }
        $select->order($this->tableName.'.id desc');

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }

        if($paginated)
        {

            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getAssignmentForStudent($studentId){

    }

    public function getTotalAdminAssignments($id){
        $total = $this->tableGateway->select(['admin_id'=>$id,'due_date > '.time()])->count();

        return $total;
    }

    public function getUpcomingAssignments($days=3){
        $upperLimit = strtotime('tomorrow midnight') - 1;

        $timestamp = strtotime("+$days day");

        $timeLimit = mktime(23,59,0,date('n',$timestamp),date('j',$timestamp),date('Y',$timestamp));

        $upperLimit =Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
        $timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();
        $upperLimit = "'{$upperLimit}'";
        $timeLimit = "'{$timeLimit}'";
        $select = new Select($this->tableName);
        $select->where(['due_date < '.$timeLimit,'due_date > '.$upperLimit,'opening_date < '.time()])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
            ->order('due_date');
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }


    public function getSessionLessonAssignments($sessionId,$lessonId){

        $rowset = $this->tableGateway->select(['course_id'=>$sessionId,'lesson_id'=>$lessonId]);
        return $rowset;

    }

}
