<?php


namespace App\V2\Model;

use App\QuestionBank;
use App\SurveyQuestion;
use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class QuestionBankTable extends BaseTable
{
    protected $tableName = 'question_banks';
    //protected $primary = 'survey_question_id';

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order('sort_order')
            ->where(['course_id'=>$id]);


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
	
	
	public function getPaginatedRecordsByClass($paginated=false,$id,$class_id,$question_type)
	{
		$select = new Select($this->tableName);
		$select->order('sort_order')
			->where(['course_id'=>$id,'questions_type'=>$question_type]);
		
		
		if($paginated)
		{
			$paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}

    public function getTotalQuestions($id){
        $total = $this->tableGateway->select(['course_id'=>$id])->count();
	    //$total = $this->tableGateway->count();
        return $total;
    }

    public function getLastSortOrder($surveyId){
        $row = QuestionBank::orderBy('sort_order','desc')->first();
        if($row){
            return $row->sort_order;
        }
        else{
            return 0;
        }
    }

}
