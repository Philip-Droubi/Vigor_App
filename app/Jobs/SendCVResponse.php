<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailTrait;
use App\Traits\NotificationTrait;

class SendCVResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use EmailTrait, NotificationTrait;

    public $user, $role, $Acception;
    public function __construct($user, $role, $Acception)
    {
        $this->user = $user;
        $this->role = $role;
        $this->Acception = $Acception;
    }

    public function handle()
    {
        $m_token = $this->user->devices()->get(['mobile_token']);
        if ($this->Acception == true) {
            $this->sendCVAccept($this->user->f_name, $this->role, $this->user->email);
            $this->sendNotification($m_token, 'Your CV was accepted!', 'You were accepted to be a ' . $this->role . ' with us. Please check your email for more.');
        } elseif ($this->Acception == false) {
            $this->sendCVRefuse($this->user->f_name, $this->user->email);
            $this->sendNotification($m_token, 'Your CV was Refused!', 'We are sorry to inform you that your CV has been rejected. Please check your email for more.');
        }
    }
}
