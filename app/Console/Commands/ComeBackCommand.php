<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserDevice;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\NotificationTrait;

class ComeBackCommand extends Command
{
    use NotificationTrait;
    protected $signature = 'Come:Send';

    protected $description = 'Send ComeBack notification to + 15 dayes last seen users';

    public function handle()
    {
        $users = User::query()->where('last_seen', '<', Carbon::now()->subDays(15))->get(['id']);
        $m_tokens = UserDevice::query()->whereIn('user_id', $users)->get(['mobile_token']);
        $body = 'Looks like it\'s been a long time since you last used the app, come back and see what you missed.';
        $this->sendNotification($m_tokens, 'We are missing you 💞', $body);
    }
}
