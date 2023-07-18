<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 7/27/2017
 * Time: 1:18 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class DownloadSessionTable extends BaseTable {

    protected $tableName = 'course_download';
    protected $timeStamp = false;
    //protected $primary = 'download_course_id';

    public function getDownloadRecords($id){

        $select = new Select($this->tableName);
        $select->where(['download_id'=>$id]);
        $select->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);
        $select->order('download_id desc');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    public function getTotalForDownload($id){
        $rowset = $this->tableGateway->select(['download_id'=>$id]);
        $count = $rowset->count();
        return $count;
    }

    public function sessionExists($id,$sessionId){
        $total = $this->tableGateway->select(['download_id'=>$id,'course_id'=>$sessionId])->count();
        return !empty($total);
    }

    public function getSessionRecords($id){
        $select = new Select($this->tableName);
        $select->where(['course_id'=>$id,$this->getPrefix().'downloads.enabled'=>1])
            ->join($this->getPrefix().'downloads',$this->tableName.'.download_id='.$this->getPrefix().'downloads.id',['name']);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }
}
