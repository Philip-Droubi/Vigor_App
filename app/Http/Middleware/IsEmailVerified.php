<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;
use App\Traits\EmailTrait;
use App\Http\Controllers\VerifyUserController;
use Illuminate\Support\Facades\Validator;

class IsEmailVerified
{
    use GeneralTrait, EmailTrait;
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                $user = User::where('email', $request->email)->first();
            }
            if ($user)
                if (is_null($user->email_verified_at)) {
                    $message = 'You need to confirm your email. We have sent you a Verification Code, please check your email.';
                    if (VerifyUserController::sendCode($user, $request))
                        return $this->fail(__("messages." . $message), 450);
                }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
        return $next($request);
    }
}
