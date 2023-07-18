<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\Model\PartnerStudent;
use App\QuestionBank;
use App\QuestionBankOption;
use App\User;
use App\V2\Model\AttendanceTable;
use App\V2\Model\MarkDistributionTable;
use App\V2\Model\MarkTable;
use App\V2\Model\RegistrationFieldTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentFieldTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\StudentTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laminas\Form\Element\Select;
use Laminas\InputFilter\InputFilter;
use ZipArchive;

class PartnerController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
	    $admin_role = getAdminRole(Auth::user()->id);
	    if ($admin_role == 'Partner'){
		    $admin_role = $admin_role;
	    }else {
		    $admin_role = $admin_role;
	    }
		
		
        $table = new MarkTable();
        $distributionTable = new MarkDistributionTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
	
	
	
	    
	    foreach($paginator as $mark_row){
		    $mark_row['markDistributionTable'] = $distributionTable->getAllMarkDistributionDetails($mark_row->id);
			$edit_enabled_for_distribution = 1;
			$mark_status_arr = array();
		    foreach ($mark_row['markDistributionTable'] as $row){
				if ($row['distribution_status'] == 'Final') {
					$mark_status_arr[] = $row['distribution_status'];
				}
		    }
			if (count($mark_status_arr) == count($mark_row['markDistributionTable'])){
				$edit_enabled_for_distribution = 0;
			}
		    $mark_row['edit_enabled_for_distribution'] = $edit_enabled_for_distribution;
	    }
		
        return viewModel('admin','_partner_mark',__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Mark Management'),
            'distributionTable' => $distributionTable,
            'total' => 0,
			'admin_role' => $admin_role
            //'total' => $table->getTotalAdminAssignments($this->getAdminId())
        ));

    }

    public function add(Request $request)
    {
        $output = array();
	    $distributionTable = new MarkDistributionTable();
        $form = $this->getMarkDistributionForm();

        $filter = $this->getMarkDistributionFilter();

        if (request()->isMethod('post')) {
	
			$course_id = $request->input('course_id');
	        $markTable = new MarkTable();
	        $markExits = $markTable->recordExists($course_id);
	  
			if($markExits){
				session()->flash('flash_error_message', __lang('Mark exists for this course'));
				return adminRedirect(['controller' => 'partnermark', 'action' => 'index']);
			}else {
				$marks = $request->input('marks');
				$student_name = $request->input('student_name');
				$student_id = $request->input('student_id');
				$attendance_mark = $request->input('attendance_mark');
				$assignment_mark = $request->input('assignment_mark');
				$assessment_mark = $request->input('assessment_mark');
				$distribution_status = $request->input('distribution_status');
	 
				
				$errors = array();
				$error = 0;
				foreach ($student_id as $key => $value) {
					if ($attendance_mark[$key] > $marks['attendance_mark']){
						$error++;
						$errors[] = 'Attendance Mark is more than '.$marks['attendance_mark'].' for '.$student_name[$key];
					}
					if ($assignment_mark[$key] > $marks['assignment_mark']){
						$error++;
						$errors[] = 'Assignment Mark is more than '.$marks['assignment_mark'].' for '.$student_name[$key];
					}
					if ($assessment_mark[$key] > $marks['assessment_mark']){
						$error++;
						$errors[] = 'Assessment Mark is more than '.$marks['assessment_mark'].' for '.$student_name[$key];
					}
				}
				if ($error > 0){
					session()->flash('flash_error_message', implode('<li>',$errors));
					return adminRedirect(['controller' => 'partnermark', 'action' => 'index']);
				}else {
					$mark_status = $request->input('status');
					foreach ($student_id as $key => $value) {
						if ($distribution_status[$key] == 'Submit for Approval'){
							$mark_status = 'Submit for Approval';
							break;
						}
					}
					//create new question
					$markTable = new MarkTable();
					$options = [
						'mark_calc' => serialize($marks),
						'title' => $request->input('title'),
						'status' => $mark_status,
						'course_id' => $course_id,
						'admin_id' => $this->getAdmin()->admin->id,
						'from_id' => $this->getAdmin()->admin->id,
						'to_id' => '1',
					];
					$mark_id = $markTable->addRecord($options);
					
					
					foreach ($student_id as $key => $value) {
						if (!empty($value)) {
							$distributionTable = new MarkDistributionTable();
							
							$optionData = [
								'mark_id' => $mark_id,
								'marks' => serialize($marks),
								'student_id' => $student_id[$key],
								'attendance_mark' => $attendance_mark[$key],
								'assignment_mark' => $assignment_mark[$key],
								'assessment_mark' => $assessment_mark[$key],
								'distribution_status' => $distribution_status[$key],
								'total_mark' => ($attendance_mark[$key] + $assignment_mark[$key] + $assessment_mark[$key]),
							];
							$distributionTable->addRecord($optionData);
						}
					}
					
					if ($mark_status == 'Submit for Approval') {
						$session = Course::find($course_id);
						
						$message = 'Result ready for <b>'.$session->name.'</b><br>Partner Instructor:'.adminName($this->getAdmin()->admin->id);
						$this->sendEmail('info@worldacademy.uk', 'Result Approval Request from '.adminName($this->getAdmin()->admin->id), $message);
					}
					
					session()->flash('flash_message', __lang('Record Added'));
					return adminRedirect(['controller' => 'partnermark', 'action' => 'index']);
				}
			}
        }
	
	    $sessionTable = new SessionTable();
	    $rowset = $sessionTable->getLimitedRecords(5000);
	
	
	    $options = [];
	    $options[] = 'Select an option';
	    $log = [];
	    foreach($rowset as $row){
		    $options[$row->id] = $row->name;
	    }
	
	    $sessionTable = new SessionTable();
	    $sessions = $sessionTable->getLimitedRecords(5000);

        $output['form'] = $form;
        $output['sessions'] = $sessions;
        $output['pageTitle'] = __lang('Add Mark');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin','_partner_mark',__FUNCTION__,$output);
    }
	
	public function sessionstudents(Request $request,$id)
	{
		
		$session = Course::find($id);
		
		// TODO Auto-generated ParentsController::index(Request $request) default action
		$options = array();
		$table = new StudentSessionTable();

		$paginator = $table->getSessionRecords(false,$id,true);
		
		foreach($paginator as $row){
			$options[$row->student_id] = $row->name.' '.$row->last_name;
		}
		
		$output['session_name'] = 'Marks for '.$session->name;
		$output['students'] = $options;
		
		return view('admin.partner_mark.purchase_summary',compact('output'));
	}
	
	
	public function sessionStudentsMarks(Request $request,$id)
	{

		$session = Course::find($id);
		
		// TODO Auto-generated ParentsController::index(Request $request) default action
		
		$options = array();
		$table = new StudentSessionTable();

		$paginator = $table->getSessionRecords(false,$id,true);
		
		foreach($paginator as $row){
			$options[$row->student_id] = $row->name.' '.$row->last_name;
		}
		
		$output['session_name'] = 'Marks for '.$session->name;
		$output['students'] = $options;
		$output['edit_attendance_mark'] = $request->input('edit_attendance_mark');
		$output['edit_assignment_mark'] = $request->input('edit_assignment_mark');
		$output['edit_assessment_mark'] = $request->input('edit_assessment_mark');
		$output['edit_mark_distributions_id'] = $request->input('edit_mark_distributions_id');
		$output['distribution_status'] = $request->input('distribution_status');
		$output['edit_status'] = $request->input('edit_status');
		

		return view('admin.partner_mark.edit_mark_distribution',compact('output'));
	}
	
	private function getMarkDistributionForm(){
		$form = new BaseForm();
		$sessionTable = new SessionTable();
		$rowset = $sessionTable->getLimitedRecords(5000);

		
		$options = [];
		$options[] = 'Select an option';
		$log = [];
		foreach($rowset as $row){
			$options[$row->id] = $row->name;
		}

		$sessionId = new Select('course_id');
		$sessionId->setLabel(__lang('Session/Course'));
		$sessionId->setAttribute('class','form-control select2');
		$sessionId->setAttribute('id','course_id');
		//dd($options);
		$sessionId->setValueOptions($options);
		
		$form->add($sessionId);

		/*$form->createText('due_date','Due Date',true,'form-control date_f');*/

		return $form;
	}
	
	private function getMarkDistributionFilter(){
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
		/*
		$filter->add([
			'name'=>'due_date',
			'required'=>false,
		
		]);*/
		
		return $filter;
	}


    public function edit(Request $request,$id){
	    $output = array();
	    $distributionTable = new MarkDistributionTable();
	    $form = $this->getMarkDistributionForm();

		$markTable = new MarkTable();
	    $markTable = $markTable->getRecord($id);

	    $markDistributionTable = $distributionTable->getAllMarkDistribution($markTable->id);
		

        if (request()->isMethod('post')) {
	        $course_id = $request->input('course_id');
			
	        $marks = $request->input('marks');
	        $student_name = $request->input('student_name');
	        $student_id = $request->input('student_id');
	        $attendance_mark = $request->input('attendance_mark');
	        $assignment_mark = $request->input('assignment_mark');
	        $assessment_mark = $request->input('assessment_mark');
	        $mark_distributions_id = $request->input('mark_distributions_id');
	        $distribution_status = $request->input('distribution_status');
			
	
	        $mark_status = $request->input('status');
	        foreach ($student_id as $key => $value) {
		        if ($distribution_status[$key] == 'Submit for Approval'){
			        $mark_status = 'Submit for Approval';
			        break;
		        }
	        }
			
	        $markTable = new MarkTable();
	        $markTable->getRecord($id);

	        $array = [
		        'id' => $id,
		        'title' => $request->input('title'),
		        'status' => $mark_status,
		        'course_id' => $course_id,
	        ];
	        $markTable->saveRecord($array);
	
	        foreach ($student_id as $key => $value) {
		        if (!empty($value)) {
			
			        $MarkDistributionID = $mark_distributions_id[$key];
					
			        $distributionTable = new MarkDistributionTable();
			        $distributionTable->getRecord($MarkDistributionID);
					
			        $optionData = [
				        'id' => $MarkDistributionID,
				        'mark_id' => $id,
				        'marks' => serialize($marks),
				        'student_id' => $student_id[$key],
				        'attendance_mark' => $attendance_mark[$key],
				        'assignment_mark' => $assignment_mark[$key],
				        'assessment_mark' => $assessment_mark[$key],
				        'distribution_status' => $distribution_status[$key],
				        'total_mark' => ($attendance_mark[$key] + $assignment_mark[$key] + $assessment_mark[$key]),
			        ];
			        $distributionTable->saveRecord($optionData);
		        }
	        }
			
	        if ($mark_status == 'Submit for Approval') {
		        $session = Course::find($course_id);
		
		        $message = 'Result ready for <b>'.$session->name.'</b><br>Partner Instructor:'.adminName($this->getAdmin()->admin->id);
		        $this->sendEmail('info@worldacademy.uk', 'Result Approval Request from '.adminName($this->getAdmin()->admin->id), $message);
	        }
	
	        session()->flash('flash_message', __lang('Changes Saved!'));
	        return adminRedirect(['controller' => 'partnermark', 'action' => 'index']);
        }


	    $sessionTable = new SessionTable();
	    $rowset = $sessionTable->getLimitedRecords(5000);
	
	
	    $options = [];
	    $options[] = 'Select an option';
	    $log = [];
	    foreach($rowset as $row){
		    $options[$row->id] = $row->name;
	    }
	
	    $sessionTable = new SessionTable();
	    $sessions = $sessionTable->getLimitedRecords(5000);
	
	    $output['form'] = $form;
	    $output['sessions'] = $sessions;
	    $output['pageTitle'] = __lang('Edit Mark');
	    $output['markTable']= $markTable;
	    $output['markDistributionTable']= $markDistributionTable;
	    $output['action']='edit';
	    $output['id']=$id;
		
	    return viewModel('admin','_partner_mark','edit',$output);

    }

    public function view(Request $request,$id){

	    $markTable = new MarkTable();
	    $mark_row = $markTable->getRecordWithCourseName($id);
			
		//dd($mark_row);
	
	    $distributionTable = new MarkDistributionTable();
	    $markDistributionTable = $distributionTable->getAllMarkDistributionDetails($mark_row->id);

        $data =[
	        'mark_id' => $id,
	        'mark_row' => $mark_row,
	        'markDistributionTable' => $markDistributionTable,
        ];
        $viewModel = viewModel('admin','_partner_mark','view',$data);
        return $viewModel;
    }


    public function approval(Request $request,$id){

	    $markTable = new MarkTable();
	    $mark_row = $markTable->getRecordWithCourseName($id);
			
		//dd($mark_row);
	
	    $distributionTable = new MarkDistributionTable();
	    $markDistributionTable = $distributionTable->getAllMarkDistributionDetails($mark_row->id);

        $data =[
	        'mark_id' => $id,
	        'mark_row' => $mark_row,
	        'markDistributionTable' => $markDistributionTable,
        ];
	
	    $data['pageTitle'] = __lang('Approve Mark').': '.$mark_row->course_name;
        $viewModel = viewModel('admin','_partner_mark','approval',$data);
        return $viewModel;
    }
	
	
	public function convert_marks($marks,$convert_to){
		$marks = round((($marks / 100) * $convert_to),2);
		return $marks;
	}

    public function prepare_result(Request $request,$id){

	    $markTable = new MarkTable();
	    $mark_row = $markTable->getRecordWithCourseName($id);
			
		//dd($mark_row);
	
	    $distributionTable = new MarkDistributionTable();
	    $markDistributionTables = $distributionTable->getAllMarkDistributionDetails($mark_row->id);
	    /*$markDistributionTables->buffer();
		foreach ($markDistributionTables as $markD){
			$total_marks = $markD->total_mark;
			$converted_marks = $this->convert_marks($total_marks,60);
			$markD['partner_60_percent_mark'] = $converted_marks;
			$markD['admin_40_percent_mark'] = $this->convert_marks($markD->admin_mark,40);
		}
	    dd($markDistributionTables);*/

        $data =[
	        'mark_id' => $id,
	        'mark_row' => $mark_row,
	        'markDistributionTable' => $markDistributionTables,
        ];
	
	    $data['pageTitle'] = __lang('Admin Mark Distribution').': '.$mark_row->course_name;
        $viewModel = viewModel('admin','_partner_mark','prepare_result',$data);
        return $viewModel;
    }

    public function delete(Request $request,$id)
    {
        $table = new AssignmentTable();
        $table->deleteRecord($id);
        flashMessage(__lang('Record deleted'));
        return  back();
    }

    public function submissions(Request $request,$id)
    {
        $assignmentSubmissionsTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        $assignmentRow = $assignmentTable->getRecord($id);
        $course_id = $assignmentRow->course_id;

        $paginator = $assignmentSubmissionsTable->getAssignmentPaginatedRecords(false,$id);
        $paginator3 = $assignmentSubmissionsTable->getAssignmentPaginatedRecords(false,$id);
        /*$paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);*/
	
	    $submitted_students = array();
	    foreach ($paginator3 as $students){
		    $submitted_students[] = $students['student_id'];
	    }
	   
	    
        $assignmentTotal = $assignmentSubmissionsTable->getTotalSubmittedForAssignment($id);
        $totalPassed = $assignmentSubmissionsTable->getTotalPassedForAssignment($id,$assignmentRow->passmark);
        $totalFailed= $assignmentTotal - $totalPassed;
        $average = $assignmentSubmissionsTable->getAverageScore($id);
	
	
	    $table = new StudentSessionTable();
	    $all_enrolled_students = $table->getSessionRecords(true,$course_id,true);


        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'all_enrolled_students'=>$all_enrolled_students,
            'submitted_students'=>$submitted_students,
            'pageTitle'=>__lang('Homework Submissions:').' '.$assignmentRow->title,
            'total' => $assignmentTotal,
            'passed' => $totalPassed,
            'failed' => $totalFailed,
            'average'=>$average,
            'row'=>$assignmentRow,
            'assignment_id'=>$assignmentRow->id,
	        
        ));


    }
	
	public function update_editable(Request $request) {
		
		$model = '';
		$id     = $request->input('pk');
		$name   = $request->input('name');
		$value  = $request->input('value');
		
		$table  = $request->input('table');
		
		if ($table) {
			$markTable = new MarkTable();
			$markTable->getRecord($id);

			$array = [
				'id' => $id,
				$name => $value,
			];
			$markTable->saveRecord($array);
			if ($markTable) {
				
				return AppHelper::RespondWithSuccess(
					'Status updated successfully',
					''
				);
				
			} else {
				return AppHelper::RespondWithError(
					'No record found'
				);
			}
		}else{
			return AppHelper::RespondWithError(
				'No record found'
			);
		}
	}
	
	
	public function update_editable_2(Request $request) {
		
		$model = '';
		$id     = $request->input('pk');
		$name   = $request->input('name');
		$value  = $request->input('value');
		
		$table  = $request->input('table');
		
		if ($table) {
			$markTable = new MarkDistributionTable();
			$markTable->getRecord($id);
			
			$array = [
				'id' => $id,
				$name => $value,
			];
			$markTable->saveRecord($array);
			if ($markTable) {
				
				return AppHelper::RespondWithSuccess(
					'Status updated successfully',
					''
				);
				
			} else {
				return AppHelper::RespondWithError(
					'No record found'
				);
			}
		}else{
			return AppHelper::RespondWithError(
				'No record found'
			);
		}
	}
	
	


}
