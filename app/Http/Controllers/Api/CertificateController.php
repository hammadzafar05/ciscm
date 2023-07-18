<?php

namespace App\Http\Controllers\Api;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Student;
use App\Test;
use App\V2\Model\AttendanceTable;
use App\V2\Model\CertificateLessonTable;
use App\V2\Model\CertificateTable;
use App\V2\Model\CertificateTestTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentCertificateTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\StudentTestTable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use App\Lib\HelperTrait;

class CertificateController extends Controller
{
    use HelperTrait;


    public function certificates(Request $request){

        $params= $request->all();
        $table = new StudentSessionTable();
        $id= $this->getApiStudentId();
        $rowsPerPage = 30;


        $total = $table->getTotalCertificates($id);
        $totalPages = ceil($total/$rowsPerPage);

        $page= !empty($params['page']) ? $params['page']:1;


        $records = [];

        if($page <= ($totalPages)){

            $paginator = $table->getCertificates(true,$id);
            $paginator->setCurrentPageNumber((int)$page);
            $paginator->setItemCountPerPage($rowsPerPage);

            foreach($paginator as $row){
                $row->session_name = $row->name;
                $data= $row;
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


    public function getCertificate(Request $request,$id){
        $certificateTable = new CertificateTable();
	    
        if(!$this->canAccessCertificate($id)){
            return jsonResponse([
                'status'=>false,
                'msg'=> __lang('not-met-requirements')
            ]);
        }


        if(!$this->canDownload($id)){
            return jsonResponse([
                'status'=>false,
                'msg'=> __lang('exceeded-max-downloads')
            ]);
        }

        $url= $this->getBaseApiUrl($request);


        $html = $this->getCertificateHtml($id);
        //$html= str_ireplace($url,'./public',$html);


        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $row= $certificateTable->getRecord($id);
        $orientation = ($row->orientation=='p')?'portrait':'landscape';

        $dompdf->setPaper('A4', $orientation);
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream(safeUrl($row->name).'.pdf');

        exit();

    }

    public function getCertificateHtml($id){
        $certificateTable = new CertificateTable();
        $sessionLessonTable = new SessionLessonTable();
        $attendanceTable = new AttendanceTable();
        $studentCertificateTable= new StudentCertificateTable();
        //add student record

        $sessionTable = new SessionTable();
        $row = $certificateTable->getRecord($id);
        $sessionRow = $sessionTable->getRecord($row->course_id);
        $student = $this->getApiStudent();

        $studentCertificate = $studentCertificateTable->addStudentEntry($this->getApiStudentId(),$id);
        $name = $student->user->name.' '.$student->user->last_name;
        $elements = [
            'student_name'=>$name,
            'session_name'=>$sessionRow->name,
            'session_start_date'=>showDate('d/M/Y',$sessionRow->start_date),
            'session_end_date'=>showDate('d/M/Y',$sessionRow->end_date),
            'date_generated'=> date('d/M/Y'),
            'company_name'=> setting('general_site_name'),
            'certificate_number' => $studentCertificate->tracking_number
        ];
        //get lessons for session
        $lessons = $sessionLessonTable->getSessionRecords($row->course_id);

        foreach($lessons as $lesson){
            if(!empty($row->any_session)){
                $date = $attendanceTable->getStudentLessonDate($this->getApiStudentId(),$lesson->lesson_id);
            }
            else{
                $date = $attendanceTable->getStudentLessonDateInSession($this->getApiStudentId(),$lesson->lesson_id,$row->course_id);
            }
            if(empty($date)){
                $date = 'N/A';
            }
            $elements['class_date_'.$lesson->lesson_id.'_'.strtoupper(safeUrl($lesson->name))]=$date;
        }

        $html = $row->html;



        foreach($elements as $key=>$value){
            $key = strtoupper($key);
            $html = str_replace("[$key]",$value,$html);
        }

        return $html;
    }

    public function canDownload($certificateid){
        $certificateTable = new CertificateTable();
        $certificateRow = $certificateTable->getRecord($certificateid);
        $studentId= $this->getApiStudentId();
        $student  = Student::find($studentId);

        $totalAllowed = $certificateRow->max_downloads;
        $totalDownloaded = $student->studentCertificateDownloads->count();

        if($totalDownloaded >= $totalAllowed && $totalAllowed > 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function canAccessCertificate($certificateid){
        $certificateTable = new CertificateTable();
        $certificateLessonTable = new CertificateLessonTable();
        $certificateTestTable = new CertificateTestTable();
        $certificateAssignmentTable = new \App\V2\Model\CertificateAssignmentTable();
        $studentSessionTable = new StudentSessionTable();
        $attendanceTable = new AttendanceTable();
        $studentTestTable = new StudentTestTable();
        $studentAssignmentTable = new \App\V2\Model\AssignmentSubmissionTable();

        $certificateRow = $certificateTable->getRecord($certificateid);
        $studentId= $this->getApiStudentId();
        //check that certificate is active
        if(empty($certificateRow->enabled)){
            return false;
        }

        //check that student is enrolled in session
        if(!$studentSessionTable->enrolled($this->getApiStudentId(),$certificateRow->course_id)){
            return jsonResponse([
                'status'=>false,
                'msg'=>__lang('certificate-download-error')
            ]);
        }

        //check for attendance
        if($certificateLessonTable->getTotalForCertificate($certificateid)>0){

            $mClasses = [];
            $rowset = $certificateLessonTable->getCertificateLessons($certificateid);
            foreach($rowset as $row){
                $mClasses[] = $row->lesson_id;
            }

            if(empty($certificateRow->any_session)){
                $status = $attendanceTable->hasClassesInSession($studentId,$certificateRow->course_id,$mClasses);
            }
            else{
                $status = $attendanceTable->hasClasses($studentId,$mClasses);
            }

            if(!$status){

                return jsonResponse([
                    'status'=>false,
                    'msg'=>__lang('outstanding-classes')
                ]);

            }


        }

        if($certificateTestTable->getTotalForCertificate($certificateid)>0){
            $rowset = $certificateTestTable->getCertificateRecords($certificateid);
            foreach($rowset as $row)
            {
                $passedTest = $studentTestTable->passedTest($studentId,$row->test_id);
                if(!$passedTest){
                    $testRecord = Test::find($row->test_id);

                    return jsonResponse([
                        'status'=>false,
                        'msg'=>__lang('need-take-test',['test'=>$testRecord->name])
                    ]);

                }
            }


        }


        if($certificateAssignmentTable->getTotalForCertificate($certificateid)>0){
            $rowset = $certificateAssignmentTable->getCertificateRecords($certificateid);
            foreach($rowset as $row)
            {
                $passedAssignment = $studentAssignmentTable->passedAssignment($studentId,$row->assignment_id);
                if(!$passedAssignment){
                    $assignmentRecord = Assignment::find($row->assignment_id);

                    return jsonResponse([
                        'status'=>false,
                        'msg'=> __lang('assignment-needed',['assignment'=>$assignmentRecord->title])
                    ]);

                }
            }


        }


        return true;

    }



}
