<?php

namespace App\Console\Commands;

use App\Helper\SchedulerHelper;
use App\Model\ScheduleSms;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\ScheduleRunCommand;

class cronSendScheduleSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendSMS:everyFiveMinutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust Stock every five minutes';

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
     * @return mixed
     */
    public function handle()
    {
	    SchedulerHelper::sms();
    }
}
