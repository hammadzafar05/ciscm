<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/16/2017
 * Time: 12:46 PM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;


use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AssignmentTable;
use App\V2\Model\StudentSessionTable;
use App\Lib\BaseForm;
use Laminas\Form\Element\File;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\File\Extension;
use Laminas\Validator\File\Size;

class AssignmentController extends Controller {

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
        $path = $this->uploadDir;
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
    }

    public function index(Request $request){

        $studentId = $this->getId();
        $studentSessionTable = new StudentSessionTable();
        $submissionTable = new AssignmentSubmissionTable();

        $paginator = $studentSessionTable->getAssignments($studentId);
        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
	    
        return view('student.assignment.index',[
            'pageTitle'=>__lang('Homework'),
            'paginator'=>$paginator,
            'submissionTable' => $submissionTable,
            'total'=> $studentSessionTable->getTotalAssignments($studentId)
        ]);
    }

    private function validateAssignment($id){
        $assignmentTable = new AssignmentTable();
        $studentSessionTable = new StudentSessionTable();
        $assignmentRow = $assignmentTable->getRecord($id);


        if( $assignmentRow->allow_late != 1 && !empty($assignmentRow->due_date)  && $assignmentRow->due_date < Carbon::now()){

            flashMessage(__lang('ass-past-due-date'));
            return back();
        }

        if(!$studentSessionTable->enrolled($this->getId(),$assignmentRow->course_id)){
            flashMessage(__lang('you-not-enrolled'));
            return back();
        }

    }

    public function submit(Request $request,$id){
        $courseUrl = session()->get('course');

        $output = [];
        $assignmentTable = new AssignmentTable();
        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentRow = $assignmentTable->getRecord($id);

        $form = $this->getForm($id);

        $this->validateAssignment($id);

        //check if student has submitted assignment
        if($assignmentSubmissionTable->hasSubmission($this->getId(),$id)){

            $submissionRow = $assignmentSubmissionTable->getAssignment($id,$this->getId());
            if($this->canEdit($submissionRow->id)){
                flashMessage(__lang('already-submitted-msg'));
                return redirect()->route('student.assignment.edit',['id'=>$submissionRow->id]);

            }
            else{
                flashMessage(__lang('ass-no-edit-msg'));
                return back();
            }

        }


        if(request()->isMethod('post')){

            $formData = request()->all();
           //dd($formData);

            $form->setData(array_merge_recursive(
                request()->all(),
                $_FILES
            ));
            //dd($form);
            if($form->isValid()){
                $data = $form->getData();
        //dd($data);     
                //handle file upload
                if($assignmentRow->type=='f' || $assignmentRow->type=='b'){
                    $file = $data['file_path']['name'];
                    $newPath = $this->uploadDir.'/'.time().$this->getId().'_'.sanitize($file);
                    $this->makeUploadDir();
                    rename($data['file_path']['tmp_name'],$newPath);

                    try{
                        chmod($newPath,0644);
                    }
                    catch(\Exception $ex){

                    }


                    $data['file_path'] = $newPath??"";
                }
                if (isset($data['content'])){
                    $content = $this->saveInlineImages($data['content'],$this->getBaseUrl());
                }
                else{
                    $content = '';
                }
                
             
            


                $data['content'] = clean($content);
                $data['student_id'] = $this->getId();
                $data['assignment_id'] = $id;
                $data['editable'] = 1;
                if ($assignmentRow->passmark == 0){
	                $data['editable'] = 0;
	                $data['grade'] = 100;
                }

                $aid = $assignmentSubmissionTable->addRecord($data);
                if($data['submitted']==1 && !empty($aid)){
                    $student = $this->getStudent();
                    $message = $student->user->name.' '.$student->user->last_name.' '.__lang('just-submitted-msg').' "'.$assignmentRow->title.'"';
                    $this->notifyAdmin($assignmentRow->admin_id,__lang('New homework submission'),$message);
                }
                flashMessage(__lang('you-successfully-submitted'));
                if(!empty($courseUrl))
                {
                    $url = $courseUrl;
                    session()->remove('course');
                    return redirect($url);
                }

                return redirect()->route('student.assignment.submissions');

            }
            else{
                $output['message'] = $this->getFormErrors($form);
            }



        }

        return view('student.assignment.submit',array_merge([
            'pageTitle'=>__lang('Submit Homework').': '.$assignmentRow->title,
            'form'=>$form,
            'row'=>$assignmentRow
        ],$output));

    }

    public function submissions(Request $request){
        $studentId = $this->getId();
        $assignmentSubmissionsTable = new AssignmentSubmissionTable();

        $paginator = $assignmentSubmissionsTable->getStudentPaginatedRecords(true,$studentId);
        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);

        return view('student.assignment.submissions',[
            'pageTitle'=>__lang('My Homework Submissions'),
            'paginator'=>$paginator,
        ]);
    }

    private function canEdit($id){

        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        //get assignment
        $row = $assignmentSubmissionTable->getRecord($id);
        $assignmentRow = $assignmentTable->getRecord($row->assignment_id);
        $time = Carbon::now();
        if(empty($row->editable) || ( $assignmentRow->allow_late != 1 && $assignmentRow->due_date < $time)){
            return false;
        }
        else{
            return true;
        }

    }

    public function edit(Request $request,$id){

        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        //get assignment
        $row = $assignmentSubmissionTable->getRecord($id);
        $assignmentRow = $assignmentTable->getRecord($row->assignment_id);
       if(!$this->canEdit($id)){
           flashMessage(__lang('sorry-no-edit'));
           return back();
       }

        $form = $this->getForm($assignmentRow->id,false);

      if ($form->has('file_path')){
          $form->get('file_path')->setAttribute('required','');
      }
       
      //for pdf
    //   if ($form->has('file_path_pdf')){
    //       $form->get('file_path_pdf')->setAttribute('required','');
    //   }



        if(request()->isMethod('post')){

            $formData = request()->all();

            $form->setData(array_merge_recursive(
                request()->all(),
                $_FILES
            ));

            if($form->isValid()){
                $data = $form->getData();

                //handle file upload
                if(($assignmentRow->type=='f' || $assignmentRow->type=='b') && !empty($data['file_path']['name'])){
                    //remove old file
                    @unlink($row->file_path);
                    $file = $data['file_path']['name'];
                    $newPath = $this->uploadDir.'/'.time().$this->getId().'_'.sanitize($file);
                    $this->makeUploadDir();
                    rename($data['file_path']['tmp_name'],$newPath);


                    try{
                        chmod($newPath,0644);
                    }
                    catch(\Exception $ex){

                    }


                    $data['file_path'] = $newPath;

                }
                else{
                    unset($data['file_path']);
                }
                
               
                
                //handle file upload pdf
                // if(($assignmentRow->type=='f' || $assignmentRow->type=='b') && !empty($data['file_path_pdf']['name'])){
                //     //remove old file
                //     @unlink($row->file_path_pdf);
                //     $file = $data['file_path_pdf']['name'];
                //     $newPath = $this->uploadDir.'/'.time().$this->getId().'_'.sanitize($file);
                //     $this->makeUploadDir();
                //     rename($data['file_path_pdf']['tmp_name'],$newPath);


                //     try{
                //         chmod($newPath,0644);
                //     }
                //     catch(\Exception $ex){

                //     }


                //     $data['file_path_pdf'] = $newPath;

                // }
                // else{
                //     unset($data['file_path_pdf']);
                // }


                $assignmentSubmissionTable->update($data,$id);
                flashMessage(__lang('you-successfully-edited'));
                return redirect()->route('student.assignment.submissions');

            }
            else{
                    $message = $this->getFormErrors($form);
                    flashMessage($message);
            }

        }
        else{
            $data = getObjectProperties($row);
            $form->setData($data);

        }
        //dd($form);
        $pageTitle = __lang('Edit Assignment').': '.$assignmentRow->title;


        $data= compact('pageTitle','assignmentRow','form');
        $data['row'] = $assignmentRow;
        if(!empty($row->file_path)){
            $data['file']  = basename($row->file_path);
        }
        //for pdf
        // if(!empty($row->file_path_pdf)){
        //     $data['file_name']  = basename($row->file_path_pdf);
        // }
        $viewModel = viewModel('student',__CLASS__,'submit',$data);

        return $viewModel;

    }

    public function delete(Request $request,$id){

        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $assignmentTable = new AssignmentTable();

        //get assignment
        $row = $assignmentSubmissionTable->getRecord($id);
        $assignmentRow = $assignmentTable->getRecord($row->assignment_id);
        if(empty($row->editable) || ( $assignmentRow->allow_late!=1 && $assignmentRow->due_date < Carbon::now())){
            flashMessage(__lang('sorry-no-delete'));
            return back();
            exit();
        }

        $this->validateOwner($row);

        if(!empty($row->file_path)){
            @unlink($row->file_path);
        }
        //pdf
        //  if(!empty($row->file_path_pdf)){
        //     @unlink($row->file_path_pdf);
        // }


        $assignmentSubmissionTable->deleteRecord($id);
        flashMessage(__lang('Assignment deleted'));
        return back();

    }

    public function view(Request $request,$id){

        $assignmentSubmissionTable = new AssignmentSubmissionTable();
        $row = $assignmentSubmissionTable->getSubmission($id);
        $viewModel= viewModel('student',__CLASS__,__FUNCTION__,['row'=>$row]);

        return $viewModel;
    }

    private function getForm($id,$fileRequired=true){
        $assignmentTable = new AssignmentTable();
        $assignmentRow = $assignmentTable->getRecord($id);
        $form = new BaseForm();

        if($assignmentRow->type=='t' || $assignmentRow->type=='b'){
            $form->createTextArea('content','Your Answer',true);
            $form->get('content')->setAttribute('class','summernote form-control');

        }

        if($assignmentRow->type=='f' || $assignmentRow->type=='b' ){
            $file = new File('file_path');
            $file->setLabel(__lang('Your File'))
                ->setAttribute('id','file_path')
                ->setAttribute('required','required');
            $form->add($file);
        }
        
        
            // $file = new File('file_path_pdf');
            // $file->setLabel(__lang('Your File'))
            //     ->setAttribute('id','file_path_pdf')
            //     ->setAttribute('required','required');
            // $form->add($file);
        

        $form->createSelect('submitted','Status',['1'=>__lang('Submitted'),'0'=>__lang('Draft')],true,false);
        $form->createTextArea('student_comment','Additional Comments (optional)',false);

        $form->setInputFilter($this->getFilter($id,$fileRequired));
        return $form;

    }

    private function getFilter($id,$fileRequired){
        $assignmentTable = new AssignmentTable();
        $assignmentRow = $assignmentTable->getRecord($id);
        $filter = new InputFilter();

        if($assignmentRow->type=='t' || $assignmentRow->type=='b'){
            $filter->add([
                'name'=>'content',
                'required'=>true,
                'validators'=>[
                    [
                        'name'=>'NotEmpty'
                    ]
                ]
            ]);
        }

        if($assignmentRow->type=='f' || $assignmentRow->type=='b' ){

            $input = new Input('file_path');
            $input->setRequired($fileRequired);
            $input->getValidatorChain()
                ->attach(new Size(5000000))
                ->attach(new Extension('jpg,xls,csv,xlsx,txt,doc,docx,pptx,pdf,ppt,png,jpeg'));

            $filter->add($input);
        }
        
        //for pdf
        

            // $input = new Input('file_path_pdf');
            // $input->setRequired($fileRequired);
            // $input->getValidatorChain()
            //     ->attach(new Size(5000000))
            //     ->attach(new Extension('jpg,mp4,mp3,avi,xls,7z,mdb,mdbx,csv,xlsx,txt,zip,doc,docx,pptx,pdf,ppt,png,gif,jpeg,rar'));

            // $filter->add($input);
        

        $filter->add([
            'name'=>'submitted',
            'required'=>false
        ]);

        $filter->add([
            'name'=>'student_comment',
            'required'=>false
        ]);

        return $filter;
    }




}
