<?php

namespace App\Console\Commands;

use App\Assignment;
use App\StudentCourse;
use App\AssignmentSubmission;
use App\Mail\PendingAssignmentsReminderMail;
use App\Student;
use App\User;
use App\V2\Model\AssignmentSubmissionTable;
use App\V2\Model\AssignmentTable;
use App\V2\Model\SessionTable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReminderToPendingAssignments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reminder-for-pending-assignments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to students who have not submitted there assignments yet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $now = Carbon::now()->toDateString();

        //Get All Assignments s
        $assignments = Assignment::where('due_date','>',$now)
                                    ->orWhere('allow_late',1)
                                    ->where('opening_date','<',$now)
                                    ->where('schedule_type','s')
                                    ->get();

        foreach ($assignments as $assignment) {
            $studentsCourses = StudentCourse::with([
                'student:id,user_id',
                'student.user:id,name,email',
                'course:id,name'])
            ->where('course_id',$assignment->course_id)->get();

            foreach ($studentsCourses as $studentCourse) {

                if(AssignmentSubmission::where('student_id',$studentCourse->student->id)->where('assignment_id',$assignment->id)->where('submitted',1)->first())
                {
                    continue;//Skip if assignment is already submitted
                }

                $date = Carbon::parse($assignment->due_date);
                $now = Carbon::now();
                $days = $date->diffInDays($now);
                if($days == 7 || $days == 3 || $days == 1)
                {
                // Use Laravel's built-in Mail facade to send the email
                Mail::to($studentCourse->student->user->email)->send(new PendingAssignmentsReminderMail($studentCourse->student->user->name,$studentCourse->course->name,$assignment->due_date,$days));
                }

            }
            
        }

        $this->info('Reminder For Pending Students Sent Successfully via Email.');
    }
}
