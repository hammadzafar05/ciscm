<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/5/2017
 * Time: 11:33 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class BookmarkTable extends BaseTable {

    protected $tableName = 'bookmarks';
    //protected $primary = 'bookmark_id';

    public function addBookMark($studentId,$lecturePageId,$sessionId){
        $total = $this->tableGateway->select(['student_id'=>$studentId,'lecture_page_id'=>$lecturePageId,'course_id'=>$sessionId])->count();
        if(empty($total)){
            $data = [
                'student_id'=>$studentId,
                'lecture_page_id'=>$lecturePageId,
                'course_id'=>$sessionId,
            ];
            return $this->addRecord($data);
        }
        else{
            return false;
        }

    }

    public function getPaginatedStudentRecords($paginated=false,$studentId)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(['student_id'=>$studentId]);
        $select->join($this->getPrefix().'lecture_pages',$this->tableName.'.lecture_page_id='.$this->getPrefix().'lecture_pages.id',['page_title'=>'title','page_content'=>'content'])
            ->join($this->getPrefix().'lectures',$this->getPrefix().'lecture_pages.lecture_id='.$this->getPrefix().'lectures.id',['lecture_title'=>'title','lecture_id'=>'id'])
                ->join($this->getPrefix().'lessons',$this->getPrefix().'lectures.lesson_id='.$this->getPrefix().'lessons.id',['lesson_name'=>'name','description','lesson_id'=>'id'])
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);


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
