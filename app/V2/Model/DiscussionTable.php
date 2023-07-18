<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/8/2017
 * Time: 2:46 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class DiscussionTable extends BaseTable {

    protected $tableName = 'discussions';
    //protected $primary = 'discussion_id';

    public function getTotalDiscussions($replied=0){
        return $this->tableGateway->select(['replied'=>$replied])->count();
    }
    public function getPaginatedRecordsForStudent($paginated=false,$id,$sessionId=null,$lectureId=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc')
            ->where(['student_id'=>$id]);
        if(!empty($sessionId)){
            $select->where(['course_id'=>$sessionId]);
        }

        if(!empty($lectureId)){
            $select->where(['lecture_id'=>$lectureId]);
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

    public function getRecord($id){
        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.'.$this->primary=>$id]);
      //  $select->order($this->primary.' desc');
        $select->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id',['mobile_number']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled','last_seen','role_id']);

        $row= $this->tableGateway->selectWith($select)->current();
        return $row;


    }

    public function getDiscussRecords($paginated=false,$replied=null)
    {
        if(GLOBAL_ACCESS){
            $select = new Select($this->tableName);
        }
        else{
            $select = new Select('admin_discussion');
            $select->join($this->getPrefix().'discussions',$this->getPrefix().'admin_discussion.discussion_id=discussions.id',['id','student_id','subject','question','created_at','replied','course_id','lecture_id','admin'])
                ->where(['admin_discussion.admin_id'=>ADMIN_ID]);
        }

        $select->order($this->tableName.'.id desc');
         $select->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id',['mobile_number']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled','last_seen']);

        if(isset($replied)){
            $select->where(['replied'=>$replied]);
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

}
