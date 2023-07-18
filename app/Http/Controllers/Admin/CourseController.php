<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\V2\Model\LectureTable;
use App\V2\Model\LessonTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\StudentSessionTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Student\CourseController as StudentCourseController;

class CourseController extends StudentCourseController
{
    private $module = 'admin';
    public function __construct()
    {
        define('MODULE','admin');
    }
    public function getId(){
        return false;
    }

    public function getStudent(){
        $obj = new \stdClass();
        $obj->id = 1;
        return $obj;
    }

    public function validateEnrollment($sessionId){

        return true;
    }

    public function verifyClass($id,$session){
        return true;
    }

    public function loglecture(Request $request){
        $lecture = $request->post('lecture_id');
        $session = $request->post('course_id');
        $lectureTable = new LectureTable();
        $lectureRow = $lectureTable->getRecord($lecture);
        $lessonTable = new LessonTable();
        $sessionLessonTable = new SessionLessonTable();
        $next = $lectureTable->getNextLecture($lecture);
        if($next){
            return redirect()->route(MODULE.'.course.lecture',['lecture'=>$next->id,'course'=>$session]);
        }

        $nextClass = $sessionLessonTable->getNextLessonInSession($session,$lectureRow->lesson_id,'c');
        if($nextClass){
            //forward to the next class
            return redirect()->route(MODULE.'.course.class',['course'=>$session,'lesson'=>$nextClass->lesson_id]);
        }
        else{
            //classes are over

            flashMessage(__lang('course-complete-msg'));
            return redirect()->route('admin.dashboard');
        }
    }
}
