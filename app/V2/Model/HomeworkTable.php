<?php

namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class HomeworkTable extends BaseTable {

	protected $tableName = 'revision_notes';
	//protected $primary = 'homework_id';
    protected $accountId = true;


	public function getPaginatedRecords($paginated=false,$sid=null)
	{
		$select = new Select($this->tableName);
		$select->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->join($this->getPrefix().'lessons',$this->tableName.'.lesson_id='.$this->getPrefix().'lessons.id',['lesson_name'=>'name']);

		if (isset($sid)) {
			$select->where(array($this->getPrefix().'courses.id'=>$sid));
		}
		$select->order('id desc');

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

	public function getHomework($id)
	{

		$select = new Select($this->tableName);
		$select->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'));

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }

		$select->where(array($this->tableName.'.id'=>$id));


		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}


	public function getTotalForCategory($cid)
	{
		$rowset = $this->tableGateway->select(array('course_id'=>$cid));
        if(!GLOBAL_ACCESS){
            $rowset = $this->tableGateway->select(array('course_id'=>$cid,'admin_id'=>ADMIN_ID));
        }
		$total = $rowset->count();
		return $total;
	}



}

?>
