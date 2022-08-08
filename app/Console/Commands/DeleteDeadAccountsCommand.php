<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DeleteDeadAccountsCommand extends Command
{
    protected $signature = 'Delete:DeadAccount';

    protected $description = 'Command that delete dead accounts that pass 30 day without reactive it';

    public function handle()
    {
        $users = User::where('deleted_at', '<', Carbon::now()->subDays(30))->get(['id']);
        foreach ($users as $user) {
            Storage::deleteDirectory('public/images/users/' . $user->id);
            $user->delete();
        }
    }
}
