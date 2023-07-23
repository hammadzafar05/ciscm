<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FileUploadedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;
    protected $lectureName;
    public function __construct($user,$lectureName)
    {
        $this->user = $user;
        $this->lectureName = $lectureName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'userName'=>$this->user,
            'lectureName'=>$this->lectureName,
        ];

        return $this->subject('Material Uploaded (World Academy)')
        ->view('emails.material_uploaded')
        ->with([
            'data' => $data,
        ]);
    }
}
