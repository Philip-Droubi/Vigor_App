<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

trait EmailTrait
{
    protected function sendForgetPassword($token, $name, $email)
    {
        Mail::send('emails.' . App::currentLocale() . '.forgetPassword_Email', ['token' => $token, 'name' => $name], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject(__('messages.Reset Password Notification') . config('app.name'));
        });
    }
    protected function sendResetPasswordConfirm($user)
    {
        Mail::send('emails.' . App::currentLocale() . '.resetPasswordConfirm_Email', ['name' => $user->name, 'time' => Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s')], function ($msg) use ($user) {
            $msg->to($user->email);
            $msg->subject(__('messages.Reset Password confirmation') . config('app.name'));
        });
    }
    protected static function sendEmailVerifyCode($token, $name, $email)
    {
        Mail::send('emails.' . App::currentLocale() . '.emailVerification_Email', ['code' => $token, 'name' => $name], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject(__('messages.Email Verification for') . config('app.name'));
        });
    }
    protected static function sendDeleteEmail($name, $email)
    {
        Mail::send('emails.' . App::currentLocale() . '.deleteAccount_Email', ['name' => $name], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject(__('messages.Account Delete Email') . config('app.name'));
        });
    }
    protected static function sendRecoverEmail($name, $code, $email)
    {
        Mail::send('emails.' . App::currentLocale() . '.recoverAccount_Email', ['name' => $name, 'code' => $code], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject(__('messages.Account Recover Email') . config('app.name'));
        });
    }

    protected static function sendCVAccept($name, $role, $email)
    {
        Mail::send('emails.' . 'en' . '.AcceptCV_Email', ['name' => $name, 'role' => $role], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject("CV Accepted " . config('app.name'));
        });
    }

    protected static function sendCVRefuse($name,  $email)
    {
        Mail::send('emails.' . 'en' . '.RefuseCV_Email', ['name' => $name], function ($msg) use ($email) {
            $msg->to($email);
            $msg->subject("CV Refused " . config('app.name'));
        });
    }
}
// use App\Traits\EmailTrait; befor the controller class
// use EmailTrait; inside the controller class
