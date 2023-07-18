<?php

namespace App\Http\Controllers\Site;

use App\Admin;
use App\Article;
use App\Course;
use App\Http\Controllers\Controller;
use App\Lib\BaseTable;
use App\Model\Page;
use App\Student;
use App\User;
use App\V2\Model\AttendanceTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentCertificateTable;
use App\Lib\CronJobs;
use App\Lib\HelperTrait;
use App\V2\Model\StudentTable;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	use HelperTrait;
	public function index(){
		
		//check if installation file exists and redirect to install if not
		if(!file_exists('../storage/installed')){
			return redirect('/install');
		}
		
		return tview('site.home.index');
	}
	
	public function article($slug){
		
		$article = Article::where('slug',$slug)->where('enabled',1)->first();
		if(!$article){
			return  abort(404);
		}
		
		
		return tview('site.home.article',compact('article'));
	}
	public function timetables(){
		$table = new SessionTable();
		$paginator = $table->getPaginatedCourseRecords(FALSE);
		foreach ($paginator as $page){
			dd($page);
		}
		
		$timetable[] = array(
			'name'=>'maruf',
			'image'=>'http://demo1.cloodo.com/script/tiva-timetable-php/admin/timetable/images/roger_hodgson.jpg',
			'date'=>'1',
			'month'=>'9',
			'year'=>'2021',
			'day'=>'Wednesday',
			'start_time'=>'09:30',
			'end_time'=>'11:30',
			'color'=>'4',
			'description'=>'description',
		);
		return \Response::json($timetable);
	}
	
	public function about(){
		return tview('site.home.about');
	}
	
	public function fully_online(){
		return tview('site.home.fully_online');
	}
	
	public function virtual_class_based(){
		return tview('site.home.virtual_class_based');
	}
	
	public function master_training_series(){
		return tview('site.home.master_training_series');
	}
	
	public function pageDetails()
	{
		$slug = \Request::route()->getName();
		$slug = str_replace('front-', '', $slug);
		$page = Page::where('slug', $slug)->first();
		//dd($page);
		if ($page == null){
			return abort(404);
		}
		
		$compactData['page_title'] = $page->name;
		return tview('site.home.pages', compact('compactData','page'));
	}
	
	public function post_graduate_diploma(){
		return tview('site.home.pages');
	}
	
	public function professional_diploma(){
		return tview('site.home.professional_diploma');
	}
	
	public function mba(){
		return tview('site.home.mba');
	}
	
	public function masters(){
		return tview('site.home.masters');
	}
	
	public function business_consultancy(){
		return tview('site.home.business_consultancy');
	}
	
	public function talent_assessment(){
		return tview('site.home.talent_assessment');
	}
	
	public function hr_consultancy(){
		return tview('site.home.hr_consultancy');
	}
	
	public function talent_development(){
		return tview('site.home.talent_development');
	}
	
	public function verify_certificate()
	{
		$output = array(
			'is_exist' =>0,
			'status' =>0
		);
		return tview('site.home.verify_certificate',$output);
	}
	
	public function verify_learnerid()
	{
		$output = array(
			'is_exist' =>0,
			'status' =>0
		);
		return tview('site.home.verify_learnerid',$output);
	}
	
	
	public function check_learnerid(Request $request){
		
		$status = $is_exist = 0;
		$student = $country = $email = $joining_date = '';
		$filter = $regids = request()->get('filter');
		if ($regids){
			//$users = DB::table('users')->select('name','billing_country_id')->where('id','=',$student_id)->first();
			
			$regids = explode('-',$regids);//WA-22-03-2210
			$student_id = end($regids);
			if ($student_id == '') {
				$student_id = 0;
			}
			$std = User::find($student_id);
			if ($std) {
				$reg_year = date('y-m', strtotime($std->id));
				
				$reg_number = 'WA-' . $reg_year . '-' . str_pad($std->id, 4, "0", STR_PAD_LEFT);
				$status = $is_exist = 1;
				$joining_date = date('d M, Y');
				$email = $std->email;
				$student = $std->name.' '.$std->last_name;
				
				$attendanceTable = new AttendanceTable();
				$studentTable = new StudentTable();
				$row = $studentTable->getStudentDetails($std->id);
				if($row){
					$courses = array();
					$sessions = Student::find($row->id)->studentCourses()->whereHas('course')->get();
					foreach ($sessions as $session){
						$attended= $attendanceTable->getTotalDistinctForStudentInSession($row->id,$session->course_id);
						$total_classes = Course::find($session->course_id)->lessons()->count();
						$courses[] = array(
							'name' => $session->course->name,
							'classes' => $attended.'/'.$total_classes,
							'enrolled_on' => showDate('d/M/Y',$session->created_at),
							'result_description' => $session->result_description,
							'result_cgpa' => $session->result_cgpa,
							'result_grade' => $session->result_grade,
							'result_certificate_number' => $session->result_certificate_number,
							'result_passing_year' => $session->result_passing_year,
						);
					}
				}else{
					$courses = array();
				}
			}
		}
		
		$output = array(
			'is_exist' =>$is_exist,
			'status' =>1,
			'student' =>$student,
			'country' =>$country,
			'joining_date' =>$joining_date,
			'email' =>$email,
			'filter' =>$filter,
			'courses' =>$courses
		);
		return tview('site.home.verify_learnerid',$output);
		
	}
	
	public function getPartner(){
	    $status=0;
	    $is_exist=0;
	    return tview('site.home.verify_partner',compact('status','is_exist'));
	}
	
	public function check_certificate(Request $request){
		
		$filter = request()->get('filter', null);
		
		
		if (empty($filter)) {
			$filter=null;
		}
		
		$studentSessionTable= new StudentCertificateTable();
		// $filter = $request->get('query');
		
		if(!empty($filter)){
			$paginator = $studentSessionTable->searchStudents($filter);
		}
		else{
			$paginator = false;
		}
		// print_r($paginator);die();
		
		// return view('admin.certificate.track',['paginator'=>$paginator,'pageTitle'=>__lang('Track Certificate')]);
		
		
		$country = '';
		$issue_date = '';
		$status = 0;
		$student = "";
		$certificate = "";
		$output = DB::table('student_certificates')->select('student_id','certificate_id')->where('tracking_number','=',$filter)->get();
		if(sizeof($output) > 0)
		{
			$student_id = @$output[0]->tracking_number;
			$certificate_id = @$output[0]->certificate_id;
			
			$certificates = DB::table('certificates')
				               ->select('name')
				               ->where('id','=',$certificate_id)
				               ->first();
			if ($certificates) {
				$certificate = $certificates->name;
			}
			
			$users = DB::table('users')->select('name','billing_country_id')->where('id','=',$student_id)->first();
			
			foreach($paginator as $student){
				$issue_date = date('d M, Y',strtotime($student->created_at));
				$student = $student->name.' '.$student->last_name;
				
				$country = '';
			}
			
			
			//$certificate = \App\Certificate::find($student->certificate_id);
			
			// echo $users->name;die();
			
			
			$status = 1;
		}else{
			$output = DB::table('external_certificates')->select('*')->where('tracking_number','=',$filter)->get();
			if(sizeof($output) > 0) {
				//dd(@$output[0]->tracking_number);
				$student = @$output[0]->title;
				$certificate = @$output[0]->course;
				$issue_date = @$output[0]->issue_date;
				$country = @$output[0]->country;
				$grade=@$output[0]->grade;
				$website=@$output[0]->website;
				$partner=@$output[0]->type=='partner';
				$ambassador =@$output[0]->type=='ambassador';
				$cgpa=@$output[0]->cgpa;
				$passing_year=@$output[0]->passing_year;
				// $enrol=showDate('d/M/Y',@$output[0]->created_at);
				$status = 1;
			}
		}
		
		$output = array(
			'is_exist' =>$status,
			'status' =>1,
			'filter' => $filter,
			'student' => $student,
			'certificates' => $certificate,
			'issue_date' => $issue_date,
			'country' => $country,
			'paginator' => $paginator,
			'grade' => $grade??"",
			'website' => $website??"",
			'ambassador' => $ambassador??"",
			'partner' => $partner??"",
			'cgpa' => $cgpa??"",
			'passing_year' => $passing_year??"",
// 			'enrol' => $enrol??"",
		);
		
		return tview('site.home.verify_certificate',$output);
		
	}
	
	public function check_partner(Request $request){
	    $filter = request()->get('filter', null);
	    $status = 0;
	    
	    $country = '';
		$issue_date = '';
		$student = "";
		$website = "";
	    if(!empty($filter)){
	        
	    }
	    $output = DB::table('external_certificates')->select('*')->where('tracking_number','=',$filter)->get();
			if(sizeof($output) > 0) {
				//dd(@$output[0]->tracking_number);
				$student = @$output[0]->title;
				$issue_date = @$output[0]->issue_date;
				$country = @$output[0]->country;
				$website=@$output[0]->website;
				// $enrol=showDate('d/M/Y',@$output[0]->created_at);
				$status = 1;
			}
			
		$output = array(
			'is_exist' =>$status,
			'status' =>$status,
			'filter' => $filter,
			'student' => $student,
			'issue_date' => $issue_date,
			'country' => $country,
			'website' => $website??"",
// 			'enrol' => $enrol??"",
		);
	    return tview('site.home.verify_partner',$output);
	}
	
	public function check_ambassador(Request $request){
	   $filter = request()->get('filter', null);
	    $status = 0;
	    $country = '';
		$issue_date = '';
		$student = "";
		$tracking_number="";
	    if(!empty($filter)){
	        
	    }
	    $output = DB::table('external_certificates')->select('*')->where('tracking_number','=',$filter)->where('type','ambassador')->get();
			if(sizeof($output) > 0) {
				$tracking_number=@$output[0]->tracking_number;
				$student = @$output[0]->title;
				$issue_date = @$output[0]->issue_date;
				$country = @$output[0]->country;
				$status = 1;
			}
			
		$output = array(
			'is_exist' =>$status,
			'status' =>$status,
			'filter' => $filter,
			'student' => $student,
			'issue_date' => $issue_date,
			'country' => $country,
		    'tracking_number' => $tracking_number,
		);
	    return tview('site.home.verify_ambassador',$output); 
	}
	
	
	public function list_of_courses(){
		return tview('site.home.list_of_courses');
	}
	
	public function interested_in_future(){
		$captchaUrl = captcha_src();
		$course_name = $agentID = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Null';
		return tview('site.home.interested_in_future',compact('captchaUrl','course_name'));
	}
	
	public function contact(){
		$captchaUrl = captcha_src();
		return tview('site.home.contact',compact('captchaUrl'));
	}
	
	public function sendMail(Request $request){
		$this->validate($request,[
			'name'=>'required',
			'email'=>'required',
			'message'=>'required',
			'captcha' => 'required|captcha'
		]);
		
		if(!empty(setting('general_admin_email')))
		{
			$this->sendEmail(setting('general_admin_email'),__('default.contact-form-message'),$request->message,['address'=>$request->email,'name'=>$request->name]);
		}
		
		return back()->with('flash_message',__('default.message-sent'));
		
	}
	
	public function privacy(){
		$title= __lang('privacy-policy');
		$content = setting('info_privacy');
		return tview('site.home.info',compact('title','content'));
	}
	
	public function terms(){
		$title= __lang('terms-conditions');
		$content = setting('info_terms');
	
		return tview('site.home.info',compact('title','content'));
		
	}
	
	public function instructors(){
		$admins = Admin::where('public',1)->whereHas('user',function($query){
			$query->orderBy('name');
		})->get();
		
		
		return tview('site.home.instructors',compact('admins'));
	}
	
	public function instructor(Admin $admin){
		if (empty($admin->public)){
			abort(401);
		}
		return tview('site.home.instructor',compact('admin'));
	}
	
	public function cron(Request $request,$method)
	{
		set_time_limit(3600);
		//protect ip
		$ip = setting('general_site_ip');
		if(!empty($ip) && trim($ip) != $_SERVER['REMOTE_ADDR']){
			exit('Unauthorized access');
		}
		
		//process only at 12noon in the first minute
		$hour= date('G');
		$minute = date('i');
		$cHour = setting('general_reminder_hour');
		if($hour != $cHour ){
			exit('Invalid time for cron');
		}
		
		$jobs= new CronJobs();
		call_user_func([$jobs,$method]);
	}
	
	public function searchAjax(Request $request){
	    $serachingdata=$request->input('searchmenu');
            $courses=Course::where('name','LIKE','%'.$serachingdata.'%')
            ->where('enabled',1)->take(5)->get();
            return tview('site.home.ajaxPage',compact('courses'));
	}
	
// 	kabir works 
public function infoImportant($id){
    $getCourse=Course::find($id);
    return tview('site.home.append.info',compact('getCourse'));
}
}
