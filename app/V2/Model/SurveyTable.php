<?php

namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class SurveyTable extends BaseTable{

    protected $tableName = 'surveys';
    //protected $primary = 'survey_id';
    protected $accountId = true;


    public function getPaginatedRecords($paginated=false,$id=null,$filter=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');

        if($this->accountId && !GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
            $select->where('(survey.name LIKE \'%'.$filter.'%\')');
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

    public function getStudentSurveyRecords($studentId,$surveyId){
        $today = time();
        $select1 = new Select('student_courses');
        $select1->join($this->getPrefix().'course_survey','student_courses.course_id=course_survey.course_id',array())
            ->join($this->getPrefix().'surveys','course_survey.survey_id='.$this->getPrefix().'surveys.id',['survey_id'=>'id','name','enabled','private'])
            ->where(['student_courses.student_id'=>$studentId])
            ->where([$this->getPrefix().'surveys.enabled'=>'1'])
            ->where([$this->getPrefix().'surveys.id'=>$surveyId])
            ->columns(['course_id'])
            ->order($this->getPrefix().'surveys.created_at desc');

        $rowset = $this->tableGateway->selectWith($select1);
        $rowset->buffer();
        return $rowset;
    }

    public function getStudentRecords($studentId){

        $today = time();
        $select1 = new Select('student_courses');
        $select1->join($this->getPrefix().'course_survey',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'course_survey.course_id',array())
            ->join($this->getPrefix().'surveys','course_survey.survey_id='.$this->getPrefix().'surveys.id',['survey_id'=>'id','name','enabled','private','hash'])
            ->where([$this->getPrefix().'student_courses.student_id'=>$studentId])
            ->where([$this->getPrefix().'surveys.enabled'=>'1'])
            ->columns([])
            ->group('course_survey.survey_id')
            ->order($this->getPrefix().'surveys.created_at desc');

        $select2 = new Select($this->tableName);
        $select2->where(['private'=>0])
            ->where([$this->getPrefix().'surveys.enabled'=>'1'])
            ->columns(['survey_id'=>'id','name','enabled','private','hash']);

        $select1->combine($select2);

        //  $sql = $select1->getSqlString();
        //   exit($sql);
        $paginatorAdapter = new DbSelect($select1,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;



    }



}

?>
