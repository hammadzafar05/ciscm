<?php
namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SurveyResponseTable extends BaseTable
{
    protected $tableName = 'survey_responses';
    //protected $primary = 'survey_response_id';

    public function getTotalForTest($id,$startDate=null,$endDate=null)
    {
        $select = new Select($this->tableName);
        $select->where(['survey_id'=>$id]);
        if($startDate){
            $select->where($this->tableName.'.created_on >= '.$startDate);
        }

        if($endDate){
            $select->where($this->tableName.'.created_on <= '.$endDate);
        }

        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();
        return $total;
    }

}
