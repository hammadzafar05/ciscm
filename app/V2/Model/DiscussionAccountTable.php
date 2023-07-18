<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/24/2017
 * Time: 12:01 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class DiscussionAccountTable extends BaseTable {

    protected $tableName ='admin_discussion';
    protected $timeStamp = false;
    //protected $primary = 'discussion_admin_id';

    public function getDiscussionAccounts($id){
        $select = new Select($this->tableName);
        $select->join($this->getPrefix().'admins',$this->tableName.'.admin_id=admins.id',['about','user_id']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'admins.user_id='.$this->getPrefix().'users.id',['name','last_name','email']);
        $select->where(['discussion_id'=>$id]);
        return $this->tableGateway->selectWith($select);
    }

    public function getTotalDiscussionAccounts($id){
        return $this->tableGateway->select(['discussion_id'=>$id])->count();
    }

    public function deleteAccountRecord($discussionId,$accountId){
        $total = $this->tableGateway->delete(['discussion_id'=>$discussionId,'admin_id'=>$accountId]);
        return $total;
    }

    public function hasDiscussion($accountId,$discussionId){
        $total = $this->tableGateway->select(['admin_id'=>$accountId,'discussion_id'=>$discussionId])->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

}
