<?php

namespace App\Console\Commands;

use App\User;
use App\Mail\ActiveUserEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendActiveUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:active-users';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Send email to active users who have logged in within the last week.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get users who have logged in within the last week (other than admin)
        $activeUsers = User::where('role_id','!=',1)->where('last_login', '>=', now()->subWeek())->get();
        // Send email to each active user
        foreach ($activeUsers as $user) {
            // Use Laravel's built-in Mail facade to send the email
            Mail::to($user->email)->send(new ActiveUserEmail($user));
        }

        $this->info('Active user emails sent successfully.');
    }
}
