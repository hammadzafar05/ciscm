<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/24/2017
 * Time: 2:21 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class TransactionTable extends BaseTable {

    protected $tableName = 'transactions';
    //protected $primary = 'transaction_id';

    public function transactionExists($tid){
        $total = $this->tableGateway->select(['transaction_id'=>$tid])->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->join($this->getPrefix().'students',$this->tableName.'.student_id=student.student_id',['first_name','last_name','email'])
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'])
                ->join($this->getPrefix().'payment_methods',$this->tableName.'.payment_method_id=payment_method.payment_method_id',['payment_methods']);

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
