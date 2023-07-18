<?php

namespace App;

use App\Lib\BaseTable;
use Illuminate\Database\Eloquent\Model;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class QuestionBankOption extends BaseTable
{
	protected $tableName = 'question_bank_options';
    protected $fillable= ['question_id','option'];
	
	public function getTotalOptions($id){
		$total = $this->tableGateway->select(['question_id'=>$id])->count();
		return $total;
	}
	
	public function getOptionRecords($id){
		$rowset = $this->tableGateway->select(['question_id'=>$id]);
		return $rowset;
	}
	
	
	
	public function getOptionRecordsPaginated($paginated=false,$id=null)
	{
		$select = new Select($this->tableName);
		$select->order($this->primary.' desc');
		$select->where(['question_id'=>$id]);
		
		if($paginated)
		{
			$paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	
	
	public function clearIsCorrect($id){
		$this->tableGateway->update(['is_correct'=>0],['question_id'=>$id]);
	}
	
    public function questionBank(){
        return $this->belongsTo(QuestionBank::class);
    }

}
