<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/5/2017
 * Time: 11:34 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class NoticeBoard extends BaseTable {
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $tableName = 'noticeboards';
	
	
	public function getUpcomingNotices(){
		$select = new Select($this->tableName);
		$select->where(['last_date_to_display <= '.date('Y-m-d')])
			->order('last_date_to_display');
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
	}
/*
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
	}*/
	
	public function getTotalNotices($passmark){
		
		$total = $this->tableGateway->select(['id >= '.$passmark])->count();
		return $total;
		
	}
}