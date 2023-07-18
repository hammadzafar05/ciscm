<?php

namespace App\V2\Model;

use App\Lib\BaseTable;
use App\Lib\UtilityFunctions;
use App\Student;
use App\User;
use Application\Model\Parents;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;



class StudentTable extends BaseTable {



	protected  $tableName = 'students';
	protected  $primary = 'student_id';

	public function getStudents($paginated=false,$filter=null,$users_ids=null)
	{
		$select = new Select($this->tableName);
        $select->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name']);
		if ($users_ids) {
			$select->where('user_id in ('.implode(',',$users_ids).')');
		}
		if(isset($filter))
		{
			$filter = addslashes($filter);
			//$select->where('(student.first_name LIKE \'%'.$filter.'%\' OR student.last_name LIKE \'%'.$filter.'%\' OR student.email LIKE \'%'.$filter.'%\')');
           $select->where("MATCH (name,last_name,email) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");

        }
        else{
            $select->order('id desc');
        }
        //exit($select->getSqlString());
        //exit($select->getSqlString($this->tableGateway->getAdapter()->getPlatform()));
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



	public function saveStudent(Student $student)
	{

		$fields = getObjectProperties($student);

		$data =array();
		foreach ($fields as $key=>$value)
		{
			$data[$key] = $value;
		}

		$id = (int)$student->id;
		if ($id == 0) {

			$data['student_created'] = time();
			//add student
			$id = $this->addRecord($data);
		}
		else {
			//update student
			if ($this->getStudent($id)) {
				$this->tableGateway->update($data, array('student_id' => $id));
			} else {
				throw new \Exception('Student ID does not exist');
			}
		}

		return $id;
	}

	public function getStudent($id)
	{
        return $this->getRecord($id);
	}


	public function getStudentWithEmail($email)
	{
	    $row = User::where('email',$email)->first();
	    $row = $row->student;
		if (!$row) {
			throw new \Exception("Could not find row $email");
		}
		return $row;
	}



	public function emailExists($email)
	{
	    if (User::where('email',$email)->first()){
	        return true;
        }
	    else{
	        return false;
        }

	}

    public function activeEmailExists($email){
        $rowset = $this->tableGateway->select(array('email'=>$email,'status'=>1));
        $total = $rowset->count();
        if (empty($total)) {
            return false;
        }
        else {
            return true;
        }
    }


	public function usernameExists($username)
	{
		$rowset = $this->tableGateway->select(array('username'=>$username));
		$total = $rowset->count();
		if (empty($total)) {
			return false;
		}
		else {
			return true;
		}
	}

    public function getRecord($id)
    {
        $select = new Select($this->tableName);
        $select->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled']);
        $select->where([$this->tableName.'.id'=>$id]);
        $row = $this->tableGateway->selectWith($select)->current();
        return $row;
    }
	
	public function getStudentIdWithDetails($id)
	{
		$select = new Select($this->tableName);
		$select->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled']);
		$select->where([$this->tableName.'.user_id'=>$id]);
		$row = $this->tableGateway->selectWith($select)->current();
		return $row;
	}
	
	public function getStudentDetails($id)
	{
		/*$select = new Select($this->tableName);
		$select->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled']);
		$select->where(['users.id'=>$id]);
		$row = $this->tableGateway->selectWith($select)->current();
		return $row;*/
		
		$row = User::where('id',$id)->first();
		$row = $row->student;
		if (!$row) {
			return '0';
		}
		return $row;
	}


}

?>
