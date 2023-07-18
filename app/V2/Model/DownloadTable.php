<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 7/27/2017
 * Time: 1:17 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class DownloadTable extends BaseTable {
    protected $tableName = 'downloads';
    //protected $primary= 'download_id';
    protected $accountId = true;


    public function getValidRecords($paginated=false)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc')
            ->where(['status'=>1]);

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
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

    public function getDownload($id,$studentId){
        $downloadSessionTable = new DownloadSessionTable();
        $totalSessions = $downloadSessionTable->getTotalForDownload($id);

        if($totalSessions>0){
            $studentSessionTable = new StudentSessionTable();
            $sessionList = $downloadSessionTable->getDownloadRecords($id);
            $status = false;
            foreach($sessionList as $row){
                if($studentSessionTable->enrolled($studentId,$row->course_id)){
                    $status = true;
                }
            }

            if($status){
                return $this->getRecord($id);
            }
            else{
                return false;
            }
        }
        else{
            return $this->getRecord($id);
        }


    }




}
