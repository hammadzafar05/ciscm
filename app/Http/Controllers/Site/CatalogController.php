<?php

namespace App\Http\Controllers\Site;

use App\Admin;
use App\Course;
use App\CourseCategory;
use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Lib\BaseTable;
use App\Lib\HelperTrait;
use App\Model\ApplicationForm;
use App\StudentField;
use App\User;
use App\V2\Form\DiscussionForm;
use App\V2\Model\CountryTable;
use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\LectureTable;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laminas\Form\Element;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Illuminate\Support\Facades\Mail;
class CatalogController extends Controller
{
	use HelperTrait;
	
	public function timetables(){
		$timetable = array();
		$table = new SessionTable();
		$paginator = $table->getPaginatedCourseRecords(FALSE);
		$iteration = 0;
		foreach ($paginator as $page){
			//dd($page);
			$image = AppHelper::imageExits($page->picture);
			
			$course_name = explode(' - Batch ',$page->name);
			$course_name = @$course_name[0];
			
			if ($page->start_date) {
				$iteration++;
				$timetable[] = array(
					'name' => $course_name,
					'image' => $image,
					'date' => date('d',strtotime($page->start_date)),
					'month' => date('m',strtotime($page->start_date)),
					'year' => date('Y',strtotime($page->start_date)),
					'day' => date('F',strtotime($page->start_date)),
					'start_time' => '',
					'end_time' => '',
					'color' => $iteration,
					'description' => $page->description == null ? '' : $page->description,
				);
			}
		}
		
		
		return \Response::json($timetable);
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
		
		
		$paginator = $table->getPaginatedCourseRecords(true,null,true,$filter,$group,'website');
		
		
		
		
		$paginator->setCurrentPageNumber((int)request()->get('page', 1));
		$paginator->setItemCountPerPage(30);
		
		//$categories = $sessionCategoryTable->getLimitedRecords(100);
		$categories = CourseCategory::whereNull('parent_id')->orderBy('sort_order')->where('enabled',1)->limit(100)->get();
		//dd($categories);
		
		$pageTitle = __lang('Online Courses');
		$parent = null;
		if(!empty($group)){
			$categoryRow = $sessionCategoryTable->getRecord($group);
			$pageTitle .=': '.$categoryRow->name;
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
		
		
		return tview('site.catalog.courses',$output);
		
	}
	
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
		
		
		$paginator = $table->getPaginatedRecords(true,null,true,$filter,$group,$sort,['s','b'],true,null,'website');
		
		
		
		
		$paginator->setCurrentPageNumber((int)request()->get('page', 1));
		$paginator->setItemCountPerPage(30);
		
		
		$categories = CourseCategory::whereNull('parent_id')->orderBy('sort_order')->where('enabled',1)->limit(100)->get();
		
			$parent = null;
		if(!empty($group)){
			$categoryRow = $sessionCategoryTable->getRecord($group);
			$pageTitle .=': '.$categoryRow->name;
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
			'pageTitle'=>__lang('Upcoming Sessions'),
			'studentSessionTable'=>$studentSessionTable,
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
		
		
		return tview('site.catalog.sessions',$output);
		
		
		
	}
	
	
	
	public function course(Request $request,Course $course){
		
		$sessionTable = new SessionTable();
		$sessionLessonTable = new SessionLessonTable();
		$sessionLessonAccountTable = new SessionLessonAccountTable();
		$studentSessionTable = new StudentSessionTable();
		$sessionInstructorTable = new SessionInstructorTable();
		$studentLectureTable = new StudentLectureTable();
		$logTable = new StudentSessionLogTable();
		$enrolled = false;
		$resumeLink = null;
		
		
		$id = $course->id;
		$downloadSessionTable = new DownloadSessionTable();
		
		$row = $sessionTable->getRecord($id);
		$rowset = $sessionLessonTable->getSessionRecords($id);
		
		$admin_id = Admin::where('id',$row->admin_id)->first();
		$partner = User::find($admin_id->user_id);
		$logo = '';
		if ($partner->logo){
			$logo = asset('cdn/temp/'.$partner->logo);
		}
		
		//ensure it is an online course
		
		
		//get instructors
		$instructors = $sessionInstructorTable->getSessionRecords($id);
		
		//get downloads
		$downloads = $downloadSessionTable->getSessionRecords($id);
		
		//check if student has started course
		//get session tests
		$sessionTestTable  = new SessionTestTable();
		$tests = $sessionTestTable->getSessionRecords($id);
		
		$output = ['rowset'=>$rowset,'row'=>$row,'pageTitle'=>__lang('Course Details'),'table'=>$sessionLessonAccountTable,'id'=>$id,
			
			'studentSessionTable'=>$studentSessionTable,
			'instructors' => $instructors,
			'downloads'=>$downloads,
			'logo'=>$logo,
			'fileTable'=> new DownloadFileTable(),
			'enrolled'=>$enrolled,
			'tests'=>$tests,
			'questionTable'=>new TestQuestionTable(),
			'studentTest'=> new StudentTestTable(),
			'totalClasses'=> $sessionLessonTable->getSessionRecords($id)->count(),
			'course'=>$course
		];
		
		if($course->type=='c'){
			return tview('site.catalog.course',$output);
		}
		else{
			return tview('site.catalog.session',$output);
		}
	}
	
	
	public function application_form(){
		
		$countryTable = new CountryTable();
		$countries = $countryTable->getRecords();
		
		$table = new SessionTable();

		$paginator = $table->getPaginatedCourseRecords(false,null,true,null,null,'asc',['c','s','b']);

		$courses = array();
		foreach($paginator as $course){
			$course_name = explode(' - Batch ',$course->name);
			$course_name = @$course_name[0];
			
			$courses[] = $course_name;
		}
		
		$fields = StudentField::orderBy('sort_order')->where('enabled',1)->get();
		$captchaUrl = captcha_src();
		return tview('site.home.application_form',compact('captchaUrl','courses','fields','countries'));
	}
	
	public function application_form_create(Request $request){
	  
		$this->validate($request,[
			'name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255'],
			'mobile_number'=>'required',
			'captcha' => 'required|captcha'
		]);
		
		$data = ApplicationForm::create($request->all());
		$email = $request['email'];
                $messageData =[
                    'email'=>$request['email'],
                    'name'=>$request['name'],
                    'course_name'=>$request['course_name'],
                ];
                Mail::send('emails.enroll',$messageData,function($message) use ($email){
                    $message->to($email)->subject('Course enrollment Success');
        });
		return back()->with('flash_message',__('default.application-form-saved'));
	}
	
	/*--MARUF START--*/
	public function listCourses(Request $request){
		
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
		
		$sort = request()->get('sort', 'asc');
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
		
		
		$paginator = $table->getPaginatedCourseRecords(true,null,true,$filter,$group,$sort,['c','s','b'],'website');
		
		
		
		
		$paginator->setCurrentPageNumber((int)request()->get('page', 1));
		$paginator->setItemCountPerPage(3000);
		
		//$categories = $sessionCategoryTable->getLimitedRecords(100);
		$categories = CourseCategory::whereNull('parent_id')->orderBy('sort_order')->where('enabled',1)->limit(100)->get();
		
		$pageTitle = __lang('shomvabona-list-of-all-courses');
		$parent = null;
		if(!empty($group)){
			$categoryRow = $sessionCategoryTable->getRecord($group);
			$pageTitle .=': '.$categoryRow->name;
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
		
		
		return tview('site.catalog.all_courses',$output);
		
	}
	/*--MARUF END--*/
}
