<?php

namespace App\Console\Commands;

use App\Models\DailyTip;
use App\Models\UserDevice;
use Illuminate\Console\Command;
use App\Traits\NotificationTrait;

class SendDailyTipCommand extends Command
{
    use NotificationTrait;
    protected $signature = 'Tip:Send';

    protected $description = 'This command send daily tips to users';

    public function handle()
    {
        $tokens = UserDevice::all('mobile_token');
        $tip = DailyTip::query()->inRandomOrder()->limit(1)->get();
        $this->sendNotification($tokens, 'Daily Tip 💡', $tip->first()->tip, 'Home Screen');
        // $this->sendNotification($tokens, 'Daily Tip', $tip->first()->tip, 'Home Screen');
    }
}
