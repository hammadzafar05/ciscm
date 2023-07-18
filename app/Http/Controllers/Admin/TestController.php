<?php

namespace App\Http\Controllers\Admin;
use Dompdf\Dompdf;
use Dompdf\Options;


use App\Course;
use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\BaseTable;
use App\Lib\HelperTrait;
use App\Model\ScheduleEmail;
use App\QuestionBankOption;
use App\Student;
use App\Test;
use App\TestOption;
use App\TestQuestion;
use App\V2\Form\TestFilter;
use App\V2\Form\TestForm;
use App\V2\Form\TestQuestionFilter;
use App\V2\Form\TestQuestionForm;
use App\V2\Model\QuestionBankTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\SessionTestTable;
use App\V2\Model\StudentTestOptionTable;
use App\V2\Model\StudentTestTable;
use App\V2\Model\TestGradeTable;
use App\V2\Model\TestOptionTable;
use App\V2\Model\TestQuestionTable;
use App\V2\Model\TestTable;
use Barryvdh\DomPDF\PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laminas\Form\Element;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;

class TestController extends Controller
{
	use HelperTrait;
	
	public function index(Request $request){
		
		$table = new TestTable();
		$questionTable = new TestQuestionTable();
		$studentTestTable = new StudentTestTable();
		$filter = request()->get('filter');
		
		
		
		if (empty($filter)) {
			$filter=null;
		}
		$paginator = $table->getPaginatedRecords(true,null,$filter);
		
		$paginator->setCurrentPageNumber((int)request()->get('page', 1));
		$paginator->setItemCountPerPage(30);
		return viewModel('admin',__CLASS__,__FUNCTION__,array(
			'paginator'=>$paginator,
			'pageTitle'=>__lang('Tests'),
			'questionTable'=>$questionTable,
			'studentTestTable'=>$studentTestTable
		));
	}
	
	
	public function add(Request $request)
	{
		$output = array();
		$table = new TestTable();
		$form = new TestForm(null,$this->getServiceLocator());
		$filter = new TestFilter();
		
		if (request()->isMethod('post')) {
			
			$form->setInputFilter($filter);
			$data = request()->all();
			
			$form->setData($data);
			if ($form->isValid()) {
				
				$array = removeNull($form->getData());
				
				//dd($array);
				//due_date
				$array['due_date'] = !empty($array['due_date']) ? $array['due_date']:null;
				
				$array[$table->getPrimary()]=0;
				$id= $table->saveRecord($array);
				//    flashMessage(__lang('Changes Saved!'));
				$output['flash_message'] = __lang('Record Added!');
				session()->flash('flash_message',__lang('test-added'));
				return adminRedirect(['controller'=>'test','action'=>'sessions','id'=>$id]);
			}
			else{
				$output['flash_message'] = __lang('save-failed-msg');
				
			}
			
		}
		
		$output['form'] = $form;
		$output['pageTitle']= __lang('Add Test');
		$output['action']='add';
		$output['id']=null;
		return viewModel('admin',__CLASS__,__FUNCTION__,$output);
	}
	
	public function edit(Request $request,$id){
		$output = array();
		$table = new TestTable();
		$form = new TestForm(null,$this->getServiceLocator());
		$filter = new TestFilter();
		
		$row = $table->getRecord($id);
		if (request()->isMethod('post')) {
			
			$form->setInputFilter($filter);
			$data = request()->all();
			$form->setData($data);
			if ($form->isValid()) {
				
				
				
				$array = removeNull($form->getData());
				
				$array['due_date'] = !empty($array['due_date']) ? $array['due_date']:null;
				
				$array[$table->getPrimary()]=$id;
				$table->saveRecord($array);
				//    flashMessage(__lang('Changes Saved!'));
				$output['flash_message'] = __lang('Changes Saved!');
				flashMessage(__lang('Changes Saved!'));
				$row = $table->getRecord($id);
				return redirect()->route('admin.test.index');
			}
			else{
				$output['flash_message'] = __lang('save-failed-msg');
			}
			
		}
		else {
			
			$data = getObjectProperties($row);
			
			$form->setData($data);
			
		}
		
		
		$output['form'] = $form;
		$output['id'] = $id;
		$output['pageTitle']= __lang('Edit Test');
		$output['row']= $row;
		$output['action']='edit';
		
		$viewModel = viewModel('admin',__CLASS__,'add',$output);
		return $viewModel ;
		
	}
	
	public function delete(Request $request,$id)
	{
		$table = new TestTable();
		try{
			$table->deleteRecord($id);
			flashMessage(__lang('Record deleted'));
		}
		catch(\Exception $ex){
			$this->deleteError();
		}
		
		return adminRedirect(array('controller'=>'test','action'=>'index'));
	}
	
	
	public function questions(Request $request,$id){
		$testTable = new TestTable();
		
		$table = new TestQuestionTable();
		$optionTable = new TestOptionTable();
		$row = $testTable->getRecord($id);
		
		/*----Starting WU-35 : Question Bank----*/
		$sessionTestTable = new SessionTestTable();
		$courses = $sessionTestTable->getTestRecords($row->id);
		
	
		if ($courses->count() > 0) {
		/*----Ending WU-35 : Question Bank----*/
			$course_id = 0;
			foreach ($courses as $course){
				$course_id = $course->course_id;
			}
			
			/*---$sessionLessonTable and $sessionClassTable are same----*/
			$sessionClassTable = new SessionLessonTable();
			$classes = $sessionClassTable->getSessionRecords($course_id);
			
			$paginator = $table->getPaginatedRecords(TRUE, $id);
			
			$paginator->setCurrentPageNumber((int)request()->get('page', 1));
			$paginator->setItemCountPerPage(30);
			return viewModel('admin', __CLASS__, __FUNCTION__, array(
				'paginator' => $paginator,
				'course_id' => $course_id,
				'course' => $course,
				'classes' => $classes,
				'pageTitle' => __lang('Test Questions') . ': ' . $row->name,
				'type' => ($row->exam_type == 1 ? __lang('shomvabona-written') : __lang('shomvabona-mcq')),
				'exam_type' => $row->exam_type,
				'id' => $id,
				'optionTable' => $optionTable,
				'page' => (int)request()->get('page', 1)
			));
		/*----Starting WU-35 : Question Bank----*/
		}else{
			session()->flash('flash_error_message', 'Need to select course first!');
			return back();
		}
		/*----Ending WU-35 : Question Bank----*/
	}
	
	/*----Starting WU-35 : Question Bank----*/
	public function add_question_from_question_bank(Request $request,$id)
	{
		$test_id = $id;
		$testTable = new TestTable();
		$row = $testTable->getRecord($id);

		
		/*$sessionTestTable = new SessionTestTable();
		$course = $sessionTestTable->getTestRecords($row->id);*/
		
		$testQuestionTable = new TestQuestionTable();
		$testOptionTable = new TestOptionTable();
		if(request()->isMethod('post')){
			$data = request()->all();
			if(empty($data['select_question_from_course_id'])) {
				session()->flash('flash_error_message', 'Need to select course first!');
				return back();
			}else if(empty($data['select_question_from_class_id'])) {
				session()->flash('flash_error_message', 'Need to select class/lesson first!');
				return back();
			}else{
				$optionTable = new QuestionBankOption();
				
				$questionBankTable = new QuestionBankTable();
				$paginator = $questionBankTable->getPaginatedRecordsByClass(false,$data['select_question_from_course_id'],$data['select_question_from_class_id'],$data['select_question_type']);
				//$paginator = $questionBankTable->getPaginatedRecordsByClass(false,$data['select_question_from_course_id'],$data['select_question_from_class_id'],'MCQ');
				/*dd($paginator->count());*/
				
				$has_questions = 0;
				foreach ($paginator as $page) {
					if (in_array($page->class_id, $data['select_question_from_class_id'])) {
						$has_questions++;
					}
				}
				if($has_questions == 0) {
					session()->flash('flash_error_message', 'No Questions found for this class/lesson!');
					return back();
				}else {
					
					$questions_array = array();
					foreach ($paginator as $page) {
						if (in_array($page->class_id, $data['select_question_from_class_id'])) {
							$options_db = $optionTable->getOptionRecords($page->id);
							$options = array();
							$correct_answer = '';
							foreach ($options_db as $option) {
								$options[] = $option->option;
								if ($option->is_correct == 1) {
									$correct_answer = $option->option;
								}
							}
						}
						
						$questions_array[] = array(
							'question' => $page['question'],
							'type' => $page['questions_type'],
							'sort_order' => $page['sort_order'],
							'total_options' => $optionTable->getTotalOptions($page->id),
							'options' => implode('|', $options),
							'correct_answer' => $correct_answer,
						);
					}
					if(count($questions_array) == 0) {
						session()->flash('flash_error_message', 'No Questions found for this class/lesson!');
						return back();
					}else {
						
						shuffle($questions_array);
						$questions_array = array_slice($questions_array, 0, $row->number_of_questions);
						
						foreach ($questions_array as $question) {
							if ($testQuestionTable->questionExists($question['question'], $test_id)) {
								$dbData = [
									'test_id' => $test_id,
									'question' => $question['question'],
									'sort_order' => $question['sort_order']
								];
								
								$questionId = $testQuestionTable->addRecord($dbData);
								
								
								if ($row->exam_type == 0) {
									//correct answer
									$correct = $question['correct_answer'];
									$options = $question['options'];
									$options = explode('|', $options);
									foreach ($options as $option) {
										$optionData = [
											'test_question_id' => $questionId,
											'option' => trim($option)
										];
										if (trim($option) == $correct) {
											$optionData['is_correct'] = 1;
										} else {
											$optionData['is_correct'] = 0;
										}
										$testOptionTable->addRecord($optionData);
									}
								} else {
									$optionData = [
										'test_question_id' => $questionId,
										'option' => 'written'
									];
									$optionData['is_correct'] = 0;
									$testOptionTable->addRecord($optionData);
								}
							}
						}
						
						
						session()->flash('flash_message', __lang('Question added'));
						return back();
					}
				}
				
			}
		}
		// return adminRedirect(['controller'=>'test','action'=>'questions','id'=>$id]);
	}
	/*----Ending WU-35 : Question Bank----*/
	
	public function addquestion(Request $request,$id)
	{
		$testTable = new TestTable();
		$row = $testTable->getRecord($id);
		
		$testQuestionTable = new TestQuestionTable();
		$testOptionTable = new TestOptionTable();
		if(request()->isMethod('post'))
		{
			$data = request()->all();
			if(!empty($data['question'])){
				
				$dbData= [
					'test_id'=>$id,
					'question'=>$data['question'],
					'sort_order'=>$data['sort_order']
				];
				
				$questionId = $testQuestionTable->addRecord($dbData);
				session()->flash('flash_message',__lang('Question added'));
				/*--MARUF START--*/
				if($row->exam_type == 0) {
					//correct answer
					$correct = $data['correct_option'];
					for ($i = 1; $i <= 5; $i++) {
						if (!empty($data['option_' . $i])) {
							$optionData = [
								'test_question_id' => $questionId,
								'option' => trim($data['option_' . $i])
							];
							if ($i == $correct) {
								$optionData['is_correct'] = 1;
							} else {
								$optionData['is_correct'] = 0;
							}
							$testOptionTable->addRecord($optionData);
						}
					}
				}else{
					$optionData = [
						'test_question_id' => $questionId,
						'option' => 'WRITTEN'
					];
					$optionData['is_correct'] = 0;
					$testOptionTable->addRecord($optionData);
				}
				/*--MARUF END--*/
			}
		}
		
		return back();
		// return adminRedirect(['controller'=>'test','action'=>'questions','id'=>$id]);
	}
	
	public function editquestion(Request $request,$id){
		
		$output = [];
		$questionTable = new TestQuestionTable();
		$optionTable = new TestOptionTable();
		$form = new TestQuestionForm();
		$filter = new TestQuestionFilter();
		$form->setInputFilter($filter);
		$row = $questionTable->getRecord($id);
		$rowset = $optionTable->getOptionRecords($id);
		
		
		
		if(request()->isMethod('post'))
		{
			$formData = request()->all();
			$form->setData($formData);
			if($form->isValid()){
				$data = $form->getData();
				$questionTable->update($data,$id);
				session()->flash('flash_message',__lang('Changes Saved!'));
				return adminRedirect(['controller'=>'test','action'=>'questions','id'=>$row->test_id]);
			}
			else{
				$output['flash_message'] = __lang('save-failed-msg');
			}
		}
		else{
			$form->setData(getObjectProperties($row));
		}
		
		
		$output['row'] = $row;
		$output['rowset']= $rowset;
		$output['form'] = $form;
		$output['id'] = $id;
		$output['pageTitle'] = __lang('Edit Question/Options');
		$output['customCrumbs'] = [
			route('admin.dashboard')=>__('default.dashboard'),
			adminUrl(['controller'=>'test','action'=>'index'])=>__lang('Tests'),
			adminUrl(['controller'=>'test','action'=>'questions','id'=>$row->test_id])=>__lang('Test Questions'),
			'#'=>__lang('Edit Question')
		];
		return view('admin.test.editquestion',$output);
	}
	
	public function addoptions(Request $request)
	{
		$id= request()->get('id');
		$testOptionTable = new TestOptionTable();
		if(request()->isMethod('post'))
		{
			$data = request()->all();
			
			
			//correct answer
			$correct = $data['correct_option'];
			if(!empty($correct)){
				$testOptionTable->clearIsCorrect($id);
			}
			$count = 0;
			for($i=1;$i<=5;$i++){
				
				if(!empty($data['option_'.$i])){
					
					$optionData = [
						'test_question_id'=>$id,
						'option'=> trim($data['option_'.$i])
					];
					
					if($i==$correct){
						$optionData['is_correct'] = 1;
					}
					else{
						$optionData['is_correct'] = 0;
					}
					
					$testOptionTable->addRecord($optionData);
					$count++;
					
				}
				
				
			}
			session()->flash('flash_message',$count.' '.__lang('options added'));
			
			
		}
		
		return adminRedirect(['controller'=>'test','action'=>'editquestion','id'=>$id]);
		
	}
	
	public function editoption(Request $request,$id){
		
		$testOptionTable = new TestOptionTable();
		$row = $testOptionTable->getRecord($id);
		$questionId = $row->test_question_id;
		
		if(request()->isMethod('post'))
		{
			$data = request()->all();
			if(!empty($data['option'])) {
				
				$dbData = [];
				if (!empty($data['is_correct']))
				{
					$testOptionTable->clearIsCorrect($questionId);
					$dbData['is_correct']=$data['is_correct'];
				}
				$dbData['option']=$data['option'];
				$testOptionTable->update($dbData,$id);
				session()->flash('flash_message',__lang('Option saved'));
			}
			else{
				session()->flash('flash_message',__lang('survey-save-failed'));
			}
			return adminRedirect(['controller'=>'test','action'=>'editquestion','id'=>$questionId]);
		}
		
		$option = new Text('option');
		$option->setAttributes(['class'=>'form-control']);
		$option->setValue($row->option);
		
		$select = new Select('is_correct');
		$select->setAttribute('class','form-control');
		$select->setValueOptions([1=>'Yes',0=>'No']);
		$select->setValue($row->is_correct);
		
		$viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['row'=>$row,'option'=>$option,'select'=>$select,'id'=>$id]);
		
		return $viewModel;
	}
	
	public function deletequestion(Request $request,$id)
	{
		$table = new TestQuestionTable();
		$row = $table->getRecord($id);
		$testId = $row->test_id;
		try{
			$table->deleteRecord($id);
			flashMessage(__lang('Record deleted'));
		}
		catch(\Exception $ex){
			$this->deleteError();
		}
		
		return adminRedirect(array('controller'=>'test','action'=>'questions','id'=>$testId));
	}
	
	public function deleteoption(Request $request)
	{
		$table = new TestOptionTable();
		$id = request()->get('id');
		$row = $table->getRecord($id);
		$questionId = $row->test_question_id;
		try{
			$table->deleteRecord($id);
			flashMessage(__lang('Record deleted'));
		}
		catch(\Exception $ex){
			$this->deleteError();
		}
		
		return adminRedirect(array('controller'=>'test','action'=>'editquestion','id'=>$questionId));
	}
	
	public function duplicate(Request $request,$id)
	{
		$testTable = new TestTable();
		$testQuestionTable = new TestQuestionTable();
		$testOptionTable = new TestOptionTable();
		
		//get all questions
		$test = $testTable->getRecord($id);
		$questions = $testQuestionTable->getPaginatedRecords(false,$test->id)->toArray();
		$options = [];
		foreach($questions as $question){
			$options[$question['id']] = $testOptionTable->getOptionRecords($question['id'])->toArray();
		}
		
		
		$testData = getObjectProperties($test);
		unset($testData['id']);
		
		$newId = $testTable->addRecord($testData);
		
		foreach($questions as $question)
		{
			$oldQuestionId=$question['id'];
			$question['test_id']= $newId;
			unset($question['id']);
			$questionId=  $testQuestionTable->addRecord($question);
			foreach($options[$oldQuestionId] as $option){
				$option['test_question_id'] = $questionId;
				unset($option['id']);
				$testOptionTable->addRecord($option);
			}
			
		}
		
		session()->flash('flash_message',__lang('Test duplicated'));
		return adminRedirect(['controller'=>'test','action'=>'index']);
		
		
		
	}
	
	
	public function results(Request $request,$id)
	{
		$testTable = new TestTable();
		$table = new StudentTestTable();
		
		$filter = request()->get('filter');
		$startDate = request()->get('start', null) ? getDateString(request()->get('start', null)):null;
		$endDate = request()->get('end', null) ? getDateString(request()->get('end', null)):null ;
		
		if (empty($filter)) {
			$filter=null;
		}
		
		
		$row = $testTable->getRecord($id);
		$paginator = $table->getPaginatedRecords(true,$id,$filter,$startDate,$endDate);
		
		$testTotal = $table->getTotalForTest($id,$startDate,$endDate);
		$totalPassed = $table->getTotalPassed($id,$row->passmark,$startDate,$endDate);
		$totalFailed= $testTotal - $totalPassed;
		$average = $table->getAverageScore($id,$startDate,$endDate);
		
		$paginator->setCurrentPageNumber((int)request()->get('page', 1));
		$paginator->setItemCountPerPage(30);
		
		return viewModel('admin',__CLASS__,__FUNCTION__,array(
			'paginator'=>$paginator,
			'pageTitle'=>__lang('Test results').': '.$row->name,
			'row'=>$row,
			'passed'=>$totalPassed,
			'failed'=>$totalFailed,
			'average'=>$average,
			'start'=>request()->get('start', null),
			'end'=>request()->get('end', null)
		));
	}
	
	/*----Starting WU-71 : Written exam PDF----*/
	public function createPdfResultsForAllStudent(Request $request,$id)
	{
		$exam_id = $id;
		$testTable = new TestTable();
		$exam_details = $testTable->getRecord($exam_id);
		
		$studentOptionTable = new StudentTestOptionTable();
		$table = new StudentTestTable();
		$paginator = $table->getPaginatedRecords(FALSE,$exam_id);
		$students = $answers = array();
		foreach ($paginator as $page){
			/*
			-storage: array:12 [▼
				"id" => "219"
				"created_at" => "2021-09-03 07:02:37"
				"updated_at" => "2021-09-03 07:03:26"
				"student_id" => "221"
				"test_id" => "36"
				"score" => "0.00"
				"status" => "Pending"
				"mobile_number" => "1670617942"
				"first_name" => "MOKHLESUR"
				"last_name" => "RAHMAN"
				"email" => "maruf.mokhlesh@gmail.com"
				"passmark" => "4.00"
			]
			 * */
			$answers[$page->id]['students'] = $page;
			
			
			$rowset = $studentOptionTable->getTestRecords($page->id);
			
			/*
			"student_test_test_option_id" => "1028"
			"student_test_id" => "219"
			"test_option_id" => "814"
			"answer" => "<p>Answer 1</p>"
			"marks_percentage" => "0"
			"results_status" => "0"
			"id" => "243"
			"created_at" => "2021-09-03 07:02:02"
			"updated_at" => "2021-09-03 07:02:02"
			"test_question_id" => "243"
			"option" => "WRITTEN"
			"is_correct" => "0"
			"test_id" => "36"
			"question" => "<p>Written Question 1</p>"
			"sort_order" => null
			* */
			foreach ($rowset as $row) {
				$answers[$page->id]['answers'][] = $row;
			}
		}
		//dd($answers);
		$data = ScheduleEmail::all();
		
		// share data to view
		view()->share('employee',$data);
		$pdf = PDF::loadView('pdf_view', $data);
		
		// download PDF file with download method
		return $pdf->download('pdf_file.pdf');
		
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
	
	public function createPdfResultForAStudent(Request $request,$id)
	{
		$testTable = new TestTable();
		$studentTestTable = new StudentTestTable();
		$studentOptionTable = new StudentTestOptionTable();
		
		/*Find Data*/
		$row = $studentTestTable->getRecord($id);
		//dd($row);
		$test = $testTable->getRecord($row->test_id);
	
		$rowset = $studentOptionTable->getTestRecords($id);
		
		$sessionTestTable  = new SessionTestTable();
		$test_details = $sessionTestTable->getTestDetails($row->test_id);
		//dd($test_details->course_id);
		
		$sessionId = $test_details->course_id;
		$this->data['test'] = $test;
		//dd($this->data['test']);
		$this->data['tests'] = $rowset;


		
		//$this->data['controller'] = $this;
		//$this->data['testGradeTable'] = new TestGradeTable();
		$student = Student::find($row->student_id);
		$this->data['student'] = $student;
		$this->data['session'] = Course::find($sessionId);
		$this->data['baseUrl'] = $this->getBaseUrl();
		
		$html = view('admin.test.resultcard',$this->data)->toHtml();
		
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		
		$dompdf->loadHtml($html);
		$orientation = 'portrait';
		
		$dompdf->setPaper('A4', $orientation);
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream(safeUrl($student->first_name.' '.$student->last_name.' answer scripts '.$this->data['session']->name).'.pdf');
		
		
		exit();
	}
	/*----ending WU-71 : Written exam PDF----*/
	
	public function testresult(Request $request,$id)
	{
		/*--MARUF START--*/
		$testTable = new TestTable();
		$studentTestTable = new StudentTestTable();
		$studentOptionTable = new StudentTestOptionTable();
		
		/*Find Data*/
		$row = $studentTestTable->getRecord($id);
		$test = $testTable->getRecord($row->test_id);
		$rowset = $studentOptionTable->getTestRecords($id);
		
		/*foreach ($rowset as $option_single){
			dd($option_single);
		}
		
		dd($rowset);*/
	
		$data = ['row'=>$row,'rowset'=>$rowset,'test'=>$test, 'student_tests_id'=>$row->id];
		
		$viewModel = viewModel('admin',__CLASS__,__FUNCTION__,$data);
		
		return $viewModel;
		/*--MARUF END--*/
	}
	
	
	public function deleteresult(Request $request,$id)
	{
		$studentTestTable = new StudentTestTable();
		
		$row = $studentTestTable->getRecord($id);
		$testId = $row->test_id;
		try{
			$studentTestTable->deleteRecord($id);
			flashMessage(__lang('Record deleted'));
		}
		catch(\Exception $ex){
			$this->deleteError();
		}
		
		return adminRedirect(array('controller'=>'test','action'=>'results','id'=>$testId));
	}
	
	public function exportresult(Request $request,$id){
		
		$type = $_GET['type'];
		$studentTestTable = new StudentTestTable();
		$testTable = new TestTable();
		$file = "export.txt";
		if (file_exists($file)) {
			unlink($file);
		}
		
		$startDate = request()->get('start', null) ? getDateString(request()->get('start', null)):null;
		$endDate = request()->get('end', null) ? getDateString(request()->get('end', null)):null ;
		
		
		$myfile = fopen($file, "w") or die("Unable to open file!");
		
		$testRow = $testTable->getRecord($id);
		
		if($type=='pass')
		{
			$totalRecords = $studentTestTable->getTotalPassedForTest($id,$testRow->passmark,$startDate,$endDate);
			
		}
		else{
			$totalRecords = $studentTestTable->getTotalFailedForTest($id,$testRow->passmark,$startDate,$endDate);
		}
		
		
		
		$rowsPerPage = 3000;
		$totalPages = ceil($totalRecords/$rowsPerPage);
		fputcsv($myfile, array(__lang('First Name'),__lang('Last Name'),__lang('Score').'%'));
		for($i=1;$i<=$totalPages;$i++){
			if($type=='pass') {
				$paginator = $studentTestTable->getPassedPaginatedRecords(true, $id,$testRow->passmark,$startDate,$endDate);
			}
			else{
				$paginator = $studentTestTable->getFailPaginatedRecords(true, $id,$testRow->passmark,$startDate,$endDate);
			}
			
			$paginator->setCurrentPageNumber($i);
			$paginator->setItemCountPerPage($rowsPerPage);
			
			foreach ($paginator as $row){
				
				fputcsv($myfile, array($row->first_name,$row->last_name,$row->score));
				
			}
			
			
			
		}
		$paginator = array();
		fclose($myfile);
		header('Content-type: text/csv');
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="'.$type.'_student_test_export_'.date('d/M/Y').'.csv"');
		
		// The PDF source is in original.pdf
		readfile($file);
		unlink($file);
		exit();
	}
	
	
	public function sessions(Request $request,$id){
		
		$sessionTestTable = new SessionTestTable();
		$testTable = new  TestTable();
		$testRow = $testTable->getRecord($id);
		
		$rowset = $sessionTestTable->getTestRecords($id);
		return view('admin.test.sessions',[
			'pageTitle'=>__lang('test-sessions-courses').': '.$testRow->name,
			'rowset'=>$rowset,
			'id'=>$id
		]);
		
	}
	
	public function addsession(Request $request,$id){
		
		$sessionTestTable = new SessionTestTable();
		$testTable = new TestTable();
		$testRow = $testTable->getRecord($id);
		$form = $this->getSessionTestForm();
		$output = [];
		if(request()->isMethod('post')){
			$formData = request()->all();
			$form->setData($formData);
			if($form->isValid()){
				
				$data = $form->getData();
				$data['test_id'] = $id;
				//$data['opening_date']= getDateString($data['opening_date']);
				$data['opening_date']= ($data['opening_date']);
				//$data['closing_date']= getDateString($data['closing_date']);
				$data['closing_date']= ($data['closing_date']);
				$sessionTestTable->addRecord($data);
				session()->flash('flash_message',__lang('course-added-succ'));
				return adminRedirect(['controller'=>'test','action'=>'sessions','id'=>$id]);
				
				
			}
			else{
				$output['flash_message']= $this->getFormErrors($form);
			}
		}
		
		$output['form'] = $form;
		$output['pageTitle'] = __lang('add-course-to').' '.$testRow->name;
		$output['id']=$id;
		$output['customCrumbs'] = [
			
			route('admin.dashboard')=>__('default.dashboard'),
			adminUrl(['controller'=>'test','action'=>'index'])=>__lang('Tests'),
			adminUrl(['controller'=>'test','action'=>'sessions','id'=>$id])=>__lang('Sessions/Courses'),
			'#'=>__lang('add').' '.__lang('sessions-courses')
		];
		return view('admin.test.addsession',$output);
	}
	
	public function editsession(Request $request,$id){
		
		$sessionTestTable = new SessionTestTable();
		$row = $sessionTestTable->getRecord($id);
		$testTable = new TestTable();
		$testRow = $testTable->getRecord($row->test_id);
		$form = $this->getSessionTestForm();
		$output = [];
		if(request()->isMethod('post')){
			$formData = request()->all();
			$form->setData($formData);
			if($form->isValid()){
				
				$data = $form->getData();
				
				//$data['opening_date']= getDateString($data['opening_date']);
				$data['opening_date']= ($data['opening_date']);
				//$data['closing_date']=getDateString($data['closing_date']);
				$data['closing_date']=($data['closing_date']);
				$sessionTestTable->update($data,$id);
				session()->flash('flash_message',__lang('course-saved'));
				return adminRedirect(['controller'=>'test','action'=>'sessions','id'=>$testRow->id]);
				
				
			}
			else{
				$output['flash_message']= $this->getFormErrors($form);
			}
		}
		else{
			$data = getObjectProperties($row);
			/*if(!empty($data['opening_date']))
				$data['opening_date'] = showDate('Y-m-d',$row->opening_date);*/
			
			/*if(!empty($data['closing_date']))
				$data['closing_date'] = showDate('Y-m-d',$row->closing_date);
			*/
			$form->setData($data);
			
		}
		
		$output['form'] = $form;
		$output['pageTitle'] = __lang('edit-course-for').' '.$testRow->name;
		$output['customCrumbs'] = [
			
			route('admin.dashboard')=>__('default.dashboard'),
			adminUrl(['controller'=>'test','action'=>'index'])=>__lang('Tests'),
			adminUrl(['controller'=>'test','action'=>'sessions','id'=>$id])=>__lang('Sessions/Courses'),
			'#'=>__lang('edit').' '.__lang('sessions-courses')
		];
		$viewModel = viewModel('admin',__CLASS__,'addsession',$output);
		
		return $viewModel;
	}
	
	public function deletesession(Request $request,$id){
		
		$testTable = new TestTable();
		$sessionTestTable= new SessionTestTable();
		$row = $sessionTestTable->getRecord($id);
		$testRow = $testTable->getRecord($row->test_id);
		if($testRow->admin_id==$this->getAdminId() || GLOBAL_ACCESS){
			$sessionTestTable->deleteRecord($id);
			session()->flash('flash_message',__lang('Record deleted'));
		}
		else{
			session()->flash('flash_message',__lang('no-permission'));
		}
		
		return back();
		
	}
	
	private function getSessionTestForm(){
		$form = new BaseForm();
		
		//get all sessions for user
		$sessionTable = new SessionTable();
		$sessions = $sessionTable->getPaginatedRecords(true);
		$sessions->setCurrentPageNumber(1);
		$sessions->setItemCountPerPage(500);
		$options=array();
		foreach ($sessions as $row)
		{
			$options[$row->id]=$row->name;
		}
		
		$sessionInstructorTable = new SessionInstructorTable();
		$rowset = $sessionInstructorTable->getAccountRecords(ADMIN_ID);
		foreach($rowset as $row){
			$options[$row->course_id] = $row->name;
		}
		
		$form->createSelect('course_id', 'Session/Course', $options);
		$form->get('course_id')->setAttribute('class','form-control select2');
		
		
		$form->createText('opening_date','Opening Date (Optional)',false,'form-control date_s',null,'Opening Date');
		$form->createText('closing_date','Closing Date (Optional)',false,'form-control date_time',null,'Closing Date');
		
		$form->setInputFilter($this->getSessionTestFilter());
		return $form;
		
		
	}
	
	private function getSessionTestFilter(){
		$filter = new InputFilter();
		$filter->add([
			'name'=>'course_id',
			'required'=>true,
			'validators'=>[
				[
					'name'=>'NotEmpty'
				]
			]
		]);
		
		$filter->add([
			'name'=>'opening_date',
			'required'=>false
		]);
		
		$filter->add([
			'name'=>'closing_date',
			'required'=>false
		]);
		
		return $filter;
		
	}
	
	
	public function importquestions(Request $request,$id){
		
		
		$testQuestionTable = new TestQuestionTable();
		
		$lastSortOrder = $testQuestionTable->getLastSortOrder($id);
		
		if(request()->isMethod('post')){
			
			$data = $_FILES['file'];
			$file = $data['tmp_name'];
			try{
				$file = fopen($file,"r");
				
				$all_rows = array();
				$header = null;
				while ($row = fgetcsv($file)) {
					if ($header === null) {
						$header = $row;
						continue;
					}
					$all_rows[] = array_combine($header, $row);
				}
				$imported=0;
				foreach($all_rows as $value){
					
					
					$question = $value['Question'];
					
					if(empty($question)){
						continue;
					}
					$options = $value['Options'];
					$correctOption = intval($value['Correct_Option_Number']);
					
					
					//create new question
					$testQuestion = new TestQuestion();
					$testQuestion->question = trim($question);
					if(!empty($lastSortOrder)){
						$lastSortOrder++;
						$testQuestion->sort_order = $lastSortOrder;
					}
					else{
						$testQuestion->sort_order = 0 ;
					}
					
					$testQuestion->test_id = $id;
					$testQuestion->save();
					$imported++;
					//get options
					$optionEntries= explode('|',$options);
					$count =0;
					foreach ($optionEntries as $optionValue){
						
						if(!empty($optionValue)){
							$count++;
							$testOption=new TestOption();
							$testOption->test_question_id= $testQuestion->id;
							$testOption->option = trim($optionValue);
							if($count == $correctOption){
								$testOption->is_correct = 1;
							}
							$testOption->save();
						}
						
					}
					
					
				}
				
				session()->flash('flash_message',__lang('questions-imported',['count'=>$imported]));
				return back();
			}
			catch(\Exception $ex){
				session()->flash('flash_message',__lang('shomvabona-import-questions'));
				return back();
			}
		}
	}
	
	public function exportquestions(Request $request,$id){
		
		$test =  Test::find($id);
		
		$file = "export.txt";
		if (file_exists($file)) {
			unlink($file);
		}
		
		$myfile = fopen($file, "w") or die(__lang('unable-to-open'));
		
		if($test->exam_type == 0) {
			$columns = array(__lang('Question'), __lang('Options'), __lang('correct-option-number'));
			fputcsv($myfile, $columns);
			
			foreach ($test->testQuestions()->orderBy('sort_order')->get() as $testQuestion) {
				
				$data = [];
				$data[0] = strip_tags($testQuestion->question);
				
				$optionCount = 0;
				$correct = 0;
				$optionArray = [];
				foreach ($testQuestion->testOptions as $testOption) {
					$optionCount++;
					$optionArray[] = $testOption->option;
					if ($testOption->is_correct == 1) {
						$correct = $optionCount;
					}
				}
				$data[1] = implode(',', $optionArray);
				$data[2] = $correct;
				fputcsv($myfile, $data);
			}
		}else{
			$columns = array(__lang('Question'));
			fputcsv($myfile, $columns);
			
			foreach ($test->testQuestions()->orderBy('sort_order')->get() as $testQuestion) {
				
				$data = [];
				$data[0] = strip_tags($testQuestion->question);
				
				fputcsv($myfile, $data);
			}
		}
		
		fclose($myfile);
		header('Content-type: text/csv');
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="'.safeUrl($test->name).'_questions_'.date('d/M/Y').'.csv"');
		
		// The PDF source is in original.pdf
		readfile($file);
		unlink($file);
		exit();
		
	}
	function add_marks(Request $request){
		$studentTestOptionTable = new StudentTestOptionTable();
		
		
		$student_tests_id = $request->input('student_tests_id');
		
		$marks_percentage = $request->input('mark');
		$student_test_test_option_id = $request->input('student_test_test_option_id');
		$rowset = $studentTestOptionTable->getRecord($student_test_test_option_id);
		
		//$studentTestOptionTable->update(['marks_percentage' => 20], $student_test_test_option_id);
		DB::table('student_test_test_option')->where('student_test_test_option_id', $student_test_test_option_id)->update(array('marks_percentage' => $marks_percentage,'results_status' =>1));
		
		$rowsets = $studentTestOptionTable->getAllMarksForTest($student_tests_id);
		$marks_percentage = 0;
		$loop = $ready = 0;
		foreach ($rowsets as $row) {
			$loop++;
			//dd($row);
			$marks_percentage += $row->marks_percentage;
			if ($row->results_status == 1){
				$ready++;
			}
		}
		
		$score = ($marks_percentage/$loop);
		//dd($score);
		
		$studentTestTable = new StudentTestTable();
		$row = $studentTestTable->getRecord($student_tests_id);
		
		if($loop == $ready){
			$studentTestTable->update(['score' => $score, 'status' => 'Evaluated'], $student_tests_id);
			return AppHelper::RespondWithSuccess(
				'Result has been updated successfully',
				''
			);
		}else{
			$studentTestTable->update(['score' => $score], $student_tests_id);
			return AppHelper::RespondWithSuccess(
				'Result has been updated successfully',
				''
			);
		}
	}
}
