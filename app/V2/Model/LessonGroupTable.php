<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 9/7/2017
 * Time: 1:47 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class LessonGroupTable extends BaseTable{

    protected $tableName='lesson_groups';
    //protected $primary = 'lesson_group_id';

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order('sort_order asc');

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getLimitedRecords($limit)
    {
        $select = new Select($this->tableName);
        $select->order('sort_order asc');
        $select->limit($limit);


        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }



}
