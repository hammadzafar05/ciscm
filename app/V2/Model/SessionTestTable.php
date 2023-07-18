<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/22/2018
 * Time: 12:39 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;

class SessionTestTable extends BaseTable {

    protected $tableName = 'course_test';
    protected $timeStamp = false;
    //protected $primary = 'course_test_id';
	
	public function getSessionRecords($id){
		
		$select = new Select($this->tableName);
		$select->where([$this->tableName.'.course_id'=>$id])
			->join($this->getPrefix().'tests',$this->getPrefix().'course_test.test_id='.$this->getPrefix().'tests.id',['name','admin_id','test_status'=>'enabled','minutes','passmark','allow_multiple','exam_type'])
			->join($this->getPrefix().'courses',$this->getPrefix().'course_test.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
			->order($this->tableName.'.opening_date');
		
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
		
	}

    public function getTestRecords($id){
        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.test_id'=>$id])
            ->join($this->getPrefix().'tests','course_test.test_id=tests.id',['name'])
            ->join($this->getPrefix().'courses','course_test.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
            ->order($this->tableName.'.opening_date');

        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;
    }
	
	/*----Starting WU-71 : Written exam PDF----*/
	public function getTestDetails($id){
		$select = new Select($this->tableName);
		$select->where([$this->tableName.'.test_id'=>$id])
			->join($this->getPrefix().'tests','course_test.test_id=tests.id',['name'])
			->join($this->getPrefix().'courses','course_test.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
			->order($this->tableName.'.opening_date');
		
		$rowset = $this->tableGateway->selectWith($select)->current();
		return $rowset;
	}
	/*----ending WU-71 : Written exam PDF----*/
	
	public function getTestDate($id){
		$select = new Select($this->tableName);
		$select->where([$this->tableName.'.test_id'=>$id]);
		
		$rowset = $this->tableGateway->select($select);
		$arr['opening_date'] = '';
		$arr['closing_date'] = '';
		foreach ($rowset as $row){
			$arr['opening_date'] = $row->opening_date;
			$arr['closing_date'] = $row->closing_date;
		}
		return $arr;
	}


    public function getUpcomingTests($days){
        $upperLimit = strtotime('tomorrow midnight') - 1;

        $timestamp = strtotime("+$days day");

        $timeLimit = mktime(23,59,0,date('n',$timestamp),date('j',$timestamp),date('Y',$timestamp));

        $upperLimit = Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
        $timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();

        $upperLimit = "'{$upperLimit}'";
        $timeLimit = "'{$timeLimit}'";

        $select = new Select($this->tableName);
        $select->where(['opening_date < '.$timeLimit,'opening_date > '.$upperLimit])
            ->where(['opening_date > 0'])
            ->join($this->getPrefix().'tests',$this->getPrefix().'course_test.test_id='.$this->getPrefix().'tests.id',['name','description','passmark','minutes'])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
            ->order('opening_date');
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }

}
