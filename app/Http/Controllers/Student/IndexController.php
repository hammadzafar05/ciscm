<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace App\Http\Controllers\Student;

use App\Admin;
use App\Course;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\Student;
use App\User;
use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AttendanceTable;
use App\V2\Model\LectureTable;
use App\V2\Model\NoticeBoard;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\StudentLectureTable;
use App\V2\Model\StudentSessionLogTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\TestGradeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\DownloadTable;


class IndexController extends Controller {
	use HelperTrait;
	
	public function index(Request $request) {
		
		$output = [];
		//$viewModel = app('Application\Controller\Catalog',['action'=>'sessions']);
		$viewModel = app(CatalogController::class)->sessions($request);
		$output['sessions'] = $viewModel->getData();
		$output['sessions']['paginator']->setItemCountPerPage(5);
		
		
		$viewModel = app(CatalogController::class)->courses($request);
		$output['courses'] = $viewModel->getData();
		$output['courses']['paginator']->setItemCountPerPage(5);
		
		
		$studentId = $this->getId();
		
		$noticeBoardTable = new NoticeBoard();
		$notices = $noticeBoardTable->getPaginatedRecords(false);
		$noticeboards = array();
		foreach ($notices as $notice) {
			$notice_students = $notice->students;
			if ($notice_students != '') {
				$notice_students = $notice->students;
			}else{
				$notice_students = $notice->student_by_courses;
			}
			$notice_students = explode(',', $notice_students);
			$logged_in_user_id = Auth::user()->id;
			if (in_array($logged_in_user_id, $notice_students)) {
				if ($notice->last_date_to_display == '') {
					$noticeboards[] = array('title' => $notice->title, 'message' => $notice->message);
				} else {
					if (strtotime($notice->last_date_to_display) >= strtotime(date('Y-m-d'))) {
						$noticeboards[] = array('title' => $notice->title, 'message' => $notice->message);
					}
				}
			}
		}
		
		$viewModel = app(StudentController::class)->mysessions($request);
		$output['mysessions'] = $viewModel->getData();
		$output['mysessions']['paginator']->setItemCountPerPage(3);
		$studentSessionTable = new StudentSessionTable();
		$sessionLogTable = new StudentSessionLogTable();
		foreach ($output['mysessions']['paginator'] as $page) {
			$resumeLink = null;
			$studentId = $page->student_id;
			$course_id = $page->course_id;
			
			if ($studentSessionTable->enrolled($studentId, $course_id)) {
				$studentCourse = $this->getStudent()->studentCourses()->where('course_id', $course_id)->first();
				$enrolled = TRUE;
				$studentLectureTable = new StudentLectureTable();
				$sessionLessonTable = new SessionLessonTable();
				//check if student has started lecture
				if ($studentLectureTable->hasLecture($studentId, $course_id)) {
					$lecture = $studentLectureTable->getLecture($studentId, $course_id);
					if ($lecture) {
						
						$lectureId = $lecture->lecture_id;
						/*//get next lecture
						$lectureTable = new LectureTable();
						$next = $lectureTable->getNextLecture($lecture->lecture_id);
					
						if($next){
							$lecture = $next;
							$lectureId = $lecture->id;
						}
					
						if($lecture->sort_order == 1){
							//  $resumeLink = $this->url()->fromRoute('view-class', ['classId' => $lecture->lesson_id, 'sessionId' => $id]);
							$resumeLink = route('student.course.class',['lesson'=>$lecture->lesson_id,'course'=>$course_id]);
						}
						else{
							// $resumeLink = $this->url()->fromRoute('view-lecture', ['lectureId' => $lecture->lecture_id, 'sessionId' => $id]);
							$resumeLink = route('student.course.lecture',['lecture'=>$lectureId,'course'=>$course_id]);
						
						}*/
						//get class list
						$classes = $sessionLessonTable->getSessionRecords($course_id, 'c');
						$classes->buffer();
						
						//get first class
						$firstClass = $classes->current();
						if ($firstClass) {
							$classLink = route('student.course.class', ['lesson' => $firstClass->lesson_id, 'course' => $course_id]);
							$classId = $firstClass->lesson_id;
							$lectureTable = new LectureTable();
							$lectures = $lectureTable->getRecordsOrdered($classId);
							if ($lectureTable->getTotalLectures($classId) > 0) {
								$nextRow = $lectures->current();
								$classLink = route('student.course.lecture', ['lecture' => $nextRow->id, 'course' => $course_id]);
							}
						} else {
							$classLink = '#';
						}
						$resumeLink = $classLink;
						
					} else {
						$resumeLink = route('student.course.intro', ['id' => $course_id]);
					}
					
				} else {
					
					// $resumeLink = $this->url()->fromRoute('application/default', ['controller' => 'course', 'action' => 'intro','id'=>$id]);
					$resumeLink = route('student.course.intro', ['id' => $course_id]);
					
				}
				
			}
			
			$page->resumeLink = $resumeLink;
			$last_lesson = $sessionLogTable->getLastRecordedLesson($studentId,$course_id);
			if ($last_lesson) {
				$page->last_lecture_link = route('student.course.lecture', ['course' => $course_id, 'lecture' => $last_lesson]);
			}else{
				$page->last_lecture_link = $resumeLink;
			}
		}
		
		
		$all_sessions = $studentSessionTable->getStudentRecords(FALSE,$studentId,'website');
		$all_sessions->buffer();
		$partner_programs = array();
		foreach ($all_sessions as $page){
			if ($page) {
				$resumeLink = null;
				$studentId = $page->student_id;
				$course_id = $page->course_id;
				
				if ($studentSessionTable->enrolled($studentId, $course_id)) {
					$studentCourse = $this->getStudent()->studentCourses()->where('course_id', $course_id)->first();
					$enrolled = TRUE;
					$studentLectureTable = new StudentLectureTable();
					$sessionLessonTable = new SessionLessonTable();
					//check if student has started lecture
					if ($studentLectureTable->hasLecture($studentId, $course_id)) {
						$lecture = $studentLectureTable->getLecture($studentId, $course_id);
						if ($lecture) {
							$classes = $sessionLessonTable->getSessionRecords($course_id, 'c');
							$classes->buffer();
							$firstClass = $classes->current();
							if ($firstClass) {
								$classLink = route('student.course.class', ['lesson' => $firstClass->lesson_id, 'course' => $course_id]);
								$classId = $firstClass->lesson_id;
								$lectureTable = new LectureTable();
								$lectures = $lectureTable->getRecordsOrdered($classId);
								if ($lectureTable->getTotalLectures($classId) > 0) {
									$nextRow = $lectures->current();
									$classLink = route('student.course.lecture', ['lecture' => $nextRow->id, 'course' => $course_id]);
								}
							} else {
								$classLink = '#';
							}
							$resumeLink = $classLink;
							
						} else {
							$resumeLink = route('student.course.intro', ['id' => $course_id]);
						}
						
					} else {
						$resumeLink = route('student.course.intro', ['id' => $course_id]);
					}
				}
				
				$page->resumeLink = $resumeLink;
				
				
				$admin_id = Admin::where('id', $page->admin_id)->first();
				$logo = $partner_name = '';
				if ($admin_id) {
					$partner = User::find($admin_id->user_id);
					if ($partner->logo) {
						$logo = asset('cdn/temp/' . $partner->logo);
					}
					$partner_name = $partner->name . ' ' . $partner->last_name;
				}
				if ($admin_id->admin_role_id == 5) {
					$page->logo = $logo;
					$page->partner_name = $partner_name;
					$page->added_by = 'Partner';
				} else {
					$page->logo = '';
					$page->partner_name = '';
					$page->added_by = '';
				}
				if ($admin_id->admin_role_id == 5) {
					$partner_programs[] = $page;
				}
			}
		}
		$output['all_sessions'] = $partner_programs;
		
		
		$viewModel = app(StudentController::class)->notes($request);
		$output['notes'] = $viewModel->getData();
		$output['notes']['paginator']->setItemCountPerPage(5);
		
		
		$viewModel = app(DownloadController::class)->index($request);
		$output['downloads'] = $viewModel->getData();
		$output['downloads']['paginator']->setItemCountPerPage(5);
		
		
		$viewModel = app(StudentController::class)->discussion($request);
		$output['discussions'] = $viewModel->getData();
		$output['discussions']['paginator']->setItemCountPerPage(5);
		
		
		$viewModel = app(AssignmentController::class)->index($request);
		$output['homework'] = $viewModel->getData();
		$output['homework']['paginator']->setItemCountPerPage(100);
		
		
		$totalHomework = $output['homework']['total'];
		$submissionTable = new AssignmentSubmissionTable();
		$output['homeworkPresent'] = FALSE;
		if (!empty($totalHomework)) {
			foreach ($output['homework']['paginator'] as $row) {
				if (!$submissionTable->hasSubmission($this->getId(), $row->assignment_id)) {
					$output['homeworkPresent'] = TRUE;
				}
			}
			
		}
		$output['controller'] = $this;
		$output['student'] = Student::find($studentId);
		$output['gradeTable'] = new TestGradeTable();
		
		$viewModel = app(StudentController::class)->certificates($request);
		$output['certificate'] = $viewModel->getData();
		$output['certificate']['paginator']->setItemCountPerPage(7);
		
		//create forum topics
		$studentSessionTable = new StudentSessionTable();
		$forumTopics = $studentSessionTable->getForumTopics(TRUE, $this->getId());
		$forumTopics->setItemCountPerPage(10);
		
		
		$output['noticeboards'] = $noticeboards;
		$output['forumTopics'] = $forumTopics;
		
// 		kabir works
        $output['paginator'] = $studentSessionTable->getDownloads($this->getId());
        $output['paginator']->setCurrentPageNumber((int)request()->get('page', 1));
        $output['paginator']->setItemCountPerPage(5);
		
		$output['pageTitle'] = __('Dashboard');
		
		return view('student.index.index', $output);
	}
	
	public function getStudentProgress($sessionId) {
		
		$attendanceTable = new AttendanceTable();
		
		$session = Course::find($sessionId);
		$totalLessons = $session->lessons()->count();
		
		
		$totalAttended = $attendanceTable->getTotalDistinctForStudentInSession($this->getId(), $sessionId);
		
		if ($totalLessons == 0) {
			$totalLessons = 1;
		}
		//calculate percentage
		$percentage = ($totalAttended / $totalLessons) * 100;
		$percentage = round($percentage);
		return $percentage;
		
	}
	
}
