<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DeleteUnVerifiedAccounts extends Command
{
    protected $signature = 'Delete:unVerified';

    protected $description = 'Delete unVerified accounts that pass 3 days';

    public function handle()
    {
        $users = User::where('created_at', '<', Carbon::now()->subDays(3))->where('email_verified_at', NULL)->get(['id']);
        foreach ($users as $user) {
            Storage::deleteDirectory('public/images/users/' . $user->id);
            $user->delete();
        }
    }
}
