<?php
namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class SurveyResponseOptionTable extends BaseTable
{
    protected $tableName = 'survey_response_survey_option';
    //protected $primary = 'survey_response_option_id';


    public function getSurveyRecords($id)
    {
        $select = new Select($this->tableName);
        $select->where(['survey_response_id'=>$id])
            ->join($this->getPrefix().'survey_options',$this->tableName.'.survey_option_id='.$this->getPrefix().'survey_options.id')
            ->join($this->getPrefix().'survey_questions',$this->getPrefix().'survey_options.survey_question_id='.$this->getPrefix().'survey_questions.id');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    /**
     * Get the total number of responses for an option
     */
    public function getOptionCount($id){
        $select = new Select($this->tableName);
        $select->where(['survey_option_id'=>$id]);

        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->count();
    }

    /**
     * Get the total number of responses for a question
     */
    public function getQuestionCount($id){
        $select = new Select($this->tableName);
        $select->where(['survey_question_id'=>$id])
            ->join($this->getPrefix().'survey_options',$this->tableName.'.survey_option_id='.$this->getPrefix().'survey_options.id');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->count();
    }
}
