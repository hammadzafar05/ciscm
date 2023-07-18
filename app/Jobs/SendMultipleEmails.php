<?php

namespace App\Jobs;
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

    protected $userEmails;

    /**
     * Create a new job instance.
     *
     * @param array $userEmails
     */
    public function __construct(array $data,$id)
    {
        $this->userEmails = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->userEmails as $email) {
            Mail::to($email)->send(new MultipleUsersEmail($id));
        }
    }
}
