<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamResultPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $userName;
    protected $courseName;
    protected $obtainedMarks;
    public function __construct($userName,$courseName,$obtainedMarks)
    {
        $this->userName = $userName;
        $this->courseName = $courseName;
        $this->obtainedMarks = $obtainedMarks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'userName'=>$this->userName,
            'courseName'=>$this->courseName,
            'obtainedMarks'=>$this->obtainedMarks
        ];

        return $this->subject('Exam Grading Published')
        ->view('emails.exam_grading_published')
        ->with([
            'data'=>$data
        ]);
    }
}
