<?php

namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class SurveyOptionTable extends BaseTable{

    protected $tableName = 'survey_options';
    //protected $primary = 'survey_option_id';

    public function getTotalOptions($id){
        $total = $this->tableGateway->select(['survey_question_id'=>$id])->count();
        return $total;
    }

    public function getOptionRecords($id){
        $rowset = $this->tableGateway->select(['survey_question_id'=>$id]);
        return $rowset;
    }


    public function getOptionRecordsPaginated($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(['survey_question_id'=>$id]);

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

?>
