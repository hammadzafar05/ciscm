<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/27/2017
 * Time: 10:38 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class RoleTable extends BaseTable {

    protected $tableName='roles';
    //protected $primary='admin_role_id';

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary);

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
