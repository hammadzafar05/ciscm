<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/2/2017
 * Time: 2:42 PM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Model\SessionTestTable;
use App\V2\Model\StudentTestWrittenAnswerTable;
use Dompdf\Options;
use Illuminate\Http\Request;


use App\Lesson;
use App\Course;
use App\Student;
use App\StudentTest;
use App\Test;
use App\V2\Model\AttendanceTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\StudentTestOptionTable;
use App\V2\Model\StudentTestTable;
use App\V2\Model\TestGradeTable;
use App\V2\Model\TestOptionTable;
use App\V2\Model\TestQuestionTable;
use App\V2\Model\TestTable;
use Dompdf\Dompdf;
use App\Lib\UtilityFunctions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class TestController extends Controller {

    use HelperTrait;
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $controller->layout('layout/student');
        }, 100);
    }
    public function index(Request $request)
    {
        // TODO Auto-generated NewsController::index(Request $request) default action
        $table = new TestTable();
        //dd($table);
        $testQuestionTable = new TestQuestionTable();
        $studentTestTable = new StudentTestTable();
	    $sessionTestTable  = new SessionTestTable();
		
		
        $paginator = $table->getStudentRecords($this->getId());
//dd($paginator);
        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
// 	dd($paginator);
	    /*foreach($paginator as $row){
		    $test_details = $sessionTestTable->getTestDate($row->test_id);
		    foreach($test_details as $test) {
			    //dd($test_details);
			    //$row->opening_date = $test->opening_date;
			    array_push($row, ['opening_date' => $test->opening_date]);
		    }
	    }*/
		
		
        return viewModel('student',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Tests'),
            'studentTest'=>$studentTestTable,
            'questionTable'=>$testQuestionTable,
            'sessionTestTable'=>$sessionTestTable,
            'id'=>$this->getId()
        ));

    }

    public function taketest(Request $request,$id)
    {
        $courseTest = session('testInfo');
        if ($courseTest){
            $courseTest = unserialize($courseTest);
        }
        $output = [];
        $testTable = new TestTable();
        $testRow=$testTable->getRecord($id);
        $output['testRow'] = $testRow;
        $questionTable = new TestQuestionTable();
        $optionTable = new TestOptionTable();
        $studentTestTable = new StudentTestTable();
        $studentTestOptionTable = new StudentTestOptionTable();
        $studentSessionTable = new StudentSessionTable();
        
        $output['pageTitle'] = __lang('Take Test').': '.$output['testRow']->name;

        if($studentTestTable->hasTest($id,$this->getId()) && empty($output['testRow']->allow_multiple)){
            flashMessage(__lang('test-taken-msg'));
            return redirect()->route('student.test.index');
        }


        if(!empty($testRow->private) && !isset($courseTest[$id])){

            //get records for the student
            $rowset = $testTable->getStudentTestRecords($this->getId(),$id);
            $total = $rowset->count();


            if(empty($total)){
                flashMessage(__lang('no-test-permission'));
                return back();
            }

            //now loop rowset as see if the test is opened
            $opened = false;

            foreach($rowset as $row){
                if(($row->opening_date < Carbon::now() || $row->opening_date==0)){
                    $opened=true;
                }

            }

            $closed = false;

            foreach($rowset as $row){
                if($row->closing_date > Carbon::now() || $row->closing_date==0){
                    $closed = true;
                }

            }

            if(!($opened && $closed)){

                flashMessage(__lang('test-closed'));
               return back();
            }


        }


        $rowset = $questionTable->getPaginatedRecords(false,$id);
        $rowset->buffer();
        
        $questions = [];
        $correct = 0;
        $totalQuestions = $rowset->count();
        foreach($rowset as $row)
        {
            $questions[$row->id]['question'] = $row;
            $questions[$row->id]['options'] = $optionTable->getOptionRecords($row->id);
        }
        //dd($rowset);die;
	    shuffle($questions);
	    //dd($questions);die;
        $output['totalQuestions'] = $totalQuestions;
	    $output['exam_type'] = $output['testRow']->exam_type;
        $output['questions'] = $questions;
        $output['optionTable']= $optionTable;
        if(isset($courseTest->testInfo)){

            if(isset($courseTest[$id])){
            	
                 $output['message'] = __lang('class-test',['class'=>$courseTest[$id]['name']]);
            }
        }
        return view('student.test.taketest',$output);
    }

    public function processtest(Request $request,$id)
    {
    	/*--MARUF START--*/
	    //dd($request->all());
		/*--student/test/taketest/14--*/
	    $output = [];
        $testTable = new TestTable();
        $output['testRow'] = $testTable->getRecord($id);
	    $exam_type = $output['testRow']->exam_type;
	    
        $questionTable = new TestQuestionTable();
        $optionTable = new TestOptionTable();
        $studentTestTable = new StudentTestTable();
        $studentTestOptionTable = new StudentTestOptionTable();
	    $studentTestWrittenAnswerTable = new StudentTestWrittenAnswerTable();


        $rowset = $questionTable->getPaginatedRecords(false,$id);
        $rowset->buffer();
        $questions = [];
        $correct = 0;
        $totalQuestions = $rowset->count();
        foreach($rowset as $row)
        {
            $questions[$row->id]['question'] = $row;
            $questions[$row->id]['options'] = $optionTable->getOptionRecords($row->id);
        }

        if(request()->isMethod('post'))
        {
            $data = request()->all();
            $studentTestId = $data['student_test_id'];
            $row = $studentTestTable->getRecord($studentTestId);
            $this->validateOwner($row);
	
	        foreach($rowset as  $row)
	        {
		        if ($exam_type == 1){
			        if (!empty($data['answer_' . $row->id])) {
				        $optionId = $data['answer_' . $row->id];
				        $studentTestWrittenAnswerTable->addRecord([
					        'student_test_id' => $studentTestId,
					        'answer' => $optionId
				        ]);
			        }
			        if (!empty($data['answer_' . $row->id])) {
				        $optionLists = $optionTable->getOptionRecords($row->id);
				        $optionId = '0';
				        $written_answer = $data['answer_' . $row->id];
				        foreach ($questions[$row->id]['options'] as $option_single){
					        $optionId = $option_single->id;
				        }
				        
				        $studentTestOptionTable->addRecord([
					        'student_test_id' => $studentTestId,
					        'test_option_id' => $optionId,
					        'answer' => $written_answer
				        ]);
				        //check if option is correct
				        $optionRow = $optionTable->getOptionRecords($optionId);
			        }
		        }else {
			        if (!empty($data['question_' . $row->id])) {
				        $optionId = $data['question_' . $row->id];
				        $studentTestOptionTable->addRecord([
					        'student_test_id' => $studentTestId,
					        'test_option_id' => $optionId
				        ]);
				        //check if option is correct
				        $optionRow = $optionTable->getRecord($optionId);
				        if ($optionRow->is_correct == 1) {
					        $correct++;
				        }
				
			        }
		        }
	        }

            //calculate score
            $score = ($correct/$totalQuestions)  * 100;
	        //update
	        /*0=MCQ, 1=written*/
	        if ($exam_type == 1) {
		        $studentTestTable->update(['score' => $score, 'status' => 'Pending'], $studentTestId);
	        }else{
		        $studentTestTable->update(['score' => $score, 'status' => 'Evaluated'], $studentTestId);
	        }
            return redirect()->route('student.test.testresults',['id'=>$studentTestId]);

        }
        else{
            return redirect()->route('student.test.taketest',['id'=>$id]);
        }
	    /*--MARUF END--*/
    }

    public function starttest(Request $request,$id)
    {
        $studentTestTable = new StudentTestTable();
        $studentTestId = $studentTestTable->addRecord([
            'student_id'=>$this->getId(),
            'test_id'=>$id,
            'score'=>0
        ]);

        $output = json_encode(['id'=>$studentTestId,'status'=>true]);
        exit($output);
    }



    public function result(Request $request,$id)
    {

        $studentTestTable = new StudentTestTable();
        $testTable = new TestTable();
        $row = $studentTestTable->getRecord($id);
        $this->validateOwner($row);
        $testRow = $testTable->getRecord($row->test_id);

        $courseTest = session('testInfo');
        if(isset($courseTest)){
            $testInfo = unserialize($courseTest);
        }
        else{
            $testInfo= [];
        }
        if(isset($testInfo[$testRow->test_id])){
            $sessionId = $testInfo[$testRow->id]['course_id'];
            $lessonId = $testInfo[$testRow->id]['lesson_id'];
            if($row->score >= $testRow->passmark){
                //set attendance for class
                $attendanceTable = new AttendanceTable();
                $attendanceTable->setAttendance([
                    'student_id'=>$this->getId(),
                    'course_id'=>$sessionId,
                    'lesson_id'=>$lessonId
                ]);

                flashMessage(__lang('class-test-complete',['score'=>$row->score]));
                $sessionLessonTable = new SessionLessonTable();
                $nextClass = $sessionLessonTable->getNextLessonInSession($sessionId,$lessonId,'c');
                if($nextClass){
                    //forward to the next class
                    return redirect()->route('student.course.class',['course'=>$sessionId,'lesson'=>$nextClass->lesson_id]);
                }
                else{
                    //classes are over
                    flashMessage(__lang('course-complete-msg'));
                    $studentSessionTable = new StudentSessionTable();
                    $studentSessionTable->markCompleted($this->getId(),$sessionId);
                    return redirect()->route('student.catalog.course',['id'=>$sessionId]);
                }
            }
            else{
                flashMessage(__lang('low-test-score',['score'=>$row->score]));
                return redirect()->route('student.course.class',['course'=>$sessionId,'lesson'=>$lessonId]);

            }

        }

        return view('student.test.result',['row'=>$row,'pageTitle'=>__lang('Test Result').': '.$testRow->name,'testRow'=>$testRow]);
    }


    public function testresults(Request $request,$id){
		/*--MARUF START /student/test/testresults/75--*/
        $studentTest = StudentTest::find($id);

        $test = $studentTest->test;
        if(empty($test->show_result)){
            flashMessage(__lang('not-allowed-result'));
            return back();
        }
        //get test
        $studentId = $this->getId();
        $student = Student::find($studentId);
        $rowset = $student->studentTests()->orderBy('created_at','desc')->where('id',$id)->paginate(30);
        $rowset = $student->studentTests()->orderBy('created_at','desc')->where('id',$id)->paginate(30);


        return view('student.test.testresults',['pageTitle'=>__lang('Test Results').': '.$test->name,
            'rowset'=>$rowset,
            'test'=>$test,
            'gradeTable'=>new TestGradeTable()
        ]);

    }

    public function reportcard(Request $request,$id){
		
		
        $sessionId = $id;
        $this->data['tests'] = $this->getSessionTestsObjects($sessionId);
        $this->data['allTests'] = $this->getSessionTests($sessionId);
	
	    
        //get studentlist

        $this->data['controller'] = $this;
        $this->data['testGradeTable'] = new TestGradeTable();
        $student = $this->getStudent();
        $this->data['student'] = $student;
	
	
	    $passing_year = $certificate_tracking_number = '';
	    $table = new StudentSessionTable();
		
	    $certificates= $table->getCertificateByCourse(false,$student->id,$sessionId);
		foreach ($certificates as $certificate){
			$output = DB::table('student_certificates')
				        ->select('student_id','certificate_id','tracking_number','created_at')
						->where('student_id',$student->id)
						->where('certificate_id',$certificate->certificate_id)
						->get();
			if(sizeof($output) > 0) {
				$certificate_tracking_number = @$output[0]->tracking_number;
				$certificate_id              = @$output[0]->certificate_id;
				$passing_year                = date('Y',strtotime(@$output[0]->created_at));
			}
		}
	    
	
	    $result_cgpa = $result_grade = '';
	    $output_cgpa = DB::table('student_courses')
		                 ->where('student_id',$student->id)
		                 ->where('course_id',$sessionId)
		                 ->get();
	    if(sizeof($output_cgpa) > 0) {
		    $result_grade                = @$output_cgpa[0]->result_grade;
		    $result_cgpa                 = @$output_cgpa[0]->result_cgpa;
		    $certificate_tracking_number = @$output_cgpa[0]->result_certificate_number;
		    $passing_year                = @$output_cgpa[0]->result_passing_year;
	    }
	    $this->data['result_grade']                 = $result_grade;
	    $this->data['result_cgpa']                  = $result_cgpa;
	    $this->data['certificate_tracking_number']  = $certificate_tracking_number;
	    $this->data['passing_year']                 = $passing_year;
	
	    $course = Course::find($sessionId);
        
        $this->data['session'] = $course;
        $this->data['baseUrl'] = $this->getBaseUrl();
	
	    $modules = array();
	    foreach($course->lessons()->orderBy('pivot_sort_order')->get() as $lesson){
		    $modules[] = $lesson->name;
	    }
	    $this->data['modules'] = $modules;

        //$html = view('admin.report.reportcard',$this->data)->toHtml();
        $html = view('admin.report.transcript',$this->data)->toHtml();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $orientation = 'portrait';

        $dompdf->setPaper('A4', $orientation);
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
	    if (($request->ip() == '::1') OR ($request->ip() == '127.0.0.1')) {
		    $dompdf->stream(safeUrl($student->user->name . ' ' . $student->user->last_name . ' report ' . $this->data['session']->name) . '.pdf', array("Attachment" => FALSE));
	    }else {
		    $dompdf->stream(safeUrl($student->user->name . ' ' . $student->user->last_name . ' report ' . $this->data['session']->name) . '.pdf');
	    }


        exit();

    }

    public function statement(Request $request){
        $id = $this->getId();
        $student = Student::findOrFail($id);
        $this->data['sessions'] = $student->studentCourses()->orderBy('created_at','desc')->paginate();
        $this->data['pageTitle'] = __lang('Statement Of Result');
        return view('student.test.statement',$this->data);
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

    public function getStudentTestsStats($studentId){

        $totalTaken = 0;
        $scores = 0;



        foreach($this->data['allTests'] as $testId){
            $studentTest = StudentTest::where('student_id',$studentId)->orderBy('score','desc')->where('test_id',$testId)->first();
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

}
