<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 10/3/2017
 * Time: 2:12 PM
 */

namespace App\Http\Controllers\Student;
use App\Admin;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\User;
use App\V2\Form\DiscussionForm;
use App\V2\Model\LectureTable;
use Illuminate\Http\Request;


use App\CourseCategory;
use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\SessionCategoryTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionLessonAccountTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\SessionTestTable;
use App\V2\Model\StudentLectureTable;
use App\V2\Model\StudentSessionLogTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\StudentTestTable;
use App\V2\Model\TestQuestionTable;
use Illuminate\Support\Facades\Auth;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\View\Model\ViewModel;

class CatalogController extends Controller {
    use HelperTrait;



    /**
     * For browsing sessions
     */
    public function sessions(Request $request){


        $table = new SessionTable();
        $studentSessionTable = new StudentSessionTable();



        $filter = request()->get('filter', null);


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



        $text = new Text('filter');
        $text->setAttribute('class','form-control');
        $text->setAttribute('placeholder','Search');
        $text->setValue($filter);


        $sortSelect = new Select('sort');
        $sortSelect->setAttribute('class','form-control');
        //$sortSelect->setAttribute('style','max-width:100px');

        $valueOptions = [
            'recent'=>__lang('Recently Added'),
            'asc'=>__lang('Alphabetical (Ascending)'),
            'desc'=>__lang('Alphabetical (Descending)'),
            'date'=>__lang('Start Date'),
        ];

        if($this->getSetting('general_show_fee')==1){
            $valueOptions['priceAsc'] = __lang('Price (Lowest to Highest)');
            $valueOptions['priceDesc'] = __lang('Price (Highest to Lowest)');
        }

        $sortSelect->setValueOptions($valueOptions);
        $sortSelect->setEmptyOption('--'.__lang('Sort').'--');
        $sortSelect->setValue($sort);


        $groupTable = new SessionCategoryTable();
        $groupRowset = $groupTable->getLimitedRecords(100);


        $paginator = $table->getPaginatedRecords(true,null,true,$filter,$group,$sort,['s','b'],true);




        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);


        $role = getRole();

            $id = $this->getId();


        $output = array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Upcoming Sessions'),
            'studentSessionTable'=>$studentSessionTable,
            'id'=>$id,
            'filter'=>$filter,
            'group'=>$group,
            'text'=>$text,
            'sortSelect'=>$sortSelect,
            'sort'=>$sort,
        );


            return viewModel('student',__CLASS__,__FUNCTION__,$output);



    }

    /**
     * For browsing courses
     */
    public function courses(Request $request){

        $table = new SessionTable();
        $studentSessionTable = new StudentSessionTable();
        $sessionCategoryTable = new SessionCategoryTable();


        $filter = request()->get('filter', null);


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



        $text = new Text('filter');
        $text->setAttribute('class','form-control');
        $text->setAttribute('placeholder',__lang('Search'));
        $text->setValue($filter);


        $sortSelect = new Select('sort');
        $sortSelect->setAttribute('class','form-control');
        //$sortSelect->setAttribute('style','max-width:100px');

        $valueOptions = [
            'recent'=>__lang('Recently Added'),
            'asc'=>__lang('Alphabetical (Ascending)'),
            'desc'=>__lang('Alphabetical (Descending)'),
            'date'=>__lang('Start Date'),
        ];

        if($this->getSetting('general_show_fee')==1){
            $valueOptions['priceAsc'] = __lang('Price (Lowest to Highest)');
            $valueOptions['priceDesc'] = __lang('Price (Highest to Lowest)');
        }

        $sortSelect->setValueOptions($valueOptions);
        $sortSelect->setEmptyOption('--'.__lang('Sort').'--');
        $sortSelect->setValue($sort);


        $groupTable = new SessionCategoryTable();


        $paginator = $table->getPaginatedCourseRecords(true,null,true,$filter,$group,$sort,'c');




        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);


        $role = getRole();


            $id = $this->getId();

        //$categories = $sessionCategoryTable->getLimitedRecords(100);
        $categories = CourseCategory::whereNull('parent_id')->orderBy('sort_order')->where('enabled',1)->limit(100)->get();

        $pageTitle = __lang('Online Courses');
        $parent = null;
        if(!empty($group)){
            $categoryRow = $sessionCategoryTable->getRecord($group);
            $pageTitle .=': '.$categoryRow->category_name;
            $description = $categoryRow->description;
            //get sub categories
            $subCategories = CourseCategory::where('parent_id',$group)->orderBy('sort_order')->where('enabled',1)->get();
            if ($subCategories->count() ==0){
                $subCategories = null;
            }

            if(!empty($categoryRow->parent_id)){
                $parent = $sessionCategoryTable->getRecord($categoryRow->parent_id);
            }
        }
        else{
            $description = '';
            $subCategories = null;
        }



        $output = array(
            'paginator'=>$paginator,
            'pageTitle'=>$pageTitle,
            'studentSessionTable'=>$studentSessionTable,
            'id'=>$id,
            'filter'=>$filter,
            'group'=>$group,
            'text'=>$text,
            'sortSelect'=>$sortSelect,
            'sort'=>$sort,
            'categories'=>$categories,
            'description'=>$description,
            'subCategories'=>$subCategories,
            'parent'=>$parent
        );


            return viewModel('student',__CLASS__,__FUNCTION__,$output);


    }

    public function course(Request $request,$id){

        $role = getRole();
        $sessionTable = new SessionTable();
        $sessionLessonTable = new SessionLessonTable();
        $sessionLessonAccountTable = new SessionLessonAccountTable();
        $studentSessionTable = new StudentSessionTable();
        $sessionInstructorTable = new SessionInstructorTable();
        $studentLectureTable = new StudentLectureTable();
        $logTable = new StudentSessionLogTable();
        $enrolled = false;
        $resumeLink = null;
        $studentCourse = false;
        if(Auth::check() && $role->id==2) {
            $studentId = $this->getId();
            if ($studentSessionTable->enrolled($studentId, $id)) {
                $studentCourse = $this->getStudent()->studentCourses()->where('course_id',$id)->first();
            $enrolled = true;
                //check if student has started lecture
                if($studentLectureTable->hasLecture($studentId,$id)){
                    $lecture = $studentLectureTable->getLecture($studentId,$id);
                    if ($lecture){

                        $lectureId = $lecture->lecture_id;
                        //get next lecture
                         $lectureTable = new LectureTable();
                         $next = $lectureTable->getNextLecture($lecture->lecture_id);

                         if($next){
                             $lecture = $next;
                             $lectureId = $lecture->id;
                         }

                        if($lecture->sort_order == 1){
                          //  $resumeLink = $this->url()->fromRoute('view-class', ['classId' => $lecture->lesson_id, 'sessionId' => $id]);
                            $resumeLink = route('student.course.class',['lesson'=>$lecture->lesson_id,'course'=>$id]);
                        }
                        else{
                           // $resumeLink = $this->url()->fromRoute('view-lecture', ['lectureId' => $lecture->lecture_id, 'sessionId' => $id]);
                            $resumeLink = route('student.course.lecture',['lecture'=>$lectureId,'course'=>$id]);

                        }

                    }
                    else{
                        $resumeLink = route('student.course.intro',['id'=>$id]);

                    }

                }
                else{

                   // $resumeLink = $this->url()->fromRoute('application/default', ['controller' => 'course', 'action' => 'intro','id'=>$id]);
                        $resumeLink = route('student.course.intro',['id'=>$id]);

                }

            }

        }
        else{
            $studentId = 0;
        }

        $discussionForm= new DiscussionForm(null,$studentId);
        $downloadSessionTable = new DownloadSessionTable();

        $row = $sessionTable->getRecord($id);
	    /*--MARUF START--*/
	    $course_name = explode(' - Batch ',$row['name']);
	    $course_name = @$course_name[0];
	    $row['name'] = $course_name;
	    /*--MARUF END--*/
	    $rowset = $sessionLessonTable->getSessionRecords($id);
	
	
	
	    $admin_id = Admin::where('id',$row->admin_id)->first();
	    $partner = User::find($admin_id->user_id);
	    $logo = '';
	    if ($partner->logo){
		    $logo = asset('cdn/temp/'.$partner->logo);
	    }

        //ensure it is an online course
        if($row->type !='c'){
            return redirect()->route('student.session-details',['id'=>$row->id,'slug'=>safeUrl($row->name)]);
        }

        //get instructors
        $instructors = $sessionInstructorTable->getSessionRecords($id);

        //get downloads
        $downloads = $downloadSessionTable->getSessionRecords($id);

        //check if student has started course
        //get session tests
        $sessionTestTable  = new SessionTestTable();
        $tests = $sessionTestTable->getSessionRecords($id);

        $output = ['rowset'=>$rowset,'row'=>$row,'pageTitle'=>__lang('Course Details'),'table'=>$sessionLessonAccountTable,'id'=>$id,
            'studentId'=>$studentId,
            'studentSessionTable'=>$studentSessionTable,
            'instructors' => $instructors,
            'form'=>$discussionForm,
            'downloads'=>$downloads,
            'logo'=>$logo,
            'fileTable'=> new DownloadFileTable(),
            'resumeLink'=>$resumeLink,
            'enrolled'=>$enrolled,
            'tests'=>$tests,
            'questionTable'=>new TestQuestionTable(),
            'studentTest'=> new StudentTestTable(),
            'totalClasses'=> $sessionLessonTable->getSessionRecords($id)->count(),
            'studentCourse'=>$studentCourse
        ];
		//dd($output);


            return viewModel('student',__CLASS__,__FUNCTION__,$output);



    }


    public function getAuthService()
    {

        return $this->getServiceLocator()->get('StudentAuthService');
    }

}
