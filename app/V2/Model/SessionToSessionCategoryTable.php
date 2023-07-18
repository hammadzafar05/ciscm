<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 9/7/2017
 * Time: 1:40 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SessionToSessionCategoryTable extends BaseTable {

    protected $tableName = 'course_course_category';
    //protected $primary = 'course_course_category_id';
    protected $timeStamp = false;


    public function clearSessionRecords($id){
        return $this->tableGateway->delete(['course_id'=>$id]);
    }

    public function getSessionRecords($id){
        $select = new Select($this->tableName);
        $select->where(['course_id'=>$id]);
        $select->join($this->getPrefix().'course_categories',$this->tableName.'.course_category_id=course_categories.id',['name']);

        return $this->tableGateway->selectWith($select);
    }

}
