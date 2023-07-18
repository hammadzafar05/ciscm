<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 3/15/2018
 * Time: 5:44 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SmsGatewayTable extends BaseTable{

    protected $tableName = 'sms_gateways';
    //protected $primary = 'sms_gateway_id';

    public function getActiveGateway(){
        $row = $this->tableGateway->select(['active'=>1])->current();
        return $row;
    }

    public function uninstallAll(){
        $this->tableGateway->update(['active'=>0]);
    }

    public function getRecords(){
        $select = new Select($this->tableName);
        $select->order('gateway_name');
        return $this->tableGateway->selectWith($select);
    }

}
