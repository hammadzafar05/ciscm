<?php

namespace App\Console;


use App\Console\Commands\cronSendScheduleEmail;
use App\Console\Commands\cronSendScheduleSMS;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
	    cronSendScheduleSMS::class,
	    cronSendScheduleEmail::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $links = ['classes','homework','courses','started','tests','forum'];
            foreach($links as $url){
                try{
                    $newLink = setting('config_baseurl').'/cron/'.$url;
                    $cont = file_get_contents($newLink);
                }
                catch(\Exception $ex){

                }

                //$this->getPageAsync($newLink);
            }
        })->hourly();
	
	    $schedule->command('sendSMS:everyFiveMinutes')->everyFiveMinutes();
	    $schedule->command('sendEmail:everyFiveMinutes')->everyFiveMinutes();
        $schedule->command('email:inactive-users')->daily();
        $schedule->command('email:active-users')->weeklyOn(1, '8:00');//run on every monday at 8:00 am


		/*
		 * /usr/local/bin/php /home/pharmacarebd/public_html/artisan sendSMS:everyFiveMinutes
		 *
		 * */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
