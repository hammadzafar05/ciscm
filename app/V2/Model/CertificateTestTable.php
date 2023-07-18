<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 6/13/2017
 * Time: 1:55 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class CertificateTestTable extends BaseTable {

    protected $tableName = 'certificate_test';
    protected $timeStamp = false;
    //protected $primary = 'certificate_id';

    public function clearCertificateRecords($id){
        return $this->tableGateway->delete(['certificate_id'=>$id]);
    }

    public function getCertificateRecords($id){

        $select = new Select($this->tableName);
        $select->where(['certificate_id'=>$id])
            ->join($this->getPrefix().'tests',$this->tableName.'.test_id='.$this->getPrefix().'tests.id',['name','passmark']);
        return $this->tableGateway->selectWith($select);
    }

    public function getCertificateTests($id){
        return $this->tableGateway->select(['certificate_id'=>$id]);
    }

    public function getTotalForCertificate($certificateId){
        $total = $this->tableGateway->select(['certificate_id'=>$certificateId])->count();
        return $total;

    }


}
