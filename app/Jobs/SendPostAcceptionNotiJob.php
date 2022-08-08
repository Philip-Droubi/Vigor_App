<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\NotificationTrait;

class SendPostAcceptionNotiJob implements ShouldQueue
{
    use NotificationTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user, $text, $Acception;
    public function __construct($user, $text, $Acception)
    {
        $this->user = $user;
        $this->text = $text;
        $this->Acception = $Acception;
    }

    public function handle()
    {
        $m_token = $this->user->devices()->get(['mobile_token']);
        if ($this->Acception == true) {
            $this->sendNotification($m_token, 'Your Post was accepted!', $this->text . ' ...');
        } elseif ($this->Acception == false) {
            $this->sendNotification($m_token, 'Your Post was Refused!', $this->text . ' ...');
        }
    }
}
