<?php

namespace App\Console\Commands;

use App\Mail\PendingAssignmentsReminderMail;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderToPendingAssignments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reminder-for-pending-assignments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to students who have not submitted there assignments yet';

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
        // Get users who haven't Submitted there assignments
        $inactiveUsers = User::where('role_id','!=',1)->where('last_login', '=', now()->subWeek())->get();
        // Send email to each inactive user
        foreach ($inactiveUsers as $user) {
            // Use Laravel's built-in Mail facade to send the email
            Mail::to($user->email)->send(new PendingAssignmentsReminderMail($user));
        }

        $this->info('Inactive user emails sent successfully.');
    }
}
