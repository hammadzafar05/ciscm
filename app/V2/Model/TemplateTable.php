<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/22/2018
 * Time: 2:14 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;

class TemplateTable extends BaseTable {

    protected $tableName = 'templates';
    //protected $primary = 'template_id';

    public function getActiveTemplate(){
        $row = $this->tableGateway->select(['active'=>1])->current();
        return $row;
    }

}
