<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/5/2017
 * Time: 11:34 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class AssignmentSubmissionTable extends BaseTable {

    protected $tableName = 'assignment_submissions';
    //protected $primary = 'assignment_submission_id';



    public function getAssignmentPaginatedRecords($paginated=false,$id,$submitted=1)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where([$this->tableName.'.assignment_id'=>$id])
            ->join($this->getPrefix().'students',$this->tableName.'.student_id=students.id',['mobile_number'])
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email'])
            ->join($this->getPrefix().'assignments',$this->tableName.'.assignment_id='.$this->getPrefix().'assignments.id',['title','instruction','passmark','course_id','due_date'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'assignments.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);

        if($submitted){
            $select->where(['submitted'=>$submitted]);
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


    public function getTotalForAssignment($id){
        $total= $this->tableGateway->select(['assignment_id'=>$id])->count();
        return $total;
    }

    public function getTotalSubmittedForAssignment($id){
        $total= $this->tableGateway->select(['assignment_id'=>$id,'submitted'=>1])->count();
        return $total;
    }

    public function getAverageScore($id){
        $select= new Select($this->tableName);
        $select->where(['assignment_id'=>$id,'submitted'=>1])
            ->columns(['total'=>new Expression('avg(grade)')]);
        $row = $this->tableGateway->selectWith($select)->current();
        return floor($row->total);
    }


    public function getTotalPassedForAssignment($id,$grade)
    {
        $total = $this->tableGateway->select(['assignment_id'=>$id,'grade >= '.$grade])->count();
        return $total;
    }

    public function getTotalFailedForAssignment($id,$grade)
    {
        $total = $this->tableGateway->select(['assignment_id'=>$id,'grade < '.$grade])->count();
        return $total;
    }

    public function getTotalPassed($id,$passmark){
        $total = $this->tableGateway->select(['assignment_id'=>$id,'grade >= '.$passmark])->count();
        return $total;
    }

    public function passedAssignment($studentId,$testId){
        $testTable = new AssignmentTable();
        $testRow = $testTable->getRecord($testId);
        $total = $this->tableGateway->select(['assignment_id'=>$testId,'grade >= '.$testRow->passmark,'student_id'=>$studentId])->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function hasSubmission($studentId,$assignmentId){
        $total = $this->tableGateway->select(['student_id'=>$studentId,'assignment_id'=>$assignmentId])->count();
        return $total;
    }



    public function getStudentPaginatedRecords($paginated=false,$id)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(['student_id'=>$id])
            ->join($this->getPrefix().'assignments',$this->tableName.'.assignment_id='.$this->getPrefix().'assignments.id',['title','instruction','passmark','course_id','due_date'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'assignments.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);
        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getSubmission($id){
        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.id'=>$id])
            ->join($this->getPrefix().'assignments',$this->tableName.'.assignment_id='.$this->getPrefix().'assignments.id',['title','due_date','instruction','type','course_id','passmark'])
            ->join($this->getPrefix().'students',$this->tableName.'.student_id=students.id',['mobile_number'])
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email']);
        $row = $this->tableGateway->selectWith($select);
        return $row->current();
    }

    public function getAssignment($assignmentId,$studentId){
        $row = $this->tableGateway->select(['assignment_id'=>$assignmentId,'student_id'=>$studentId])->current();
        return $row;
    }


    public function getPassedPaginatedRecords($paginated=false,$id=null,$score)
    {
        $select = new Select($this->tableName);
        $select->order('grade desc')
            ->where([$this->tableName.'.assignment_id'=>$id,'grade >= '.$score])
            ->join($this->getPrefix().'students',$this->tableName.'.student_id=students.id',['mobile_number'])
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email'])
        ->join($this->getPrefix().'assignments',$this->tableName.'.assignment_id='.$this->getPrefix().'assignments.id',['passmark']);


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }


    public function getFailPaginatedRecords($paginated=false,$id=null,$score)
    {
        $select = new Select($this->tableName);
        $select->order('grade desc')
            ->where([$this->tableName.'.assignment_id'=>$id,'grade < '.$score])
            ->join($this->getPrefix().'students',$this->tableName.'.student_id=students.id',['mobile_number'])
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email'])
        ->join($this->getPrefix().'assignments',$this->tableName.'.assignment_id='.$this->getPrefix().'assignments.id',['passmark']);


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }



}
