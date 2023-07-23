<?php

namespace App\Console\Commands;

use App\User;
use App\Mail\InactiveUserEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInactiveUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:inactive-users';

    
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to inactive users who have not logged in for a week.';

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
        // Get users who haven't logged in for a week (other than admin)
        $inactiveUsers = User::where('role_id','!=',1)->where('last_login', '<', now()->subWeek())->get();
        // Send email to each inactive user
        foreach ($inactiveUsers as $user) {
            /// Use Laravel's built-in Mail facade to send the email
            Mail::to($user->email)->send(new InactiveUserEmail($user));
        }

        $this->info('Inactive user emails sent successfully.');
    }
}
