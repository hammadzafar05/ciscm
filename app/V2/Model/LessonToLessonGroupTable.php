<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 9/7/2017
 * Time: 1:48 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class LessonToLessonGroupTable extends BaseTable {

    protected $tableName = 'lesson_lesson_group';
    //protected $primary = 'lesson_to_lesson_group_id';

    public function clearLessonRecords($id){
        return $this->tableGateway->delete(['lesson_id'=>$id]);
    }

    public function getLessonRecords($id){
        $select = new Select($this->tableName);
        $select->where(['lesson_id'=>$id]);
        $select->join($this->getPrefix().'lesson_groups',$this->tableName.'.lesson_group_id=lesson_groups.id',['group_name'=>'name']);

        return $this->tableGateway->selectWith($select);
    }

}
