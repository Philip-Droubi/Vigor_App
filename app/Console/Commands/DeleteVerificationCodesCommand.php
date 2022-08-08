<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteVerificationCodesCommand extends Command
{
    protected $signature = 'Delete:VerificationCodes';

    protected $description = 'Command that delete old verification codes that passed 3 dayes since last update';

    public function handle()
    {
        DB::table('new_emails')->where('updated_at', '<', Carbon::now()->subDays(2))->delete();
        DB::table('password_resets')->where('created_at', '<', Carbon::now()->subDays(2))->delete();
        DB::table('recoveries')->where('updated_at', '<', Carbon::now()->subDays(2))->delete();
        DB::table('user_verify')->where('updated_at', '<', Carbon::now()->subDays(2))->delete();
    }
}
