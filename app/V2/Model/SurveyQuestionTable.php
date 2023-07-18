<?php


namespace App\V2\Model;

use App\SurveyQuestion;
use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class SurveyQuestionTable extends BaseTable
{
    protected $tableName = 'survey_questions';
    //protected $primary = 'survey_question_id';

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order('sort_order')
            ->where(['survey_id'=>$id]);


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getTotalQuestions($id){
        $total = $this->tableGateway->select(['survey_id'=>$id])->count();
        return $total;
    }

    public function getLastSortOrder($surveyId){
        $row = SurveyQuestion::where('survey_id',$surveyId)->orderBy('sort_order','desc')->first();
        if($row){
            return $row->sort_order;
        }
        else{
            return 0;
        }
    }

}
