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
use Laminas\Db\Sql\Select;

class MarkDistributionTable extends BaseTable {

    protected $tableName = 'mark_distributions';
	
	protected $fillable = ['mark_id','student_id','attendance_mark','assignment_mark','assessment_mark','total_mark','marks'];
	
	public function getAllMarkDistribution($mark_id){
		$total = $this->tableGateway->select(['mark_id'=>$mark_id]);
		if(empty($total)){
			return false;
		}
		else{
			return $total;
		}
	}
	
	public function getAllMarkDistributionDetails($mark_id){
		$select = new Select($this->tableName);
		$select->order($this->primary.' desc');
		$select->where([$this->tableName.'.mark_id'=>$mark_id])
			->join($this->getPrefix().'students',$this->tableName.'.student_id=students.id',['mobile_number'])
			->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email'])
			->join($this->getPrefix().'marks',$this->tableName.'.mark_id='.$this->getPrefix().'marks.id',['title','status']);
		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
}
