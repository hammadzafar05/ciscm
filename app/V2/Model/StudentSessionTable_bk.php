<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/10/2016
 * Time: 8:39 PM
 */

namespace App\V2\Model;


use App\Course;
use App\Lib\BaseTable;
use App\StudentCourse;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class StudentSessionTable extends BaseTable {

    protected $tableName = 'student_courses';
    //protected $primary = 'student_course_id';

    public function getTotalDistinctStudents(){

        $select = new Select($this->tableName);
        $select->columns(array(new Expression('DISTINCT(student_id) as id')));


        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();
        return $total;
    }

    public function getTotalActiveStudents(){
        $timeLimit = time() - (86400 * 30);
        $timeString = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();
        $select = new Select($this->tableName);
        $select->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id',['mobile_number']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled','last_seen']);

        $select->where("last_seen >= '{$timeString}'");
        $select->columns(array(new Expression('DISTINCT(student_courses.student_id) as id')));


        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();
        return $total;
    }


    public function getActiveStudents()
    {
        $timeLimit = time() - (86400 * 30);
        $timeString = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();

        $select = new Select($this->tableName);
        $select->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id',['mobile_number']);
        $select->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled','last_seen']);
        $select->where("last_seen >= '{$timeString}'");
        $select->group('student_id');



        $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }


    public function getRecord($id){

        $select = new Select($this->tableName);
        $select->where([$this->tableName.'.'.$this->primary=>$id])
                ->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id')
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','last_name','email'])
                ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name']);
        $row = $this->tableGateway->selectWith($select)->current();
        return $row;
   }

    public function getTotalForSession($id){
        return Course::find($id)->studentCourses()->count();
   /*     $select = new Select($this->tableName);
        $select->where(array('course_id'=>$id));
        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();
        return $total;*/
    }

    public function getTotalForStudent($id){
        $select = new Select($this->tableName);
        $select->where(array('student_id'=>$id));
        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();
        return $total;
    }

    public function enrolled($studentId,$sessionId){
        if(empty($studentId)){
            return false;
        }
        $select = new Select($this->tableName);
        $select->where(array('student_id'=>$studentId,'course_id'=>$sessionId));
        $rowset = $this->tableGateway->selectWith($select);

        $total = $rowset->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function unenroll($studentId,$sessionId){
        $select = new Select($this->tableName);
        $total = $this->tableGateway->delete(array('student_id'=>$studentId,'course_id'=>$sessionId));

        return $total;
    }

    public function addRecord($data){

        //check for limit
        if(defined('STUDENT_LIMIT') &&  STUDENT_LIMIT > 0 ){
            $enrolled = $this->getTotalActiveStudents();
            if($enrolled >= STUDENT_LIMIT){
                return false;
            }
        }

        $select = new Select($this->tableName);
        $select->where(array('student_id'=>$data['student_id'],'course_id'=>$data['course_id']));
        $rowset = $this->tableGateway->selectWith($select);
        $total = $rowset->count();

        if(empty($total)){
            $this->tableGateway->insert($data);

        }
    }

    public function getSessionRecords($paginated=false,$id,$alpha=false)
    {
        $select = new Select($this->tableName);
        if($alpha){
            $select->order('name asc');
        }
        else{
            $select->order($this->primary.' desc');
        }

        $select->where(array($this->getPrefix().'student_courses.course_id'=>$id))
            ->join($this->getPrefix().'courses',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'courses.id',['course_name'=>'name'])
            ->join($this->getPrefix().'students',$this->getPrefix().'student_courses.student_id=students.id',array('user_id','mobile_number'))
            ->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','last_name','email']);

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getCertificates($paginated=false,$studentId)
    {
        $select = new Select($this->tableName);

        $select->where(array($this->getPrefix().'student_courses.student_id'=>$studentId,$this->getPrefix().'certificates.enabled'=>1))
            ->join($this->getPrefix().'certificates',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'certificates.course_id',['certificate_name'=>'name','certificate_id'=>'id','orientation','description'],'left')
            ->join($this->getPrefix().'courses',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'courses.id',array('name'));

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }


    public function getTotalCertificates($studentId)
    {
        $select = new Select($this->tableName);

        $select->where(array($this->getPrefix().'student_courses.student_id'=>$studentId,$this->getPrefix().'certificates.enabled'=>1))
            ->join($this->getPrefix().'certificates',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'certificates.course_id',['name','id','orientation','description'],'left')
            ->join($this->getPrefix().'courses',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'courses.id',array('name'));


        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->count();
    }


    public function getForumTopics($paginated=false,$studentId)
    {

        $select = new Select($this->tableName);

        $select->where(array($this->getPrefix().'student_courses.student_id'=>$studentId,$this->getPrefix().'forum_topics.id > 0'))
            ->join($this->getPrefix().'forum_topics',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'forum_topics.course_id',['forum_topic_id'=>'id','title','forum_created_on'=>'created_at','user_id'],'left')
            ->join($this->getPrefix().'courses',$this->getPrefix().'student_courses.course_id='.$this->getPrefix().'courses.id',array('name','type'));

      //  dd($select->getSqlString($this->tableGateway->getAdapter()->getPlatform()));

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }


    public function hasCertificate($studentId,$certificateId){

        $select = new Select($this->tableName);

        $select->where(array('student_courses.student_id'=>$studentId,'certificate_id'=>$certificateId))
            ->join($this->getPrefix().'certificates','student_courses.course_id=certificate.course_id',null,'left');
        $resultSet = $this->tableGateway->selectWith($select);
        $total = $resultSet->count();

        if(empty($total)){
         return false;
        }
        else{
            return true;
        }

    }

    public function getStudentRecords($paginated=false,$id)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(array('student_id'=>$id))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name','start_date','enabled','end_date','description','venue','enrollment_closes','enable_discussion','type','short_description','picture','fee','admin_id','payment_required','enable_chat','enforce_order','enable_forum')) ;


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }


    public function getStudentForumRecords($paginated=false,$id)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(array('student_id'=>$id,'enable_forum'=>1,$this->getPrefix().'courses.enabled'=>1))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name','start_date','enabled','end_date','description','venue','enrollment_closes','enable_discussion','type','picture')) ;


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getTotalStudentForumRecords($id)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->where(array('student_id'=>$id,'enable_forum'=>1,$this->getPrefix().'courses.enabled'=>1))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name','start_date','enabled','end_date','description','venue','enrollment_closes','enable_discussion','type')) ;


        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->count();
    }


    public function getSessionInstructors($id){


        $select = new Select($this->tableName);
        $select->order($this->getPrefix().'users.name');
        $select->where(array($this->tableName.'.student_id'=>$id))
            ->join($this->getPrefix().'admin_course',$this->tableName.'.course_id='.$this->getPrefix().'admin_course.course_id',array('admin_id'))
            ->join($this->getPrefix().'admins',$this->getPrefix().'admin_course.admin_id='.$this->getPrefix().'admins.id',array('about'))
            ->join($this->getPrefix().'users',$this->getPrefix().'admins.user_id='.$this->getPrefix().'users.id',['first_name'=>'name','last_name','email','picture'])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name','enable_discussion')) ;

        $select->group('admin_course.admin_id');
        $select->where([$this->getPrefix().'users.enabled'=>1]);

       //exit($select->getSqlString($this->tableGateway->getAdapter()->getPlatform()));
        $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;

    }

    public function getAssignments($id){

        $select = new Select($this->tableName);

        $now = Carbon::now()->toDateString();


        $select->order($this->getPrefix().'assignments.created_at desc');
        $select->where(array('student_courses.student_id'=>$id,"(due_date > '$now' OR allow_late='1')","opening_date < '$now'",'schedule_type'=>'s'))
            ->join($this->getPrefix().'assignments',$this->tableName.'.course_id='.$this->getPrefix().'assignments.course_id',array('title','instruction','allow_late','due_date','created_at','assignment_type'=>'type','passmark','admin_id','assignment_id'=>'id'))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->columns([]);


        $select2 = new Select('attendances');
        $select2->where(array('attendances.student_id'=>$id,'assignments.schedule_type'=>'c'))
            ->join($this->getPrefix().'assignments',$this->getPrefix().'attendances.lesson_id='.$this->getPrefix().'assignments.lesson_id AND '.$this->getPrefix().'attendances.course_id='.$this->getPrefix().'assignments.course_id',['title','instruction','allow_late','due_date','created_at','assignment_type'=>'type','passmark','admin_id','assignment_id'=>'id'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'attendances.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->columns([]);


        $select->combine($select2);


            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;

    }

    public function getTotalAssignments($id){

   /*     $select = new Select($this->tableName);
        $select->order('due_date desc');
        $now = time();
        $select->where(array('student_id'=>$id,"(due_date > $now OR allow_late='1')",'opening_date < '.time(),'schedule_type'=>'s'))
            ->join($this->getPrefix().'assignments',$this->tableName.'.course_id=assignments.course_id',array('title','instruction','due_date','created_at','assignment_type'=>'type','passmark','admin_id','assignment_id'))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name')) ;*/

        $select = new Select($this->tableName);

        $now = Carbon::now()->toDateString();

        $select->order($this->getPrefix().'assignments.created_at desc');
        $select->where(array('student_courses.student_id'=>$id,"(due_date > $now OR allow_late='1')",'opening_date < '.$now,'schedule_type'=>'s'))
            ->join($this->getPrefix().'assignments',$this->tableName.'.course_id='.$this->getPrefix().'assignments.course_id',array('title','instruction','allow_late','due_date','created_at','assignment_type'=>'type','passmark','admin_id','assignment_id'=>'id'))
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->columns([]);


        $select2 = new Select('attendances');
        $select2->where(array('attendances.student_id'=>$id,'assignments.schedule_type'=>'c'))
            ->join($this->getPrefix().'assignments',$this->getPrefix().'attendances.lesson_id='.$this->getPrefix().'assignments.lesson_id AND '.$this->getPrefix().'attendances.course_id='.$this->getPrefix().'assignments.course_id',['title','instruction','allow_late','due_date','created_at','assignment_type'=>'type','passmark','admin_id','assignment_id'=>'id'])
            ->join($this->getPrefix().'courses',$this->getPrefix().'attendances.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->columns([]);


        $select->combine($select2);



        $total = $this->tableGateway->selectWith($select)->count();
        return $total;

    }

    public function getDownloads($id){
        $select = new Select($this->tableName);
        $select->order($this->getPrefix().'course_download.id desc');
        $select->where(array('student_id'=>$id,'downloads.enabled'=>1))
            ->join($this->getPrefix().'course_download',$this->tableName.'.course_id=course_download.course_id',[])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('course_name'=>'name'))
            ->join($this->getPrefix().'downloads',$this->getPrefix().'course_download.download_id='.$this->getPrefix().'downloads.id',array('download_id'=>'id','download_name'=>'name'));
        $select->group('downloads.id');
        $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;

    }

    public function getDownloadsTotal($id){

        $select = new Select($this->tableName);
        $select->order($this->getPrefix().'course_download.id desc');
        $select->where(array('student_id'=>$id,'downloads.enabled'=>1))
            ->join($this->getPrefix().'course_download',$this->tableName.'.course_id=course_download.course_id',[])
            ->join($this->getPrefix().'courses',$this->tableName.'.course_id='.$this->getPrefix().'courses.id',array('name'))
            ->join($this->getPrefix().'downloads',$this->getPrefix().'course_download.download_id='.$this->getPrefix().'downloads.id',array('download_id'=>'id','download_name'=>'name'));


        return $this->tableGateway->selectWith($select)->count();

    }


    public function markCompleted($studentId,$sessionId){
        $this->tableGateway->update([
            'completed'=>1
        ],[
            'student_id'=>$studentId,
            'course_id'=>$sessionId
        ]);
    }
}
