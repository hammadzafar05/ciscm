<?php

namespace App\Http\Controllers\Admin;

use App\Assignment;
use App\Course;
use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;

use App\Model\ScheduleEmail;
use App\Student;
use App\V2\Model\AccountsTable;
use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AssignmentTable;
use App\V2\Model\AttendanceTable;
use App\V2\Model\NoticeBoard;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
// use Illuminate\Support\Facades\Storage;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\StudentTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laminas\Form\Element\Select;
use Laminas\InputFilter\InputFilter;
use ZipArchive;

class NoticeboardController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $table = new NoticeBoard();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('noticeboard'),
            'total' => $table->getTotalNotices(0)
        ));

    }

    public function add(Request $request)
    {
		
	    $admin_role = getAdminRole(Auth::user()->id);
	    $enrolled_users[] = 0;
	    $SessionTable = new SessionTable();
	    $StudentSessionTable = new StudentSessionTable();
	    $courses = $SessionTable->getPaginatedRecords(false);
	    foreach ($courses as $course) {
		    $students = $StudentSessionTable->getSessionRecords(FALSE, $course->id, TRUE);
		    foreach ($students as $student) {
			    //dd($student);
			    $enrolled_users[] = $student->user_id;
		    }
	    }
	
	
	    $sessionTable = new SessionTable();
	    $studentSessionTable = new StudentSessionTable();
	    $output = [];
	    $count = 0;
	
	    $studentTable = new StudentTable();
	    if ($admin_role == 'Partner'){
		    $all_students = $studentTable->getStudents(FALSE,null,$enrolled_users);
	    }else{
		    $all_students = $studentTable->getStudents(FALSE);
	    }
	
	
	    $courses = array();
	    $student_by_courses = array();
	    $sessionTable = new SessionTable();
	    //$sessions = $sessionTable->getPaginatedRecords(FALSE);
	    $sessions = $sessionTable->getPaginatedRecords(FALSE,null,true,null,null,'asc',null,true);
	
	    foreach ($sessions as $row) {
		    $students_by = $studentSessionTable->getSessionRecords(false,$row->id,true);
		    foreach ($students_by as $student) {
			    $student_by_courses[] = array('mobile_number'=>$student->mobile_number,'email'=>$student->email,'name'=>$student->name.' '.$student->last_name,'session_id'=>$row->id,'user_id'=>$student->user_id);
		    }
		    if ($students_by->count() > 0) {
			    $courses[] = array('id' => $row->id, 'name' => $row->name);
		    }
	    }
	    $noticeBoardTable = new NoticeBoard();
		
	    if(!empty($id)){
		    $row = $noticeBoardTable->getRecord($id);
		    $output['row'] = $row;
		    $output['id'] = $id;
		    $output['students'] = $studentSessionTable->getSessionRecords(true,0,true);
		    $output['all_students'] = $all_students;
		    $output['pageTitle']= __lang('Noticeboard');
		    $output['totalStudents'] = Student::count();
	    }
	    else{
		    $output['row'] = null;
		    $output['id'] = 0;
		    $output['students'] = $studentSessionTable->getSessionRecords(true,0,true);
		    $output['all_students'] = $all_students;
		    $output['pageTitle']= __lang('Noticeboard');
		    $output['totalStudents'] = Student::count();
	    }
	    $output['courses'] = $courses;
	    $output['student_by_courses'] = $student_by_courses;

        if (request()->isMethod('post')) {
	
	        $last_date_to_display = $request->post('last_date_to_display');
	        $type = $request->post('type');
	        $course_id = $request->post('course_id');
			if (!$course_id){
				$course_id = 0;
			}
	        $student_by_courses = $request->post('student_by_courses');
	        $students = $request->post('students');
	        $title = $request->post('title');
	        $message = $request->post('message');
			if ($student_by_courses) {
				if (count($student_by_courses) > 0) {
					$student_by_courses = implode(',', $student_by_courses);
				}
			}
	        if ($students) {
		        if (count($students) > 0) {
			        $students = implode(',', $students);
		        }
	        }
	
	        $array = array();
	        $array['id'] = 0;
	        $array['last_date_to_display'] = $last_date_to_display;
	        $array['type'] = $type;
	        $array['course_id'] = $course_id;
	        $array['student_by_courses'] = $student_by_courses;
	        $array['students'] = $students;
	        $array['title'] = $title;
	        $array['message'] = $message;
			
	
	        $noticeBoardTable->saveRecord($array);
	        //$data = NoticeBoard::create($request->all());
	
	        session()->flash('flash_message',__lang('Record Added'));
	        return adminRedirect(['controller'=>'noticeboard','action'=>'index']);
        }

        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }


    public function edit(Request $request,$id) {
		
	    $admin_role = getAdminRole(Auth::user()->id);
	    $enrolled_users[] = 0;
	    $SessionTable = new SessionTable();
	    $StudentSessionTable = new StudentSessionTable();
	    $courses = $SessionTable->getPaginatedRecords(false);
	    foreach ($courses as $course) {
		    $students = $StudentSessionTable->getSessionRecords(FALSE, $course->id, TRUE);
		    foreach ($students as $student) {
			    //dd($student);
			    $enrolled_users[] = $student->user_id;
		    }
	    }
	
	
	    $sessionTable = new SessionTable();
	    $studentSessionTable = new StudentSessionTable();
	    $output = [];
	    $count = 0;
	
	    $studentTable = new StudentTable();
	    if ($admin_role == 'Partner'){
		    $all_students = $studentTable->getStudents(FALSE,null,$enrolled_users);
	    }else{
		    $all_students = $studentTable->getStudents(FALSE);
	    }
	
	
	    $courses = array();
	    $student_by_courses = array();
	    $sessionTable = new SessionTable();
	    //$sessions = $sessionTable->getPaginatedRecords(FALSE);
	    $sessions = $sessionTable->getPaginatedRecords(FALSE,null,true,null,null,'asc',null,true);
	
	    foreach ($sessions as $row) {
		    $students_by = $studentSessionTable->getSessionRecords(false,$row->id,true);
		    foreach ($students_by as $student) {
			    $student_by_courses[] = array('mobile_number'=>$student->mobile_number,'email'=>$student->email,'name'=>$student->name.' '.$student->last_name,'session_id'=>$row->id,'user_id'=>$student->user_id);
		    }
		    if ($students_by->count() > 0) {
			    $courses[] = array('id' => $row->id, 'name' => $row->name);
		    }
	    }
	    $noticeBoardTable = new NoticeBoard();
	
	    if(!empty($id)){
		    $row = $noticeBoardTable->getRecord($id);
		    $output['row'] = $row;
		    $output['id'] = $id;
		    $output['students'] = $studentSessionTable->getSessionRecords(true,0,true);
		    $output['all_students'] = $all_students;
		    $output['pageTitle']= __lang('Noticeboard');
		    $output['totalStudents'] = Student::count();
	    }
	    else{
		    $output['row'] = null;
		    $output['id'] = 0;
		    $output['students'] = $studentSessionTable->getSessionRecords(true,0,true);
		    $output['all_students'] = $all_students;
		    $output['pageTitle']= __lang('Noticeboard');
		    $output['totalStudents'] = Student::count();
	    }
	    $output['courses'] = $courses;
	    $output['student_by_courses'] = $student_by_courses;
	
	    if (request()->isMethod('post')) {
		
		    
		    $last_date_to_display = $request->post('last_date_to_display');
		    $type = $request->post('type');
		    $course_id = $request->post('course_id');
		    if (!$course_id){
			    $course_id = 0;
		    }
		    $student_by_courses = $request->post('student_by_courses');
		    $students = $request->post('students');
		    $title = $request->post('title');
		    $message = $request->post('message');
		    if ($student_by_courses) {
			    if (count($student_by_courses) > 0) {
				    $student_by_courses = implode(',', $student_by_courses);
			    }
		    }
		    if ($students) {
			    if (count($students) > 0) {
				    $students = implode(',', $students);
			    }
		    }
		
		    if ($type == 'Course'){
			    $students = '';
		    }else{
			    $course_id = 0;
			    $student_by_courses = '';
		    }
			
		    $array = array();
		    $array['id'] = $id;
		    $array['last_date_to_display'] = $last_date_to_display;
		    $array['type'] = $type;
		    $array['course_id'] = $course_id;
			
			if ($course_id > 0 && $student_by_courses == ''){
				$__student_by_courses = array();
				$sessions = $sessionTable->getPaginatedRecords(FALSE,$course_id,true,null,null,'asc',null,true);
				foreach ($sessions as $row) {
					$students_by = $studentSessionTable->getSessionRecords(false,$row->id,true);
					foreach ($students_by as $student) {
						$__student_by_courses[] = $student->user_id;
					}
				}
				$student_by_courses = implode(',', $__student_by_courses);
			}
			
		    $array['student_by_courses'] = $student_by_courses;
		    $array['students'] = $students;
		    $array['title'] = $title;
		    $array['message'] = $message;
			
		
		    $noticeBoardTable->saveRecord($array);
		    //$data = NoticeBoard::create($request->all());
		
		    session()->flash('flash_message',__lang('Changes Saved'));
		    return adminRedirect(['controller'=>'noticeboard','action'=>'index']);
	    }
	
	    return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }

    public function view(Request $request,$id){

        $homeworkTable = new AssignmentTable();
        $submissionsTable = new AssignmentSubmissionTable();
        $row = Assignment::find($id);
        $data =[
            'row' => $row,
            'table' => $submissionsTable,
            'accountsTable' => new AccountsTable()
        ];
        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,$data);
        return $viewModel;
    }

    public function delete(Request $request,$id)
    {
        $table = new NoticeBoard();
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
	
	/*--MARUF START--*/
	public function send_email_reminder_assignment(Request $request,$id,$email){
		$assignmentTable = new AssignmentTable();
		
		$assignmentRow = $assignmentTable->getRecord($id);
		$course_id = $assignmentRow->course_id;

		$subject = 'Assignment Remider: '.$assignmentRow->title;
		$message = 'Please submit your assignment regarding '.$assignmentRow->title;
		$message .= '<br>Due Date '.$assignmentRow->due_date;

		try{
			sendEmail($email,$subject,$message);
			session()->flash('flash_message','Reminder has been sent successfully.');
		}
		catch(\Exception $ex){
			session()->flash('flash_message','There Was An Error Trying To Send Your Email. Please Try Again Later.');
		}
		
		return back();
	}
	/*--MARUF END--*/

    public function viewsubmission(Request $request,$id){
        $assignmentSubmissionTable = new AssignmentSubmissionTable();

        $row = $assignmentSubmissionTable->getSubmission($id);
        $form = $this->getGradeForm();

        if(request()->isMethod('post')){
            $formData = request()->all();
            $form->setData($formData);
            if($form->isValid()){
                $data = $form->getData();
                $assignmentSubmissionTable->update($data,$id);


                if(!empty($formData['notify'])){
                    $this->notifyStudent($row->student_id,__lang('homework-graded-mail-title'),__lang('homework-graded-mail-msg',['title'=>$row->title]));
                }

                session()->flash('flash_message',__lang('updated-this-assignment'));
                return adminRedirect(['controller'=>'assignment','action'=>'submissions','id'=>$row->assignment_id]);
            }
            else{
                flashMessage($this->getFormErrors($form));
            }

        }
        else{
            $form->setData(getObjectProperties($row));
            if(empty($row->grade)){
                $form->get('editable')->setValue(0);
            }

        }

        $this->data['customCrumbs'] = [

            route('admin.dashboard')=>__('default.dashboard'),
            adminUrl(['controller'=>'assignment','action'=>'index'])=>__lang('Homework'),
            adminUrl(['controller'=>'assignment','action'=>'submissions','id'=>$row->assignment_id])=>__lang('Submissions'),
            '#'=>__lang('View Submission')
        ];

        return viewModel('admin',__CLASS__,__FUNCTION__,array_merge(array(
            'row'=>$row,
            'pageTitle'=>__lang('Homework Submission:').' '.$row->title,
            'form'=>$form
        ),$this->data));
    }
    

    public function downloadFile($id)
    {
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $row = $assignmentSubmissionTable->getSubmission($id);

        $path = $row->file_path;
        if (!file_exists($path)){
            return back();
        }
        
        //return response()->download($path);
        //echo $path;die;
        header('Content-type: '.getFileMimeType($path));
        //header('Content-Type: application/msword');

		// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

		// The PDF source is in original.pdf
        readfile($path);
        exit();

    }

    public function exportresult(Request $request,$id){

        $type = $_GET['type'];
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();
        $file = "export.txt";
        if (file_exists($file)) {
            unlink($file);
        }

        $myfile = fopen($file, "w") or die("Unable to open file!");
        $assignmentRow = $assignmentTable->getRecord($id);
        if($type=='pass')
        {
            $totalRecords = $assignmentSubmissionTable->getTotalPassedForAssignment($id,$assignmentRow->passmark);
        }
        else{
            $totalRecords = $assignmentSubmissionTable->getTotalFailedForAssignment($id,$assignmentRow->passmark);
        }

        $rowsPerPage = 3000;
        $totalPages = ceil($totalRecords/$rowsPerPage);
        fputcsv($myfile, array(__lang('First Name'),__lang('Last Name'),__lang('Email'),__lang('score').' %'));
        for($i=1;$i<=$totalPages;$i++){
            if($type=='pass') {
                $paginator = $assignmentSubmissionTable->getPassedPaginatedRecords(true, $id,$assignmentRow->passmark);
            }
            else{
                $paginator = $assignmentSubmissionTable->getFailPaginatedRecords(true, $id,$assignmentRow->passmark);
            }

            $paginator->setCurrentPageNumber($i);
            $paginator->setItemCountPerPage($rowsPerPage);

            foreach ($paginator as $row){

                fputcsv($myfile, array($row->first_name,$row->last_name,$row->email,$row->grade));

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
	
	public function export_file(Request $request,$id){
  
		$assignmentSubmissionTable = new AssignmentSubmissionTable();
		$assignmentTable = new AssignmentTable();
		$assignmentRow = $assignmentTable->getRecord($id);
		
		$error = '';
		$zip = new ZipArchive();
		$zip_name = $assignmentRow->title.".zip";
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
			$error .= "* Sorry ZIP creation failed at this time";
		}
		
		$paginator = $assignmentSubmissionTable->getAssignmentPaginatedRecords(false,$id);
		$rows = array();
		foreach ($paginator as $row){
			$rows[] = $row;
			//$pdfFile = asset($row->file_path);
			$pdfFile = ($row->file_path);
			$extension = AppHelper::getExtension($pdfFile);
			$zip->addFromString(basename(($row->first_name.' '.$row->last_name.'-'.$row->email).'.'.$extension),  file_get_contents($pdfFile));
		}
		//dd($rows);
 
		$zip->close();
		if(file_exists($zip_name)) {
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zip_name . '"');
			readfile($zip_name);
			unlink($zip_name);
		}
		dd($error);
		
	}

    public function sessionlessons(Request $request,$id){

        $selected = request()->get('lesson_id');

        $select = new Select('lesson_id');
        $select->setEmptyOption('--'.__lang('select').'--');
        $sessionLessonTable = new SessionLessonTable();
        $rowset= $sessionLessonTable->getSessionRecords($id);

        $options = [];
        foreach($rowset as $row){
            $options[$row->lesson_id]= $row->name;
        }
        $select->setLabel('Class');
        $select->setAttribute('class','form-control');
        $select->setValueOptions($options);

        if($selected){
            $select->setValue($selected);
        }
        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['select'=>$select]);

        return $viewModel;
    }

    private function getGradeForm(){
        $form= new BaseForm();
        $form->createTextArea('admin_comment',__lang('comment').' ('.__lang('optional').')',false);
        $form->createText('grade',__lang('grade').' (%)',true,'form-control digit',null,__lang('digits-only'));
        $form->createSelect('editable',__lang('Status'),['0'=>__lang('graded').' ('.__lang('un-editable').')','1'=>__lang('ungraded').' ('.__lang('editable').')'],true,false);
        $form->setInputFilter($this->getGradeFilter());
        return $form;
    }

    private function getGradeFilter(){
        $filter = new InputFilter();
        $filter->add([
            'name'=>'admin_comment',
            'required'=>false
        ]);
        $filter->add([
            'name'=>'grade',
            'required'=>'true',
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ],
                [
                    'name'=>'Float'
                ]
            ]
        ]);
        $filter->add([
            'name'=>'editable',
            'required'=> true
        ]);
        return $filter;
    }

    private function getAssignmentForm(){
        $form = new BaseForm();
        $sessionTable = new SessionTable();
        $rowset = $sessionTable->getLimitedRecords(5000);




        $options = [];
        $log = [];
        foreach($rowset as $row){
            // $options[$row->course_id] = $row->session_name;
            $options[] =  ['attributes'=>['data-type'=>$row->type],'value'=>$row->id,'label'=>$row->name.' ('.$row->id.')'];
            $log[$row->id]=true;
        }



        $sessionInstructorTable = new SessionInstructorTable();
        $rowset = $sessionInstructorTable->getAccountRecords($this->getAdminId());
        foreach($rowset as $row){

            if(isset($log[$row->course_id])){
                continue;
            }
            // $options[$row->course_id] = $row->session_name;
            $options[] =  ['attributes'=>['data-type'=>$row->type],'value'=>$row->course_id,'label'=>$row->name.' ('.$row->course_id.')'];

        }

        //$form->createSelect('course_id','Session/Course',$options,true);
        // $form->get('course_id')->setAttribute('class','form-control select2');

        $sessionId = new Select('course_id');
        $sessionId->setLabel(__lang('Session/Course'));
        $sessionId->setAttribute('class','form-control select2');
        $sessionId->setAttribute('id','course_id');
        //dd($options);
        $sessionId->setValueOptions($options);

        $form->add($sessionId);


        //$form->createText('due_time','Due Time',true,'form-control time-picker',null,'Example: 11:10 AM');
        $form->createText('due_date','Due Date',true,'form-control date_f');
        $form->createText('opening_date','Opening Date',true,'form-control date');
		
        $form->createSelect('schedule_type','Type',['s'=>__lang('Scheduled'),'c'=>__lang('Post Class')],true,false);
        $form->createText('title','Title',true);
        $form->createSelect('type','Student Response Type',['t'=>__lang('Text'),'f'=>__lang('File Upload'),'b'=>__lang('Text & File Upload')],true);
        $form->createTextArea('instruction','Homework Instructions',true);
        $form->get('instruction')->setAttribute('id','instruction');
        $form->createHidden('lesson_id');

        $form->createText('passmark','Passmark (%)',true,'number form-control');
        $form->createCheckbox('notify','Receive submission notifications?',1);
        $form->createCheckbox('allow_late','Allow late submissions?',1);
        return $form;
    }

    private function getAssignmentFilter(){
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
            'name'=>'due_date',
            'required'=>false,

        ]);


        $filter->add([
            'name'=>'type',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        $filter->add([
            'name'=>'instruction',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        $filter->add([
            'name'=>'passmark',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        $filter->add([
            'name'=>'notify',
            'required'=>false,

        ]);

        $filter->add([
            'name'=>'allow_late',
            'required'=>false,

        ]);

        $filter->add([
            'name'=>'opening_date',
            'required'=>false,

        ]);

        $filter->add([
            'name'=>'lesson_id',
            'required'=>false,

        ]);



        $filter->add([
            'name'=>'schedule_type',
            'required'=>true,

        ]);

        $filter->add([
            'name'=>'title',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        return $filter;
    }

}
