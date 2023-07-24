<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemindForExams extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $userName;
    protected $examName;
    protected $courseName;
    protected $openingDate;
    protected $time;
    protected $duration;
    protected $totalMarks;
    protected $noOfQuestions;
    protected $typeOfQuestions;
    public function __construct($userName,$examName,$courseName,$openingDate,$time,$duration,$totalMarks,$noOfQuestions,$typeOfQuestions)
    {
        $this->userName=$userName;
        $this->examName=$examName;
        $this->courseName=$courseName;
        $this->openingDate=$openingDate;
        $this->examName=$examName;
        $this->totalMarks=$totalMarks;
        $this->time=$time;
        $this->duration=$duration;
        $this->noOfQuestions=$noOfQuestions;
        $this->typeOfQuestions=$typeOfQuestions;
    }

    /**
     * Build the message.
     *w
     * @return $this
     */
    public function build()
    {

        
        $data = [
            'userName'=>$this->userName,
            'examName'=>$this->examName,
            'courseName'=>$this->courseName,
            'openingDate'=>$this->openingDate,
            'totalMarks'=>$this->totalMarks,
            'time'=>$this->time,
            'duration'=>$this->duration,
            'noOfQuestions'=>$this->noOfQuestions,
            'typeOfQuestions'=>$this->typeOfQuestions
        ];

        return $this->subject('Assessment Reminder (World Academy)')
        ->view('emails.exams_reminder')
        ->with([
            'data'=> $data
        ])
        ;
    }
}
