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

class MarkTable extends BaseTable {

    protected $tableName = 'marks';
    //protected $tableName = 'assignments';
    //protected $primary = 'id';
    protected $accountId = true;
	
	protected $fillable = ['title','status','course_id','admin_id','type','remarks'];
	
	public function recordExists($course_id){
		$total = $this->tableGateway->select(['course_id'=>$course_id])->count();
		if(empty($total)){
			return false;
		}
		else{
			return true;
		}
	}
	
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
	
	public function getTotalMarkEntry($id){
		$total = $this->tableGateway->select(['admin_id'=>$id])->count();
		
		return $total;
	}
	
	
	public function getRecordWithCourseName($id)
	{
		/*$primary = $this->getPrimary();
		if($this->accountId && !GLOBAL_ACCESS){
			$row= $this->tableGateway->select(array($primary=>$id,'admin_id'=>ADMIN_ID))->current();
		}
		else{
			$row= $this->tableGateway->select(array($primary=>$id))->current();
		}*/
		
		$select = new Select($this->tableName);
		$select->join($this->getPrefix().'courses',$this->getPrefix()."{$this->tableName}.course_id=".$this->getPrefix()."courses.id",['course_name'=>'name']);
		
		if (isset($id)) {
			$select->where(array($this->tableName.'.id'=>$id));
		}
		$select->order($this->tableName.'.id desc');
		
		if(!GLOBAL_ACCESS){
			$select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
		}

		$resultSet = $this->tableGateway->selectWith($select)->current();
		return $resultSet;
	}
}
