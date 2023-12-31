<?php

namespace App\Jobs;

use App\Lecture;
use App\Mail\FileUploadedEmail;
use Illuminate\Bus\Queueable;
use App\Mail\MultipleUsersEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMultipleEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $lectureId;

    /**
     * Create a new job instance.
     *
     * @param array $user
     */
    public function __construct(array $data, $id)
    {
        $this->user = $data;
        $this->lectureId = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lectureName=Lecture::find($this->lectureId)->title;
        foreach ($this->user as $data) {
            Mail::to($data['email'])->send(new FileUploadedEmail($data['name'], $lectureName));
        }
    }
}