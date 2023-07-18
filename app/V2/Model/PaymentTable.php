<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/7/2017
 * Time: 3:54 PM
 */

namespace App\V2\Model;
use Laminas\Db\Sql\Expression;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class PaymentTable extends BaseTable {

    protected $tableName='payments';
    //protected $primary='payment_id';

    public function getPaymentPaginatedRecords($paginated=false,$startDate=null,$endDate=null)
    {
        $select = new Select($this->tableName);
        $select->order('id desc')
            ->join($this->getPrefix().'students',$this->tableName.'.student_id=student.student_id',['first_name','last_name','email'])
            ->join($this->getPrefix().'payment_methods',$this->tableName.'.payment_method_id=payment_method.payment_method_id',['payment_methods']);

        if(!empty($startDate)){
            $select->where(['added_on >= '.$startDate]);
        }

        if(!empty($endDate)){
            $select->where(['added_on <= '.$endDate]);
        }


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getSum($startDate=null,$endDate=null){
        $select= new Select($this->tableName);
        $select->columns(['total'=>new Expression('sum(amount)')]);
        if(!empty($startDate)){
            $select->where(['added_on >= '.$startDate]);
        }

        if(!empty($endDate)){
            $select->where(['added_on <= '.$endDate]);
        }

        $row = $this->tableGateway->selectWith($select)->current();
        return $row->total;
    }
}
