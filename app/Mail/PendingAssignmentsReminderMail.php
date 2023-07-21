<?php

namespace App\Mail;

use App\Student;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingAssignmentsReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $userName;
    protected $courseName;
    protected $dueDate;
    protected $days;
    public function __construct($userName,$courseName,$dueDate,$days)
    {
        $this->userName=$userName;
        $this->courseName=$courseName;
        $this->dueDate=$dueDate;
        $this->days=$days;
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
            'dueDate'=>$this->dueDate,
            'days'=>$this->days,
        ];

        return $this->subject('Reminder Email (World Academy)')
        ->view('emails.pending_assignments_reminder')
        ->with([
            'data' => $data,
        ]);
    }
}
