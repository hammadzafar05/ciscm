<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 4/9/2018
 * Time: 4:04 PM
 */

namespace App\V2\Model;


use Application\Entity\Account;
use Application\Entity\ForumTopic;
use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class ForumTopicTable extends BaseTable {
    protected $tableName = 'forum_topics';
    public $total;

    public function getTopicsForSession($sessionId){

        $select = new Select($this->tableName);
        $select->where(['course_id'=>$sessionId])
                ->order($this->primary.' desc');

        $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;

    }

    public function getTopicsForAdmin($adminId,$sessionId=null){

        if(GLOBAL_ACCESS){
            /*
            $select = new Select($this->tableName);
            $select->order($this->primary.' desc');
            $topics = ForumTopic::orderBy($this->primary,'desc')->paginate(20);
            return $topics;
            */
            $select = new Select($this->tableName);
            $select->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);
            if(!empty($sessionId)){
                $select->where([$this->tableName.'.course_id'=>$sessionId]);
            }
            $select->order($this->primary.' desc');
        }
        else{
            //get topics for session created by admin
            $select = new Select($this->tableName);
            $select->order($this->primary.' desc')
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
                ->where([$this->getPrefix().'courses.admin_id'=>$adminId])
                ->columns(['title','id','user_id','created_at','course_id','lecture_id']);

            if(!empty($sessionId)){
                $select->where([$this->tableName.'.course_id'=>$sessionId]);
            }
            //get topics for sessions where admin is instructor
            $select2 = new Select($this->tableName);
            $select2->order($this->primary.' desc')
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
                ->join($this->getPrefix().'admin_course',$this->tableName.'.course_id='.$this->getPrefix().'admin_course.course_id',[])
                ->where(['admin_course.admin_id'=>$adminId])
                ->columns(['title','id','user_id','created_at','course_id','lecture_id']);
            if(!empty($sessionId)){
                $select2->where([$this->tableName.'.course_id'=>$sessionId]);
            }
            $select->combine($select2);

        }

        $newSelect = $select;
        $this->total= $this->tableGateway->selectWith($newSelect)->count();
        $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

}
