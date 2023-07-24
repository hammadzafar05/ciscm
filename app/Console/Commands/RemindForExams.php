<?php

namespace App\Console\Commands;

use App\Assignment;
use App\AssignmentSubmission;
use App\CourseTest;
use App\Mail\PendingAssignmentsReminderMail;
use App\Mail\RemindForExams as MailRemindForExams;
use App\StudentCourse;
use App\Test;
use App\V2\Model\SessionTestTable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RemindForExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reminder-for-exams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Students for Assessment via mail';

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


        $tests = CourseTest::where('opening_date', '>', $now)
            ->get();

        foreach ($tests as $test) {
            $studentsCourses = StudentCourse::with([
                'student:id,user_id',
                'student.user:id,name,email',
                'course:id,name',
                'course.tests' => function ($query) use ($test) {
                    $query->where('tests.id', $test->test_id);
                }
            ])
                ->where('course_id', $test->course_id)->get();

            $openingDate = Carbon::parse($test->opening_date);
            $now = Carbon::now();
            $days = $openingDate->diffInDays($now);
            $openingTime = Carbon::parse($test->opening_date);

            foreach ($studentsCourses as $studentCourse) {

                // if ($days == 7 || $days == 3 || $days == 0.5) {
                    // Use Laravel's built-in Mail facade to send the email
                    Mail::to($studentCourse->student->user->email)
                        ->send(
                            new MailRemindForExams(
                                $studentCourse->student->user->name,
                                $studentCourse->course->tests[0]->name,
                                $studentCourse->course->name,
                                $openingDate->format('d M Y'),
                                $openingTime->format('h:i A'),
                                $studentCourse->course->tests[0]->minutes,
                                $studentCourse->course->tests[0]->passmark,
                                $studentCourse->course->tests[0]->number_of_questions,
                                $studentCourse->course->tests[0]->exam_type == 0 ? 'MCQs' : 'Written'
                            )
                        );
                }

            // }

        }
        $this->info('Reminder For Scheduled Exam Sent Successfully via Email.');
    }
}