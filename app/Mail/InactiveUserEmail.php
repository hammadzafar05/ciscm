<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InactiveUserEmail extends Mailable
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

    public function build()
    {
        $data = DB::table('student_courses')
        ->leftJoin('courses', 'id', '=', 'student_courses.course_id')
        ->leftJoin('users', 'id', '=', 'student_courses.student_id')
        ->where('student_courses.student_id', '=', $this->user->id)
        ->select('users.id', 'users.name', 'courses.name')
        ->first();
        return $this->subject('Reminder Email (World Academy)')
        ->view('emails.inactive_user')
        ->with([
            'data' => $data,
        ]);
    }
}
