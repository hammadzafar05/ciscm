<?php

namespace App\Http\Controllers\Admin;

use App\AssignmentSubmission;
use App\Course;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Lib\HelperTrait;
use App\Student;
use App\StudentTest;
use App\Test;
use App\V2\Model\AttendanceTable;
use App\V2\Model\SessionCategoryTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\TestGradeTable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;

class ReportController extends Controller
{
    use HelperTrait;

    public function index(Request $request){

        $table = new SessionTable();
        $attendanceTable = new AttendanceTable();
        $studentSessionTable = new StudentSessionTable();

        $filter = request()->get('filter');


        if (empty($filter)) {
            $filter=null;
        }

        $group = request()->get('group', null);
        if (empty($group)) {
            $group=null;
        }

        $sort = request()->get('sort', null);
        if (empty($sort)) {
            $sort=null;
        }

        $type = request()->get('type', null);
        if (empty($type)) {
            $type=null;
        }

        $text = new Text('filter');
        $text->setAttribute('class','form-control');
        $text->setAttribute('placeholder',__lang('Search'));
        $text->setValue($filter);

        $select = new Select('group');
        $select->setAttribute('class','form-control select2');
        $select->setEmptyOption('--'.__lang('Select Category').'--');

        $sortSelect = new Select('sort');
        $sortSelect->setAttribute('class','form-control');
        //$sortSelect->setAttribute('style','max-width:100px');
        $sortSelect->setValueOptions([
            'recent'=>__lang('Recently Added'),
            'asc'=>__lang('Alphabetical (Ascending)'),
            'desc'=>__lang('Alphabetical (Descending)'),
            'date'=>__lang('Start Date'),
            'priceAsc' =>__lang('Price (Lowest to Highest)'),
            'priceDesc' => __lang('Price (Highest to Lowest)')
        ]);
        $sortSelect->setEmptyOption('--'.__lang('Sort').'--');
        $sortSelect->setValue($sort);

        $typeSelect = new Select('type');
        $typeSelect->setAttribute('class','form-control');
        //$typeSelect->setAttribute('style','max-width:100px');
        $typeSelect->setValueOptions([
            's'=>__lang('Training Session'),
            'c'=>__lang('Online Course'),
            'b'=>__lang('training-online'),
        ]);
        $typeSelect->setEmptyOption('--'.__lang('Type').'--');
        $typeSelect->setValue($type);

        $groupTable = new SessionCategoryTable();
        $groupRowset = $groupTable->getLimitedRecords(1000);
        $options =[];

        foreach($groupRowset as $row){
            $options[$row->id] = $row->name;
        }
        $select->setValueOptions($options);
        $select->setValue($group);

        $paginator = $table->getPaginatedRecords(true,null,null,$filter,$group,$sort,$type);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);

        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Reports'),
            'attendanceTable'=>$attendanceTable,
            'studentSessionTable'=>$studentSessionTable,
            'filter'=>$filter,
            'group'=>$group,
            'text'=>$text,
            'select'=>$select,
            'sortSelect'=>$sortSelect,
            'sort'=>$sort,
            'typeSelect'=>$typeSelect,
            'type'=>$type
        ));

    }

    public function classes(Request $request,$id){

        $session = Course::findorFail($id);

        $this->data['pageTitle']=__lang('Class Report').': '.$session->name;
        $this->data['session'] = $session;
        $this->data['attendanceTable'] = new AttendanceTable();
        $this->data['sessionLessonTable'] = new SessionLessonTable();
        $this->data['id'] = $id;
        return view('admin.report.classes',$this->data);

    }

    public function students(Request $request,$id){

        $attendanceTable = new AttendanceTable();
        $this->data['rowset'] = $attendanceTable->getStudentSessionReportRecords($id);
        $this->data['id']=$id;
        $session= Course::findOrFail($id);
        $this->data['pageTitle']=__lang('Student Report').': '.$session->name;
        $this->data['session'] = $session;
        $this->data['attendanceTable'] = $attendanceTable;

        $totalLessons = $session->lessons()->count();
        if(empty($totalLessons)){
            $totalLessons= 1;
        }
        $this->data['totalSessionLessons'] = $totalLessons;



        $this->data['allTests'] = $this->getSessionTests($id);
        $this->data['controller'] = $this;
        $this->data['testGradeTable'] = new TestGradeTable();

        return view('admin.report.students',$this->data);
    }

    public function tests(Request $request,$id){

        $this->data['tests'] = $this->getSessionTestsObjects($id);
        $this->data['allTests'] = $this->getSessionTests($id);
        //get studentlist


        $this->data['controller'] = $this;
        $this->data['testGradeTable'] = new TestGradeTable();
        $this->data['pageTitle'] = __lang('Test Report').': '.Course::find($id)->name;

        $attendanceTable = new AttendanceTable();
        $this->data['rowset'] = $attendanceTable->getStudentSessionReportRecords($id);
        $this->data['session']= Course::find($id);

        return view('admin.report.tests',$this->data);
    }

    public function homework(Request $request,$id){

        $session = Course::findOrFail($id);
        $this->data['session'] = $session;

        $this->data['pageTitle'] = __lang('Homework Report').': '.$session->name;
        $this->data['controller'] = $this;
        $attendanceTable = new AttendanceTable();
        $this->data['rowset'] = $attendanceTable->getStudentSessionReportRecords($id);
        $this->data['testGradeTable'] = new TestGradeTable();


        return view('admin.report.homework',$this->data);
    }

    public function reportcard(Request $request,$id){

        $sessionId = $request->get('sessionId');
        $this->data['tests'] = $this->getSessionTestsObjects($sessionId);
        $this->data['allTests'] = $this->getSessionTests($sessionId);
        //get studentlist


        $this->data['controller'] = $this;
        $this->data['testGradeTable'] = new TestGradeTable();
        $student = Student::find($id);
        $this->data['student'] = $student;
        $this->data['session'] = Course::find($sessionId);
        $this->data['baseUrl'] = $this->getBaseUrl();
        $html = view('admin.report.reportcard',$this->data)->toHtml();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $orientation = 'portrait';

        $dompdf->setPaper('A4', $orientation);
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream(safeUrl($student->first_name.' '.$student->last_name.' report '.$this->data['session']->name).'.pdf');


        exit();

    }



    public function getStudentTestsStats($studentId){

        $totalTaken = 0;
        $scores = 0;


        foreach($this->data['allTests'] as $testId){
            $studentTest = StudentTest::where('student_id',$studentId)->where('test_id',$testId)->orderBy('score','desc')->first();
            if($studentTest){
                $totalTaken++;
                $scores += $studentTest->score;
            }
        }

        if(!empty($totalTaken)){
            return [
                'testsTaken'=>$totalTaken,
                'average' => ($scores/$totalTaken)
            ];
        }
        else{
            return [
                'testsTaken'=>$totalTaken,
                'average' => 0
            ];
        }


    }

    public function getStudentAssignmentStats($studentId){
        $session= $this->data['session'];
        $totalTaken = 0;
        $scores = 0;

        foreach($session->assignments as $assignment){
            $submission= AssignmentSubmission::where('student_id',$studentId)->where('assignment_id',$assignment->assignment_id)->orderBy('grade','desc')->first();
            if($submission){
                $totalTaken++;
                $scores+=$submission->grade;
            }
        }



        if(!empty($totalTaken)){
            return [
                'submissions'=>$totalTaken,
                'average' => ($scores/$totalTaken)
            ];
        }
        else{
            return [
                'submissions'=>$totalTaken,
                'average' => 0
            ];
        }


    }

    public function getStudentTotalPosts($studentId){
        $total = 0;

        foreach($this->data['session']->forumTopics as $topic){

            foreach($topic->forumPosts()->where('post_owner',$studentId)->where('post_owner_type','s')->get() as $row){
                $total++;
            }

        }
        return $total;
    }

    private function getSessionTests($sessionId){
        $session = Course::find($sessionId);
        //create list of tests for this session
        $allTests = [];
        foreach($session->tests as $test){
            $allTests[$test->id] = $test->id;
        }

        foreach($session->lessons as $lesson){

            if( $lesson && !empty($lesson->test_id) && !empty($lesson->test_required) && Test::find($lesson->test_id)){
                $allTests[$lesson->test_id] = $lesson->test_id;
            }

        }
        return $allTests;
    }

    private function getSessionTestsObjects($sessionId){
        $testIds = $this->getSessionTests($sessionId);
        $objects = [];
        foreach($testIds as $id)
        {
            $test = Test::find($id);
            if($test){
                $objects[] = $test;
            }
        }
        return $objects;
    }
}
