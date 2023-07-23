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

class FileUploadSendMultipleEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @param array $user
     */
    public function __construct(array $data)
    {
        $this->user = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->user as $data) {
            Mail::to($data['student']['user']['email'])->send(new FileUploadedEmail($data['student']['user']['name'],$data['lecture']['title']));
        }
    }
}