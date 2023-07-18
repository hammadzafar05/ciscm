<?php

namespace App\Http\Controllers\Admin;

use App\Certificate;
use App\Course;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Lib\HelperTrait;
use App\Test;
use App\V2\Form\CertificateFilter;
use App\V2\Form\CertificateForm;
use App\V2\Form\EditCertificateForm;
use App\V2\Model\AssignmentTable;
use App\V2\Model\CertificateAssignmentTable;
use App\V2\Model\CertificateLessonTable;
use App\V2\Model\CertificateTable;
use App\V2\Model\CertificateTestTable;
use App\V2\Model\LessonTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentCertificateTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\TestTable;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $table = new CertificateTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Certificates'),
            'articleTable'=>$table
        ));
    }



    public function add(Request $request)
    {
        $output = array();
        $lessonTable = new CertificateTable();
        $form = new CertificateForm(null,$this->getServiceLocator());
        $filter = new CertificateFilter();

        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $form->setData($data);
            if ($form->isValid()) {

                $array = $form->getData();
                $array[$lessonTable->getPrimary()]=0;
                $id= $lessonTable->saveRecord($array);
                flashMessage(__lang('record-added!'));

                return adminRedirect(['controller'=>'certificate','action'=>'edit','id'=>$id]);


            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');
                if ($data['image']) {
                    $output['display_image']= resizeImage($data['image'], 100, 100,$this->getBaseUrl());
                }

                    $output['no_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());

            }

        }
        else{
            $output['no_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
            $output['display_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        }
        $output['form'] = $form;
        $output['pageTitle']= __lang('Add Certificate');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }



    public function edit(Request $request,$id){
        $certificateTable = new CertificateTable();
        $sessionTable = new SessionTable();
        $lessonTable = new LessonTable();
        $testTable = new TestTable();
        $assignmentTable = new AssignmentTable();
        $sessionLessonTable = new SessionLessonTable();
        $certificateLessonTable = new CertificateLessonTable();
        $certificateTestTable = new CertificateTestTable();
        $certificateAssignmentTable = new CertificateAssignmentTable();
        $form = new EditCertificateForm(null,$this->getServiceLocator(),$id);
        $filter = new CertificateFilter();
        $output= [];
        $certificate = Certificate::find($id);
		$course_id = $certificate->course_id;
		
		
        //get session list


        $form->setInputFilter($filter);
        if(request()->isMethod('post'))
        {
            $formData = request()->all();

            $form->setData($formData);


            if($form->isValid()){
	
	            $student_ids = array();
	            $StudentSessionTable = new StudentSessionTable();
	            $studentCertificateTable= new StudentCertificateTable();
				
	            $enrolled_students = $StudentSessionTable->getSessionRecords(false,$course_id,true);
				foreach ($enrolled_students as $student){
					$student_ids[] = $student->student_id;
					$studentCertificate = $studentCertificateTable->addStudentEntry($student->student_id,$id);
				}
	
	            //dd($student_ids);
				
                $data= removeNull($form->getData());

                $newSrc = $this->getBaseUrl().'/'.$data['image'];

                $certificateRow = $certificateTable->getRecord($id);

                $html = $data['html'];

                if(empty($html)){
                    $html = $certificateRow->html;
                }

                if(!empty($html)){
                    $dom = new \DOMDocument();
                    //$dom->loadHTML($html);
                    $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

                    $new_elm = $dom->createElement('style', '* { font-family: DejaVu Sans, sans-serif; }');
                    $elm_type_attr = $dom->createAttribute('type');
                    $elm_type_attr->value = 'text/css';
                    $new_elm->appendChild($elm_type_attr);

                    $head = $dom->createElement('head');

                    $head->appendChild($new_elm);

                    $html = $dom->getElementsByTagName('html')->item(0);
                    $body = $dom->getElementsByTagName('body')->item(0);
                    $html->insertBefore($head,$body);


                    foreach ($dom->getElementsByTagName('img') as $img) {
                        // put your replacement code here
                        $img->setAttribute( 'src', $newSrc );
                    }

                    $data['html'] = $dom->saveHTML();

                    $html = $data['html'];
                    while(preg_match('#inset#',$html)){

                        $pos = stripos($html,'inset:');

                        $append = substr($html,$pos);

                        $pos = stripos($append,';');
                        $insetLine= substr($append,0,$pos);

                        $cleanInset = str_ireplace('inset:','',$insetLine);
                        $cleanInset = str_ireplace('auto','',$cleanInset);
                        $cleanInset = trim($cleanInset);

                        $positions  = explode(' ',$cleanInset);

                        $top = $positions[0];
                        $left = end($positions);

                        $newCssRule = "top: {$top}; left: {$left}";

                        $html = str_ireplace($insetLine,$newCssRule,$html);


                    }
                    $data['html'] = $html;

                }




                if($certificateRow->course_id != $data['course_id'] || $certificateRow->orientation != $data['orientation']){
                    $data['html']='';
                }



                //remove lesson records
                foreach($data as $key=>$value){
                    if(preg_match('#lesson_#',$key)){
                        unset($data[$key]);
                    }
                }


                $certificateTable->update($data,$id);

                //now save classes
                $certificateLessonTable->clearCertificateRecords($id);

                $classes = [];
                foreach($formData as $key=>$value){
                    if(preg_match('#lesson_#',$key) && !empty($value)){
                    /*    $certificateLessonTable->addRecord([
                            'certificate_id'=>$id,
                            'lesson_id'=>$value
                        ]);*/
                        $classes[] = $value;
                    }
                }
                $certificate->lessons()->sync($classes);

                $certificateTestTable->clearCertificateRecords($id);
                foreach($formData as $key=>$value){
                    if(preg_match('#test_#',$key) && !empty($value)){
                        $certificateTestTable->addRecord([
                            'certificate_id'=>$id,
                            'test_id'=>$value
                        ]);
                    }
                }

                $certificateAssignmentTable->clearCertificateRecords($id);
                foreach($formData as $key=>$value){
                    if(preg_match('#assignment_#',$key) && !empty($value)){
                        $certificateAssignmentTable->addRecord([
                            'certificate_id'=>$id,
                            'assignment_id'=>$value
                        ]);
                    }
                }

                flashMessage(__lang('Changes Saved!'));
                return redirect()->route('admin.certificate.index');
            }
            else{
                $output['flash_message']=$this->getFormErrors($form);
            }


            $form->populateValues($formData);
        }
        else {


            $row = $certificateTable->getRecord($id);
            $row = Certificate::find($id);

          //  $data = getObjectProperties($row);
            $data = $row->toArray();


            $lessons = $certificateLessonTable->getCertificateLessons($id);
            foreach($lessons as $row2){
                $data['lesson_'.$row2->lesson_id]=$row2->lesson_id;
            }


            $form->setData($data);

        }
        $row = $certificateTable->getRecord($id);

        //add lesson records to form here
        $rowset = $sessionLessonTable->getSessionRecords($row->course_id);
        $form->translate = false;
        foreach($rowset as $row2){
            $form->createCheckbox('lesson_'.$row2->lesson_id,$row2->name,$row2->lesson_id);
            if($certificateLessonTable->hasLesson($id,$row2->lesson_id))
            {

                $form->get('lesson_'.$row2->lesson_id)->setAttribute('checked','checked');
            }

        }
        $form->translate = true;


        //get lessons for session
        $lessons = $sessionLessonTable->getSessionRecords($row->course_id);

        //get tests for session
        $tests = $certificateTestTable->getCertificateRecords($id);
        $assignments = $certificateAssignmentTable->getCertificateRecords($id);

        if($row->orientation=='p'){
            $width = 595;
            $height = 842;
        }
        else{
            $width = 842;
            $height = 595;
        }

        $output['allTests'] = $this->getSessionTestsObjects($row->course_id);
        $output['allAssignments'] = $assignmentTable->getPaginatedRecords(false, $row->course_id);



        $output = array_merge($output,[
            'row'=>$row,
            'pageTitle'=>__lang('Edit Certificate').': '.$row->name,
            'certificateLessonTable'=>$certificateLessonTable,
            'lessons'=>$lessons,
            'tests'=>$tests,
            'assignments'=>$assignments,
            'width'=>$width,
            'height'=>$height,
            'siteUrl'=>$this->getBaseUrl(),
            'form' => $form,
            'action'=>'edit',
            'id'=>$id,
            'certificate'=>$certificate
        ]);



        if ($row->image && file_exists(DIR_MER_IMAGE . $row->image) && is_file(DIR_MER_IMAGE . $row->image)) {
            $output['display_image'] = resizeImage($row->image, 100, 100,$this->getBaseUrl());
        } else {
            $output['display_image'] = resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        }


        $output['no_image']= $this->getBaseUrl().'/'.resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());


        return view('admin.certificate.edit',$output);
    }

    public function fix(Request $request){


        $certificates = Certificate::get();
        foreach($certificates as $certificateRow){
            $newSrc = $this->getBaseUrl().'/'.$certificateRow->image;

            $html = $certificateRow->html;
            if(empty($html)){
                continue;
            }
            $dom = new \DOMDocument();
            //$dom->loadHTML($html);
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

            foreach ($dom->getElementsByTagName('img') as $img) {
                // put your replacement code here
                $img->setAttribute( 'src', $newSrc );
            }

            $certificateRow->html = $dom->saveHTML();
            $certificateRow->save();


        }

        exit('Fixed all');

    }

    public function reset(Request $request,$id)
    {
        $certificateTable = new CertificateTable();
        $certificateTable->update(['html'=>''],$id);
        session()->flash('flash_message',__lang('certificate-reset'));
        return adminRedirect(['controller'=>'certificate','action'=>'edit','id'=>$id]);

    }

    public function loadclasses(Request $request,$id){

        $sessionLessonTable= new SessionLessonTable();

        $rowset = $sessionLessonTable->getSessionRecords($id);

        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['rowset'=>$rowset]);

        return $viewModel;
    }



    public function delete(Request $request,$id){
        $table = new CertificateTable();
        $table->deleteRecord($id);
        session()->flash('flash_message',__lang('Record deleted'));
        return adminRedirect(['controller'=>'certificate','action'=>'index']);

    }

    public function duplicate(Request $request,$id){

        //get tables
        $certificateTable = new CertificateTable();
        $certificateLessonTable = new CertificateLessonTable();
        $certificateTestTable = new CertificateTestTable();

        //now get session records
        $certiciateRow = $certificateTable->getRecord($id);
        $certificateLessonRowset = $certificateLessonTable->getCertificateLessons($id);
        $certificateTestRowset = $certificateTestTable->getCertificateTests($id);

        //create row
        $certificateArray= getObjectProperties($certiciateRow);
        unset($certificateArray['id']);
        $newId = $certificateTable->addRecord($certificateArray);

        //now get lessons
        foreach($certificateLessonRowset as $row){
            $data = getObjectProperties($row);

            $data['certificate_id']=$newId;
            $certificateLessonTable->addRecord($data);
        }

        //get instructors
        foreach($certificateTestRowset as $row){
            $data = getObjectProperties($row);

            $data['certificate_id']=$newId;
            $certificateTestTable->addRecord($data);
        }

        session()->flash('flash_message',__lang('certificate-duplicated'));
        return adminRedirect(array('controller'=>'certificate','action'=>'index'));


    }


    public function students(Request $request,$id){

        $certificate= Certificate::find($id);
		
        $studentCertificates= $certificate->studentCertificates()->orderBy('id','desc')->paginate(30);
        $total = $certificate->studentCertificates()->count();

        return view('admin.certificate.students',['students'=>$studentCertificates,'total'=>$total,'pageTitle'=>$certificate->name.' ('.$total.')']);

    }


    public function track(Request $request){

        $studentSessionTable= new StudentCertificateTable();
        $filter = $request->get('query');

        if(!empty($filter)){
            $paginator = $studentSessionTable->searchStudents($filter);

            $paginator->setCurrentPageNumber((int)request()->get('page', 1));
            $paginator->setItemCountPerPage(30);
        }
        else{
            $paginator = false;
        }

        return view('admin.certificate.track',['paginator'=>$paginator,'pageTitle'=>__lang('Track Certificate')]);

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
