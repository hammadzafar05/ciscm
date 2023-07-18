<?php


namespace App\Lib;


use App\Course;
use App\EmailTemplate;
use App\ForumTopic;
use App\SmsTemplate;
use App\Test;
use App\V2\Model\AssignmentTable;
use App\V2\Model\SessionLessonAccountTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\SessionTestTable;
use App\V2\Model\StudentSessionTable;
use Illuminate\Support\Carbon;

class CronJobs
{
    use HelperTrait;
    public function forum(){

        $start = time()- 3600;
        $start = Carbon::createFromTimestamp($start)->toDateTimeString();
        $topics = ForumTopic::where('created_at','>',$start)->get();
        $sessionTopics = [];
        foreach($topics as $topic){
            $sessionTopics[$topic->course_id][] = $topic;
        }

        //now loop through session topics
        foreach($sessionTopics as $sessionId=>$topics){
            $data = [
                'topics'=>$topics,
                'controller'=>$this,
            ];
            $data['module']= 'student';
            $message = view('mails.forum_topic',$data)->toHtml();

            //get subscribed users
            $course = Course::find($sessionId);

            $subject = __lang('new-forum-topics-for').' '.$course->name;
            $this->notifySessionStudents($sessionId,$subject,$message);

            $data['module'] = 'admin';
            $message = view('mails.forum_topic',$data)->toHtml();
            $sent= $this->notifyAdmins(__lang('new-forum-topics-for').' '.Course::find($sessionId)->name,$message);

            //send to creator

            try{
                if(!empty($course->admin_id) && !isset($sent[$course->admin->user->email])){
                    $this->sendEmail($course->admin->user->email,$subject,$message);
                    $sent[$course->admin->user->email] = true;
                }
            }
            catch(\Exception $ex){

            }



            //get session instructors
            foreach($course->admins as $admin){
                try{
                    if(!isset($sent[$admin->user->email])){
                        $this->sendEmail($admin->user->email,$subject,$message);
                    }
                }
                catch(\Exception $ex){

                }


            }

        }

        exit('done');
    }

    public function classes(){
        $totalSent= 0;
        $sessionLessonTable = new SessionLessonTable();
        $studentsTable = new StudentSessionTable();
        $lessonAccountTable = new SessionLessonAccountTable();
        $rowset = $sessionLessonTable->getUpcomingLessons(setting('general_reminder_days'));

        foreach($rowset as $row){
            echo $row->name.'<br/>';
            //for this lesson, get all students enrolled
            //      $subject = 'Upcoming Class: '.$row->lesson_name;
            $class = $row->name;
            $classDate = showDate('d/M/Y',$row->lesson_date);
            $venue = $row->lesson_venue;
            if(empty($venue)){
                $venue =$row->venue;
            }
            $lstart = '';
            if(!empty($row->lesson_start)){
                $lstart = '<strong>'.__('Starts').':</strong> '.$row->lesson_start.'.<br/>';
            }
            $lend = '';
            if(!empty($row->lesson_end)){
                $lend = '<strong>'.__('Ends').':</strong> '.$row->lesson_end.'.<br/>';
            }

            if($row->lesson_type=='s')
            {

                $map = [
                    'CLASS_NAME'=>$class,
                    'CLASS_DATE'=>$classDate,
                    'SESSION_NAME'=>$row->course_name,
                    'CLASS_VENUE'=>$row->venue ,
                    'CLASS_START_TIME'=>$row->lesson_start,
                    'CLASS_END_TIME'=>$row->lesson_end,
                ];

                $messageArray = $this->getEmailMessage(1,$map);
                $sms = $this->getSmsMessage(1,$map);
                $subject = $messageArray['subject'];
                $message = $messageArray['message'];
                $textMessage = $sms;

//                $message = <<<EOD
//Please be reminded that the class <strong>'$class'</strong> is scheduled to hold as follows: <br/>
//<strong>Date:</strong> $classDate <br/>
//<strong>Session:</strong> $row->session_name <br/>
//<strong>Venue:</strong> $row->venue <br/>
//$lstart $lend
//EOD;
                //              $textMessage = "Reminder! The $row->session_name class '$class' holds on $classDate. Venue: $row->venue . ".strip_tags($lstart).strip_tags($lend);
            }
            else{

                $map = [
                    'CLASS_NAME'=>$class,
                    'CLASS_DATE'=>$classDate,
                    'COURSE_NAME'=>$row->course_name,
                ];

                $messageArray = $this->getEmailMessage(2,$map);
                $sms = $this->getSmsMessage(2,$map);
                $subject = $messageArray['subject'];
                $message = $messageArray['message'];
                $textMessage = $sms;



//                $message = <<<EOD
//Please be reminded that the class <strong>'$class'</strong> is scheduled as follows: <br/>
//<strong>Course:</strong> $row->session_name <br/>
//<strong>Starts:</strong> $classDate <br/>
//EOD;
//            $textMessage = "Reminder! The $row->session_name class '$class' starts on  $classDate";





            }



            //get all students in session and notify
            $students = $studentsTable->getSessionRecords(false,$row->course_id);
            $numbers= [];
            foreach($students as $student){
                try{
                    $studentMessage = setPlaceHolders($message,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $studentSubject = setPlaceHolders($subject,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $this->sendEmail($student->email,$studentSubject,$studentMessage);
                    if(!empty($student->mobile_number)){
                        $numbers[] = $student->mobile_number;
                    }

                    $totalSent++;
                }
                catch(\Exception $ex){}

            }

            sendSms(null,$numbers,$textMessage);


            //get all admins for lesson and notify
            $instructors = $lessonAccountTable->getInstructors($row->lesson_id,$row->course_id);
            foreach($instructors as $instructor){
                try{
                    $insMessage = setPlaceHolders($message,[
                        'RECIPIENT_FIRST_NAME'=>$instructor->name,
                        'RECIPIENT_LAST_NAME'=>$instructor->last_name
                    ]);
                    $insSubject = setPlaceHolders($subject,[
                        'RECIPIENT_FIRST_NAME'=>$instructor->name,
                        'RECIPIENT_LAST_NAME'=>$instructor->last_name
                    ]);
                    $this->sendEmail($instructor->email,$insSubject,$insMessage);
                    $totalSent++;
                }
                catch(\Exception $ex){}
            }


        }

        exit("Sent to: ".$totalSent);
    }

    public function tests(){
        $totalSent= 0;
        $sessionTestTable = new SessionTestTable();
        $studentsTable = new StudentSessionTable();
        $rowset = $sessionTestTable->getUpcomingTests(setting('general_reminder_days'));

        foreach($rowset as $row){

            $test = Test::find($row->test_id);
            $map =[
                'TEST_NAME' => $row->name,
                'TEST_DESCRIPTION'=>$test->description,
                'SESSION_NAME'=>$row->course_name,
                'OPENING_DATE'=>date('d/M/Y',$row->opening_date),
                'CLOSING_DATE'=> date('d/M/Y',$row->closing_date),
                'PASSMARK'=>$test->passmark.' %',
                'MINUTES_ALLOWED'=>$test->minutes,
            ];

            $messageArray = $this->getEmailMessage(3,$map);
            $textMessage = $this->getSmsMessage(3,$map);

            $subject= $messageArray['subject'];
            $message = $messageArray['message'];

//            echo $row->name.'<br/>';
//            //for this lesson, get all students enrolled
//            $subject = 'Upcoming Test: '.$row->name;
//            $test = $row->name;
//            $testDate = date('d/M/Y',$row->opening_date);
//
//            $lstart = '';
//            if(!empty($row->opening_date)){
//                $lstart = '<strong>Starts:</strong> '.$row->opening_date.'.<br/> ';
//            }
//            $lend = '';
//            if(!empty($row->closing_date)){
//                $lend = '<strong>Ends:</strong> '.$row->closing_date.'.<br/> Please ensure you take the test before the closing date.';
//            }
//
//
//                $message = <<<EOD
//Please be reminded that the test <strong>'$test'</strong> is scheduled as follows: <br/>
//<strong>Session/Course:</strong> $row->session_name <br/>
//$lstart $lend
//
//EOD;
//
//        $textMessage = "Reminder: The '$row->session_name' test '$test' is scheduled as follows:  ".strip_tags($lstart).strip_tags($lend);



            //get all students in session and notify
            $numbers = [];
            $students = $studentsTable->getSessionRecords(false,$row->course_id);
            foreach($students as $student){
                try{
                    $studentMessage = setPlaceHolders($message,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $studentSubject = setPlaceHolders($subject,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $this->sendEmail($student->email,$studentSubject,$studentMessage);

                    if(!empty($student->mobile_number)){
                        $numbers[] = $student->mobile_number;
                    }

                    $totalSent++;
                }
                catch(\Exception $ex){}

            }

            sendSms(null,$numbers,$textMessage);

        }

        exit("Sent to: ".$totalSent);
    }



    public function started(){

        $totalSent= 0;
        $sessionLessonTable = new SessionLessonTable();
        $studentsTable = new StudentSessionTable();
        $rowset = $sessionLessonTable->getStartedLessons();

        foreach($rowset as $row){


//
//            echo $row->lesson_name.'<br/>';
//            //for this lesson, get all students enrolled
//            $subject = 'Class '.$row->lesson_name.' is open';
//            $class = $row->lesson_name;
//            $classDate = date('d/M/Y',$row->lesson_date);

            $url = route('student.course.class',['lesson'=>$row->lesson_id,'course'=>$row->course_id]);
//                $message = <<<EOD
//Please be reminded that the class <strong>'$class'</strong> has started. <br/>
//Click this link to take this class now: <a href="$url">$url</a><br/>
//EOD;



            $map = [
                'CLASS_NAME'=>$row->name,
                'CLASS_URL'=> $url,
                'COURSE_NAME'=> $row->course_name,
            ];


            $messageArray = $this->getEmailMessage(4,$map);
            $message = $messageArray['message'];
            $subject = $messageArray['subject'];
            $textMessage = $this->getSmsMessage(4,$map);

            //get all students in session and notify
            $students = $studentsTable->getSessionRecords(false,$row->course_id);
            $numbers = [];
            foreach($students as $student){
                try{

                    $studentMessage = setPlaceHolders($message,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $studentSubject = setPlaceHolders($subject,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $this->sendEmail($student->email,$studentSubject,$studentMessage);
                    if(!empty($student->mobile_number)){
                        $numbers[] = $student->mobile_number;
                    }
                    $totalSent++;
                }
                catch(\Exception $ex){}

            }

            sendSms(null,$numbers,$textMessage);


        }

        exit("Sent to: ".$totalSent);
    }

    public function homework(){
        $totalSent= 0;
        $assignmentTable = new AssignmentTable();
        $studentsTable = new StudentSessionTable();
        $rowset = $assignmentTable->getUpcomingAssignments(setting('general_reminder_days'));

        foreach($rowset as $row){
            echo $row->title.'<br/>';
            $days = (Carbon::parse($row->due_date)->timestamp - time())/86400;
            $days = floor($days);
            if($days>1){
                $dayText = __lang('days');
            }
            else{
                $dayText = __lang('day');
            }
            //for this lesson, get all students enrolled
            //$subject = 'Homework due in  '.$days.' '.$dayText;
            $title = $row->title;
            $dueDate = showDate('d/M/Y',$row->due_date);
            $openingDate = showDate('d/M/Y',$row->opening_date);
            $link = route('student.assignment.submit',['id'=>$row->id]);

//                $message = <<<EOD
//Please be reminded that the homework <strong>'$title'</strong> is due on $dueDate. <br/>
//Please click this link to submit your homework now: <a href="$link">$link</a>
//EOD;

            $map = [
                'NUMBER_OF_DAYS'=>$days,
                'DAY_TEXT'=>$dayText,
                'HOMEWORK_NAME'=>$title,
                'HOMEWORK_URL'=>$link,
                'HOMEWORK_INSTRUCTION'=>$row->instruction,
                'PASSMARK'=>$row->passmark,
                'DUE_DATE'=>$dueDate,
                'OPENING_DATE'=>$openingDate
            ];

            $messageArray = $this->getEmailMessage(5,$map);
            $textMessage = $this->getSmsMessage(5,$map);
            $message = $messageArray['message'];
            $subject = $messageArray['subject'];


            //get all students in session and notify
            $students = $studentsTable->getSessionRecords(false,$row->course_id);
            $numbers = [];
            foreach($students as $student){
                try{

                    $studentMessage = setPlaceHolders($message,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $studentSubject = setPlaceHolders($subject,[
                        'RECIPIENT_FIRST_NAME'=>$student->name,
                        'RECIPIENT_LAST_NAME'=>$student->last_name
                    ]);
                    $this->sendEmail($student->email,$studentSubject,$studentMessage);
                    if(!empty($student->mobile_number)){
                        $numbers[] = $student->mobile_number;
                    }
                    $totalSent++;
                }
                catch(\Exception $ex){}

            }

            sendSms(null,$numbers,$textMessage);

        }

        exit("Sent to: ".$totalSent);


    }

    public function courses(){
        $totalSent= 0;
        $sessionTable = new SessionTable();
        $studentsTable = new StudentSessionTable();
        $rowset = $sessionTable->getClosingCourses(setting('general_reminder_days'));

        foreach($rowset as $row){
            echo $row->name.'<br/>';
            $days = (Carbon::parse($row->end_date)->timestamp - time())/86400;
            $days = floor($days);
            if($days>1){
                $dayText = __lang('days');
            }
            else{
                $dayText = __lang('day');
            }
            //for this lesson, get all students enrolled
//            $subject = 'Course ends in  '.$days.' '.$dayText;
            $title = $row->name;
            $dueDate = showDate('d/M/Y',$row->end_date);
            $link = route('student.catalog.course',['id'=>$row->id]);
//            $message = <<<EOD
//Please be reminded that the course <strong>'$title'</strong> closes on $dueDate. <br/>
//Please click this link to complete the course now: <a href="$link">$link</a>
//EOD;



            $map = [
                'COURSE_NAME'=>$title,
                'COURSE_URL'=>$link,
                'CLOSING_DATE'=>$dueDate,
                'NUMBER_OF_DAYS'=>$days,
                'DAY_TEXT'=>$dayText,
            ];

            $messageArray = $this->getEmailMessage(6,$map);
            $textMessage = $this->getSmsMessage(6,$map);
            $message = $messageArray['message'];
            $subject = $messageArray['subject'];

            //get all students in session and notify
            $students = $studentsTable->getSessionRecords(false,$row->id);
            $numbers = [];
            foreach($students as $student){
                try{
                    if(empty($student->completed)){

                        $studentMessage = setPlaceHolders($message,[
                            'RECIPIENT_FIRST_NAME'=>$student->name,
                            'RECIPIENT_LAST_NAME'=>$student->last_name
                        ]);
                        $studentSubject = setPlaceHolders($subject,[
                            'RECIPIENT_FIRST_NAME'=>$student->name,
                            'RECIPIENT_LAST_NAME'=>$student->last_name
                        ]);
                        $this->sendEmail($student->email,$studentSubject,$studentMessage);
                        if(!empty($student->mobile_number)){
                            $numbers[] = $student->mobile_number;
                        }
                        $totalSent++;
                    }

                }
                catch(\Exception $ex){}

            }

            sendSms(null,$numbers,$textMessage);

        }

        exit("Sent to: ".$totalSent);


    }

    private function getPageAsync($url, $params = array('noparam'=>'true'), $type='GET')
    {
        //echo "Attempting to get $url <br/>";


        foreach ($params as $key => $val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);

        $parts=parse_url($url);

        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);

        $cusParams = $parts['query'];

        // Data goes in the path for a GET request
        if('GET' == $type) $parts['path'] .= '?'.$cusParams;

        $out = "$type ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

    private function getEmailMessage($id,$map){
        $template = EmailTemplate::find($id);
        $message = $template->message;
        $subject = $template->subject;
        foreach ($map as $key=>$value){
            $key = '['.$key.']';
            $message = str_replace($key,$value,$message);
            $subject = str_replace($key,$value,$subject);
        }

        return [
            'message'=>$message,
            'subject'=>$subject
        ];

    }

    private function getSmsMessage($id,$map){
        $template = SmsTemplate::find($id);
        $message = $template->message;
        foreach ($map as $key=>$value){
            $key = '['.$key.']';
            $message = str_replace($key,$value,$message);
        }

        return strip_tags($message);
    }


}
