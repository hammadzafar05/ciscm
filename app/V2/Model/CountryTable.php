<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/18/2017
 * Time: 4:03 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class CountryTable extends BaseTable {

    protected $tableName = 'countries';
    //protected $primary = 'country_id';

    public function getRecords(){
        $select = new Select($this->tableName);
        $select->order('name');
        return $this->tableGateway->selectWith($select);
    }

}
