<?php
namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SessionSurveyTable  extends BaseTable
{

    protected $tableName = 'course_survey';
    protected $timeStamp = false;
    //protected $primary = 'course_survey_id';


    public function getSessionRecords($id){

        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.course_id'=>$id])
            ->join($this->getPrefix().'surveys',$this->getPrefix().'course_survey.survey_id='.$this->getPrefix().'surveys.id',['name','survey_id','admin_id','survey_status'=>'status','private'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'course_survey.course_id='.$this->getPrefix().'courses.id',['course_name'])
            ->order($this->tableName.'.opening_date');

        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }

    public function getSurveyRecords($id){
        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.survey_id'=>$id])
            ->join($this->getPrefix().'surveys',$this->getPrefix().'course_survey.survey_id='.$this->getPrefix().'surveys.id',['name'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'course_survey.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
            ->order($this->getPrefix().'courses.name');

        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;
    }


    public function getUpcomingSurveys($days){
        $upperLimit = strtotime('tomorrow midnight') - 1;

        $timestamp = strtotime("+$days day");

        $timeLimit = mktime(23,59,0,date('n',$timestamp),date('j',$timestamp),date('Y',$timestamp));
        $upperLimit = "'{$upperLimit}'";
        $timeLimit = "'{$timeLimit}'";

        $select = new Select($this->tableName);
        $select->where(['opening_date < '.$timeLimit,'opening_date > '.$upperLimit])
            ->where(['opening_date > 0'])
            ->join($this->getPrefix().'surveys','course_survey.survey_id='.$this->getPrefix().'surveys.id',['name'])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'])
            ->order('opening_date');
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }


}
