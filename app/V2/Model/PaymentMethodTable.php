<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/19/2017
 * Time: 4:04 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class PaymentMethodTable extends BaseTable {

    protected $tableName = 'payment_methods';
    //protected $primary = 'payment_method_id';

    public function getMethodWithCode($code)
    {
        $row = $this->tableGateway->select(['code'=>trim($code)])->current();
        return $row;
    }

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
       $select->order('payment_methods');

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getInstalledMethods(){
        $select = new Select($this->tableName);
        $select->where(['enabled'=>1]);
        $select->order('sort_order');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    public function getMethodsForCurrency($currencyId){
        $select = new Select($this->tableName);
        $select->where(['is_global'=>1,'enabled'=>1])
            ->order('sort_order')
        ->columns(['enabled','payment_method_id'=>'id','label']);

        //get for currency
        $select1 = new Select('currency_payment_method');
        $select1->join($this->getPrefix().'payment_methods',$this->getPrefix().'currency_payment_method.payment_method_id='.$this->getPrefix().'payment_methods.id',['enabled','payment_method_id'=>'id','label']);
        $select1->columns([]);
        $select1->order('sort_order');
        $select1->where([$this->getPrefix().'payment_methods.enabled'=>1]);
        $select1->where(['currency_id'=>$currencyId]);


        $select->combine($select1);

        return $this->tableGateway->selectWith($select);
    }
}
