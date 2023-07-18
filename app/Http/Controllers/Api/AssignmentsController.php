<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use App\Assignment;
use App\AssignmentSubmission;
use App\Homework;
use App\Lesson;
use App\RevisionNote;
use App\Session;
use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AssignmentTable;
use App\V2\Model\HomeworkTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use Illuminate\Http\Request;
use App\Lib\HelperTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface as Response;

class AssignmentsController extends Controller
{
    use HelperTrait;

    protected $uploadDir;
    public function __construct(){


        $user= '';
        if(defined('USER_ID')){
            $user = '/'.USER_ID;
        }
        $this->uploadDir = 'usermedia'.$user.'/student_uploads/'.date('Y_m');



    }

    private function makeUploadDir(){
        $path = 'public/'.$this->uploadDir;
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
    }

    public function assignments(Request $request){
        
        $params= $request->all();

        $studentId = $this->getApiStudentId();

        $studentSessionTable = new StudentSessionTable();
        $submissionTable = new AssignmentSubmissionTable();

        $page= !empty($params['page']) ? $params['page']:1;

        $rowsPerPage=30;
        $total = $studentSessionTable->getTotalAssignments($studentId);
        $totalPages = ceil($total/$rowsPerPage);
        $records = [];
        if($page <= ($totalPages)){

            $paginator = $studentSessionTable->getAssignments($studentId);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage($rowsPerPage);
            

            foreach($paginator as $row){
                $data= $row;
                if (!empty($data['due_date'])){
                    $data['due_date'] = Carbon::parse($data['due_date'])->timestamp;
                }
                $data['created_on'] = Carbon::parse($row->created_at)->timestamp;
                $data['account_id'] = $row->admin_id;
                $data['session_name'] = $row->course_name;

                $data->has_submission = !empty($submissionTable->hasSubmission($studentId,$row->assignment_id));
                if($data->has_submission){
                    $submission = $submissionTable->getAssignment($row->assignment_id,$this->getApiStudentId());
                    $submission->assignment_submission_id = $submission->id;
                    $submission->created = Carbon::parse($submission->created_at)->timestamp;
                    $submission->modified = Carbon::parse($submission->updated_at)->timestamp;
                    $data->submission = $submission;
                }
                $records[] =$data;
            }
        }

        return jsonResponse([
            'total_pages'=>$totalPages,
            'current_page'=>$page,
            'total'=> $total,
            'rows_per_page'=>$rowsPerPage,
            'records'=>$records,
        ]);

    }

    public function getAssignment(Request $request,$id){

        $submissionTable = new AssignmentSubmissionTable();


        $data = Assignment::find($id)->toArray();

        if (!empty($data['due_date'])){
            $data['due_date'] = Carbon::parse($data['due_date'])->timestamp;
        }
        $data['created_on'] = Carbon::parse($data['created_at'])->timestamp;
        $data['account_id'] = $data['admin_id'];
        $data['session_id'] = $data['course_id'];
        $data['assignment_id'] = $data['id'];
        $data['assignment_type'] = $data['type'];

        $studentId= $this->getApiStudentId();
        $data['has_submission'] = !empty($submissionTable->hasSubmission($studentId,$data['id']));
        if($data['has_submission']){
            $submission =$submissionTable->getAssignment($data['id'],$this->getApiStudentId());
            $submission->assignment_submission_id = $submission->id;
            $submission->created = Carbon::parse($submission->created_at)->timestamp;
            $submission->modified = Carbon::parse($submission->updated_at)->timestamp;

            $data['submission'] = $submission;
        }

        return jsonResponse($data);
    }

    public function createSubmission(Request $request){
        $data = $request->all();

        $id = $data['assignment_id'];
        $assignmentTable = new AssignmentTable();
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentRow = $assignmentTable->getRecord($id);

        $this->validateAssignment($id);
        $output = [];
        $rules =[
            'content'=>'required',
            'assignment_id'=>'required'
        ];
        if($assignmentRow->type=='f' || $assignmentRow->type=='b'){
            $file = $_FILES['file_path']['name'];
            if(empty($file)){
                $rules['file']='required';
            }

        }

    //    $isValid= $this->validateGump($data,$rules);
        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return jsonResponse(['status'=>false,'msg'=>$validator->getMessageBag()->toArray()]);
        }

        if($assignmentSubmissionTable->hasSubmission($this->getApiStudentId(),$id)){

            $submissionRow = $assignmentSubmissionTable->getAssignment($id,$this->getApiStudentId());
            if($this->canEdit($submissionRow->id)){

                return jsonResponse([
                    'status'=>false,
                    'msg'=>__lang('mobile-ass-sub-msg1'),
                    'redirect'=>true,
                    'id'=>$submissionRow->id
                ]);

            }
            else{
                return jsonResponse([
                    'status'=>false,
                    'msg'=>__lang('mobile-ass-sub-msg2'),
                    'redirect'=>false,
                ]);

            }

        }


        //handle file upload
        if($assignmentRow->type=='f' || $assignmentRow->type=='b'){
            $file = $_FILES['file_path']['name'];


            if(!isValidUpload($_FILES['file_path']['tmp_name'])){
                return jsonResponse(['status'=>false,'msg'=>__lang('mobile-ass-sub-msg3') ]);

            }

            $newPath = $this->uploadDir.'/'.time().$this->getApiStudentId().'_'.sanitize($this->getApiStudent()->user->name.'_'.$this->getApiStudent()->user->last_name).'.'.getExtensionForMime($_FILES['file_path']['tmp_name']);
            $this->makeUploadDir();
            rename($_FILES['file_path']['tmp_name'],$newPath);

            try{
                chmod($newPath,644);
            }
            catch(\Exception $ex){

            }


            $data['file_path'] = $newPath;
        }
        //$uri = $request->getUri();
        // $baseUrl = $uri->getBaseUrl();

        // $content = $this->saveInlineImages($data['content'],$baseUrl);


        $data['content'] = nl2br(strip_tags($data['content']));
        $data['student_id'] = $this->getApiStudentId();
        $data['assignment_id'] = $id;
      /*  $data['created'] = time();
        $data['modified'] = time();*/
        $data['editable'] = 1;


        $aid = $assignmentSubmissionTable->addRecord($data);
        if($data['submitted']==1 && !empty($aid)){
            $student = $this->getApiStudent();
            $message = $student->user->name.' '.$student->user->last_name.' '.__lang('mobile-ass-sub-msg4').' "'.$assignmentRow->title.'"';
            $this->notifyAdmin($assignmentRow->admin_id,__lang('new-homework-submission'),$message);
        }

        return jsonResponse([
            'status'=>true,
            'record'=>AssignmentSubmission::find($aid)
        ]);

    }

    public function updateSubmission(Request $request,$id){


        $submissionId = $id;
        $data = $request->all();


        $assignmentTable = new AssignmentTable();
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $row = $assignmentSubmissionTable->getRecord($submissionId);

        $assignmentId = $row->assignment_id;
        $assignmentRow = $assignmentTable->getRecord($assignmentId);


        $output = [];
        $rules =[
            'content'=>'required',
        ];

        if($assignmentRow->type=='f' || $assignmentRow->type=='b'){
            $file = $_FILES['file_path']['name'];
            if(empty($file)){
                $rules['file']='required';
            }

        }


        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            return jsonResponse(['status'=>false,'msg'=>$this->getValidationErrors()]);
        }



        if(!$this->canEdit($submissionId)){


            return jsonResponse([
                'status'=>false,
                'msg'=>__lang('sorry-no-edit'),
                'redirect'=>false,
            ]);

        }




        //handle file upload
        if($assignmentRow->assignment_type=='f' || $assignmentRow->assignment_type=='b'){
            @unlink($row->file_path);


            $file = $_FILES['file_path']['name'];


            if(!isValidUpload($_FILES['file_path']['tmp_name'])){
                return jsonResponse(['status'=>false,'msg'=>__lang('mobile-ass-sub-msg3') ]);

            }

            $newPath = $this->uploadDir.'/'.time().$this->getApiStudentId().'_'.sanitize($this->getApiStudent()->user->name.'_'.$this->getApiStudent()->user->last_name).'.'.getExtensionForMime($_FILES['file_path']['tmp_name']);
            $this->makeUploadDir();
            rename($_FILES['file_path']['tmp_name'],$newPath);

            try{
                chmod($newPath,644);
            }
            catch(\Exception $ex){

            }


            $data['file_path'] = $newPath;


        }
//        $uri = $request->getUri();
//        $baseUrl = $uri->getBaseUrl();
//
//        $content = $this->saveInlineImages($data['content'],$baseUrl);
//
//        $data['content'] = sanitizeHtml($content);

        $data['content'] = nl2br(strip_tags($data['content']));
        $data['modified'] = time();

        $assignmentSubmissionTable->update($data,$submissionId);


        return jsonResponse([
            'status'=>true,
            'record'=>AssignmentSubmission::find($submissionId)
        ]);

    }

    public function deleteSubmission(Request $request,$id){

        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        //get assignment
        $row = $assignmentSubmissionTable->getRecord($id);
        $assignmentRow = $assignmentTable->getRecord($row->assignment_id);
        if(empty($row->editable) || ( $assignmentRow->allow_late!=1 && Carbon::parse($assignmentRow->due_date)->timestamp < time())){
            return jsonResponse([
                'status'=>false,
                'msg'=> __lang('sorry-no-delete')
            ]);

        }

        $this->validateApiOwner($row);

        if(!empty($row->file_path)){
            @unlink($row->file_path);
        }

        $assignmentSubmissionTable->deleteRecord($id);
        return jsonResponse([
            'status'=>true,
            'msg'=> __lang('homework-deleted'),
            'assignment'=>$assignmentRow
        ]);
    }


    private function validateAssignment($id){
        $assignmentTable = new AssignmentTable();
        $studentSessionTable = new StudentSessionTable();
        $assignmentRow = $assignmentTable->getRecord($id);

        if( $assignmentRow->allow_late!=1 && $assignmentRow->due_date < time()){
            return jsonResponse([
                'status'=>false,
                'msg'=>__lang('ass-past-due-date')
            ]);

        }

        if(!$studentSessionTable->enrolled($this->getApiStudentId(),$assignmentRow->course_id)){
            return jsonResponse([
                'status'=>false,
                'msg'=>__lang('you-not-enrolled')
            ]);

        }

    }

    private function canEdit($id){
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        //get assignment
        $row = $assignmentSubmissionTable->getRecord($id);
        $assignmentRow = $assignmentTable->getRecord($row->assignment_id);
        if(empty($row->editable) || ( $assignmentRow->allow_late!=1 && Carbon::parse($assignmentRow->due_date)->timestamp < time())){
            return false;
        }
        else{
            return true;
        }

    }

    public function revisionNotesSessions(Request $request)
    {


        $params = $request->all();

        $page = !empty($params['page']) ? $params['page'] : 1;

        $rowsPerPage = 30;

        $studentSessionTable = new StudentSessionTable();
        $studentId = $this->getApiStudentId();

        $total = $studentSessionTable->getTotalStudentForumRecords($studentId);

        $totalPages = ceil($total / $rowsPerPage);
        $records = [];

        if ($page <= $totalPages) {
            $paginator = $studentSessionTable->getStudentForumRecords(true, $studentId);
            $paginator->setCurrentPageNumber((int)$page);
            $paginator->setItemCountPerPage($rowsPerPage);

            foreach ($paginator as $row) {
                $row->total_notes = Course::find($row->course_id)->revisionNotes()->count();
                $row->session_name = $row->name;
                $row->session_date = Carbon::parse($row->start_date)->timestamp;
                $row->enrollment_closes = stamp($row->enrollment_closes);
                $row->session_type = $row->type;
                $row->session_id = $row->course_id;
                $records[] = $row;
            }

        }

        return jsonResponse([
            'total_pages' => $totalPages,
            'current_page' => $page,
            'total' => $total,
            'rows_per_page' => $rowsPerPage,
            'records' => $records,
        ]);

    }


    public function revisionNotes(Request $request){

        $params = $request->all();

        $this->validateParams($params,[
            'course_id'=>'required'
        ]);

        $table = new HomeworkTable();
        $sessionTable = new SessionTable();

        $id = $params['course_id'];

        if(!$this->enrolledInSession($id)){
            return jsonResponse(['status'=>false]);
        }

        /*
        $paginator = $table->getPaginatedRecords(true,$id);
        $session = $sessionTable->getRecord($id);

        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(30);
        */

        $rowset = RevisionNote::where('course_id',$id)->paginate(30);

        $data = $rowset->toArray();

        foreach($data['data'] as $key=>$value){
            $data['data'][$key]['homework_id'] = $data['data'][$key]['id'];
            $data['data'][$key]['session_id'] =$data['data'][$key]['course_id'];
            $data['data'][$key]['lesson_name'] = Lesson::find($value['lesson_id'])->name;
            $data['data'][$key]['date'] = stamp($data['data'][$key]['created_at']);
        }

        return jsonResponse($data);



    }

    public function getRevisionNote(Request $request,$id){

        $row = RevisionNote::find($id);
        $row->session_id = $row->course_id;
        if(!$this->enrolledInSession($row->course_id)){
            return jsonResponse(['status'=>false]);
        }

        return jsonResponse($row);
    }

    private function enrolledInSession($id){
        $studentSessionTable = new StudentSessionTable();
        return $studentSessionTable->enrolled($this->getApiStudentId(),$id);
    }


}
