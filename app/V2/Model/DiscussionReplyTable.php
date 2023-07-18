<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/8/2017
 * Time: 2:47 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class DiscussionReplyTable extends BaseTable {

    protected $tableName = 'discussion_replies';
    //protected $primary = 'discussion_reply_id';


    public function getTotalReplies($id){
        return $this->tableGateway->select(['discussion_id'=>$id])->count();
    }

    public function getPaginatedRecordsForDiscussion($paginated=false,$id)
    {

        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(['discussion_id'=>$id]);
        $select->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','last_name','email']);
        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getRepliedAdmins($id){
        $select = new Select($this->tableName);
        $select->where(['discussion_id'=>$id])
            ->where(['role_id'=>1])
                ->join($this->getPrefix().'users',$this->tableName.'.user_id='.$this->getPrefix().'users.id',['name','last_name','email','role_id']);

        return $this->tableGateway->selectWith($select);
    }

}
