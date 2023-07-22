<?php

namespace App\Mail;

use App\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ActiveUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $data = DB::table('student_courses')
        // ->leftJoin('courses', 'id', '=', 'student_courses.course_id')
        // ->leftJoin('users', 'id', '=', 'student_courses.student_id')
        // ->where('student_courses.student_id', '=', $this->user->id)
        // ->select('users.id', 'users.name', 'courses.name')
        // ->first();
        $data = Student::with(['user'=>function($query){
            $query->where('id',$this->user->id);
        },'studentCourses.course:id,name'])->first()->toArray();

        $lastLoginDate = Carbon::parse($data['user']['last_login']);
        $lastLoginDate = $lastLoginDate->format('F jS, Y');

        return $this->subject('Appreciation Email (World Academy)')
        ->view('emails.active_user')
        ->with([
            'data' => $data,
            'lastLoginDate'=>$lastLoginDate
        ]);
    }
}