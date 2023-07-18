<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/12/2017
 * Time: 4:31 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class RegistrationFieldTable extends BaseTable {

    protected $tableName = 'student_fields';
    //protected $primary = 'registration_field_id';

    public function getActiveFields()
    {
        $select = new Select($this->tableName);
        $select->where(['enabled'=>1])
                ->order('sort_order');
        return $this->tableGateway->selectWith($select);
    }

    public function getAllFields(){
        $select = new Select($this->tableName);
        $select->order('sort_order');
        return $this->tableGateway->selectWith($select);
    }


}
