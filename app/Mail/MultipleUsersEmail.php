<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActiveUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $lecture_id;

    public function __construct($id)
    {
        $this->lecture_id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = DB::table('student_lectures')
            ->leftJoin('lectures', 'id', '=', 'student_lectures.lecture_id')
            ->leftJoin('users', 'id', '=', 'student_lectures.student_id')
            ->where('student_lectures.lecture_id', '=', $this->lecture_id)
            ->select('lectures.name', 'users.name')
            ->first();
        return $this->subject('Appreciation Email (World Academy)')
            ->view('emails.fileUploadEmail')
            ->with([
                'data' => $data,
            ]);
    }
}
