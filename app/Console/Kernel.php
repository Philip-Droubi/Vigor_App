<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('Delete:DeadAccount')->daily()->runInBackground();
        $schedule->command('Delete:unVerified')->daily()->runInBackground();
        $schedule->command('Delete:VerificationCodes')->everySixHours()->runInBackground();
        $schedule->command('tip:send')->everyMinute()->runInBackground()->appendOutputTo('c:\xampp\htdocs\Home_Workout_Application\info.txt');
        $schedule->command('Come:Send')->everyMinute()->runInBackground()->appendOutputTo('c:\xampp\htdocs\Home_Workout_Application\info.txt');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
