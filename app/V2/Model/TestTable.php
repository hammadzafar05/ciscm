<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/1/2017
 * Time: 1:34 PM
 */

namespace App\V2\Model;


use Application\Entity\Test;
use App\Lib\BaseTable;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class TestTable extends BaseTable{

    protected $tableName = 'tests';
    //protected $primary = 'test_id';
    protected $accountId = true;


    public function getPaginatedRecords($paginated=false,$id=null,$filter=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');

        if($this->accountId && !GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
            $select->where('('.$this->getPrefix().'tests.name LIKE \'%'.$filter.'%\')');
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


    public function getActivePaginatedRecords($paginated=false)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc')
                ->where(['enabled'=>1]);

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

    public function getLimitedRecords($limit){
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc')
            ->limit($limit);
        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
        return $this->tableGateway->selectWith($select);
    }

    public function getStudentTestRecords($studentId,$testId){
        $today = time();
        $select1 = new Select('student_courses');
        $select1->join($this->getPrefix().'course_test','student_courses.course_id=course_test.course_id',array('opening_date','closing_date'))
            ->join($this->getPrefix().'tests','course_test.test_id='.$this->getPrefix().'tests.id',['test_id'=>'id','name','enabled','minutes','allow_multiple','passmark','private','number_of_questions'])
            ->where(['student_courses.student_id'=>$studentId])
            ->where([$this->getPrefix().'tests.enabled'=>'1'])
            ->where([$this->getPrefix().'tests.id'=>$testId])
            ->columns(['course_id'])
            ->order($this->getPrefix().'tests.created_at desc');

        $rowset = $this->tableGateway->selectWith($select1);
        $rowset->buffer();
        //dd($rowset);
        return $rowset;
    }

    public function getStudentRecords($studentId){

        $today = "'".Carbon::now()->toDateTimeString()."'";
        $select1 = new Select('student_courses');
        $select1->join($this->getPrefix().'course_test',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'course_test.course_id',array())
                ->join($this->getPrefix().'tests',$this->getPrefix().'course_test.test_id='.$this->getPrefix().'tests.id',['test_id'=>'id','name','enabled','minutes','allow_multiple','passmark','private','show_result','number_of_questions'])
                ->where([$this->getPrefix().'student_courses.student_id'=>$studentId])
                ->where([$this->getPrefix().'tests.enabled'=>'1'])
                ->where($this->getPrefix()."course_test.opening_date <= $today OR ".$this->getPrefix()."course_test.opening_date=0")
                ->where($this->getPrefix()."course_test.closing_date >= $today OR ".$this->getPrefix()."course_test.closing_date=0")
                ->columns([])
                ->group($this->getPrefix().'course_test.test_id')
            ->order($this->getPrefix().'tests.created_at desc');
		/*'opening_date','closing_date'*/

        $select2 = new Select($this->tableName);
        $select2->where(['private'=>0])
               ->where([$this->getPrefix().'tests.enabled'=>'1'])
                ->columns(['test_id'=>'id','name','enabled','minutes','allow_multiple','passmark','private','show_result','number_of_questions']);

        $select1->combine($select2);

       // $sql = $select1->getSqlString($this->tableGateway->getAdapter()->getPlatform());
       // exit($sql);
        $paginatorAdapter = new DbSelect($select1,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;



    }

    public function getStudentTotalRecords($studentId){

        $today = "'".Carbon::now()->toDateTimeString()."'";
        $select1 = new Select('student_courses');
        $select1->join($this->getPrefix().'course_test',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'course_test.course_id',array())
            ->join($this->getPrefix().'tests',$this->getPrefix().'course_test.test_id='.$this->getPrefix().'tests.id',['test_id'=>'id','name','enabled','minutes','allow_multiple','passmark','private','show_result'])
            ->where([$this->getPrefix().'student_courses.student_id'=>$studentId])
            ->where([$this->getPrefix().'tests.enabled'=>'1'])
            ->where($this->getPrefix()."course_test.opening_date <= $today OR ".$this->getPrefix()."course_test.opening_date=0")
            ->where($this->getPrefix()."course_test.closing_date >= $today OR ".$this->getPrefix()."course_test.closing_date=0")
            ->columns([])
            ->group($this->getPrefix().'course_test.test_id')
            ->order($this->getPrefix().'tests.created_at desc');

        $select2 = new Select($this->tableName);
        $select2->where(['private'=>0])
            ->where([$this->getPrefix().'tests.enabled'=>'1'])
            ->columns(['test_id'=>'id','name','enabled','minutes','allow_multiple','passmark','private','show_result']);

        $select1->combine($select2);

        //  $sql = $select1->getSqlString();
        //   exit($sql);

        $rowset = $this->tableGateway->selectWith($select1);
        return $rowset->count();



    }


    public function getLastSortOrder($testId){
        $row = Test::where('id',$testId)->orderBy('sort_order','desc')->first();
        return $row->sort_order;
    }
}
