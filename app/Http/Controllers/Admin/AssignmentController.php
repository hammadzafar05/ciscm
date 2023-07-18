<?php

namespace App\Http\Controllers\Admin;

use App\Assignment;
use App\Course;
use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\V2\Model\AccountsTable;
use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AssignmentTable;
use App\V2\Model\AttendanceTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
// use Illuminate\Support\Facades\Storage;
use App\V2\Model\StudentSessionTable;
use Illuminate\Http\Request;
use Laminas\Form\Element\Select;
use Laminas\InputFilter\InputFilter;
use ZipArchive;
use DB;

class AssignmentController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $table = new AssignmentTable();
        $submissionTable = new AssignmentSubmissionTable();

       $form = $this->getAssignmentForm();
        // $paginator = $table;
        
        $paginator=DB::table('assignments')->join('courses','assignments.course_id','courses.id')->select('assignments.*','courses.name')->latest()->paginate(50);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'form'=>$form,
            'pageTitle'=>__lang('Homework'),
            'submissionTable' => $submissionTable,
            'total' => $table->getTotalAdminAssignments($this->getAdminId())
        ));

    }

    public function add(Request $request)
    {
        $output = array();
        $assignmentTable = new AssignmentTable();
        $form = $this->getAssignmentForm();

        $filter = $this->getAssignmentFilter();

        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $form->setData($data);
            if ($form->isValid()) {

                if($request->file()) 
                {
                    $uploadedFile = $request->file('assignment_file');
                    $filename = time().$uploadedFile->getClientOriginalName();
                    $destinationPath = public_path('uploads/assignment');
                    $uploadedFile->move($destinationPath,$filename);

                    /*Storage::disk('local')->putFileAs(
                        'assignment/'.$filename,
                        $uploadedFile,
                        $filename
                    );*/

                    // $assignmentTable->filename = $filename;

                    // $assignmentTable->save();
                    // die();
                }

                $array = $form->getData();
                $array[$assignmentTable->getPrimary()]=0;
                //$array['due_date'] = !empty($array['due_date'])? getDateNewString($array['due_date'],$array['due_time']):null;
                $array['due_date'] = !empty($array['due_date'])? ($array['due_date']):null;
	            //$array['due_time'] = !empty($array['due_time'])? getTimeStringTo24($array['due_time']):null;
                $array['opening_date'] = !empty($array['opening_date'])? getDateString($array['opening_date']):null;
                $array['assignment_file'] = !empty($filename)? $filename:null;

                $array['admin_id'] = $this->getAdmin()->admin->id;

                $assignmentTable->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                if(!empty($data['notify'])){
                    $subject = __lang('New Homework');
                    //$message= __lang('new-homework-email',['title'=>$data['title'],'instruction'=>$data['instruction'],'due-date'=>$array['due_date']]);
	                
	                $session = Course::find($data['course_id']);
	                $message= __lang('new-homework-email',['title'=>$data['title'],'course_name'=>$session->name,'instruction'=>$data['instruction'],'due-date'=>$array['due_date']]);
	
	                $sms= __lang('new-homework-sms',['title'=>$data['title'],'due-date'=>@$data['due_date']]);

                    $this->notifySessionStudents($data['course_id'],$subject,$message,true,$sms);
                }

                session()->flash('flash_message',__lang('Record Added'));
                return adminRedirect(['controller'=>'assignment','action'=>'index']);
            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');
            }

        }



        $output['form'] = $form;
        $output['pageTitle'] = __lang('Add Homework');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }


    public function edit(Request $request,$id){
        $output = array();
        $assignmentTable = new AssignmentTable();
        $form = $this->getAssignmentForm();
        $filter = $this->getAssignmentFilter();

        $row = $assignmentTable->getRecord($id);
        // print_r($row);die();
        $oldName = $row->title;
        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();

            $form->setData($data);
            if ($form->isValid()) {

                if($request->file()) 
                {
                    // echo $row->assignment_file;die();
                    @unlink(public_path('uploads/assignment')."/".$row->assignment_file);
                    $uploadedFile = $request->file('assignment_file');
                    $filename = time().$uploadedFile->getClientOriginalName();
                    $destinationPath = public_path('uploads/assignment');
                    $uploadedFile->move($destinationPath,$filename);
                }

                $array = $form->getData();

                $array[$assignmentTable->getPrimary()]=$id;
                //$array['due_date'] = !empty($array['due_date'])?getDateNewString($array['due_date'],$array['due_time']):null;
                $array['due_date'] = !empty($array['due_date'])?($array['due_date']):null;
		
	            //$array['due_time'] = !empty($array['due_time'])? getTimeStringTo24($array['due_time']):null;
                $array['opening_date'] = !empty($array['opening_date'])?getDateString($array['opening_date']):null;
                $array['assignment_file'] = !empty($filename)? $filename:null;
	
                $assignmentTable->saveRecord($array);

                if(!empty($data['notify_students'])){
                    $subject = __lang('New Homework');
	
	                $session = Course::find($data['course_id']);
                    $message= __lang('new-homework-email',['title'=>$data['title'],'course_name'=>$session->name,'instruction'=>$data['instruction'],'due-date'=>$array['due_date']]);
			
                    $textMessage= __lang('new-homework-sms',['title'=>$data['title'],'due-date'=>$data['due_date']]);


                    $this->notifySessionStudents($data['course_id'],$subject,$message,false,$textMessage);
                }

                session()->flash('flash_message',__lang('Changes Saved!'));
                return adminRedirect(['controller'=>'assignment','action'=>'index']);


            }
            else{

                $output['flash_message'] = $this->getFormErrors($form);
            }

        }
        else {


            $data = getObjectProperties($row);
	        $data['due_time'] = !empty($data['due_time'])? getTimeStringTo12($data['due_time']):null;
            //$data['due_date'] = !empty($data['due_date'])? showDate('Y-m-d',$data['due_date']):null;
            $data['opening_date'] = !empty($data['opening_date'])?showDate('Y-m-d',$data['opening_date']):null;
            $form->setData($data);

        }

        $output['form'] = $form;
        $output['id'] = $id;
        $output['pageTitle']= __lang('Edit Homework');
        $output['row']= $row;
        $output['action']='edit';

        $viewModel = viewModel('admin',__CLASS__,'add',$output);

        return $viewModel ;

    }

    public function view(Request $request,$id){

        $homeworkTable = new AssignmentTable();
        $submissionsTable = new AssignmentSubmissionTable();
        $row = Assignment::find($id);
        // dd($row);
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
	    $all_enrolled_students = $table->getSessionRecords(false,$course_id,true);
	
	    $not_submitted_students = 0;
	    foreach($all_enrolled_students as $row){
		    if(in_array($row->student_id,$submitted_students) == FALSE){
			    $not_submitted_students++;
		    }
	    }
		//dd($all_enrolled_students->count());
		
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'all_enrolled_students'=>$all_enrolled_students,
            'submitted_students'=>$submitted_students,
            'not_submitted_students'=>$not_submitted_students,
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
    public function downloadFilePdf($id)
    {
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $row = $assignmentSubmissionTable->getSubmission($id);

        $path = $row->file_path_pdf;
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
        //$form->createText('grade',__lang('grade').' (%)',false,'form-control digit',null,__lang('digits-only'));
        $form->createText('grade','Grade',false);
        $form->createSelect('editable',__lang('Status'),['0'=>__lang('graded').' ('.__lang('un-editable').')','1'=>__lang('ungraded').'('.__lang('editable').')'],true,false);
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
        $form->createCheckbox('capston','Capstone Project?',1);
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
            'name'=>'capston',
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
    
    // kabir works start from here
    public function filterCourse(Request $request){
        $form = $this->getAssignmentForm();
         $submissionTable = new AssignmentSubmissionTable();
        $paginator=DB::table('assignments')->join('courses','assignments.course_id','courses.id')->select('assignments.*','courses.name')->where('assignments.course_id',$request->course_id)->latest()->paginate(50);
        //dd($paginator);die;
        
        
        return view('admin.assignment.index',compact('paginator','form','submissionTable'));
    }
    
      public function pendingMarks(){
        $paginator=Assignment::get();
        //dd($paginator);
        $title="This is a Page for Pending Assignment Grade";
        return view('admin.assignment.pending',compact('title','paginator'));
    }
    

}
