<?php

namespace App\Http\Controllers;

use App\Models\UserVerify;
use App\Models\NewEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use App\Traits\EmailTrait;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class VerifyUserController extends Controller
{
    use GeneralTrait, EmailTrait;
    public static function sendCode($user, $request)
    {
        $token = Str::upper(Str::random(5));
        UserVerify::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'token' => $token
        ]);
        self::sendEmailVerifyCode($token, $user->f_name, $user->email);
        return true;
    }

    public function verifyAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->only('code'), [
                'code' => ['required', 'string']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $code = request()->code;
            $user = Auth::user();
            if (is_null($user->email_verified_at)) {
                $verifyUser = UserVerify::where(['user_id' => $user->id, 'token' => $code])->first();
                if (!is_null($verifyUser)) {
                    if ((Carbon::parse($verifyUser->updated_at)->addMinutes(20))->lte(Carbon::now())) {
                        if (VerifyUserController::sendCode($user, $request))
                            return $this->success(__("messages.This code has Expired"));
                    }
                    $verifyUser->user->email_verified_at = Carbon::now();
                    $verifyUser->user->save();
                    $verifyUser->delete();
                    return $this->success(__("messages.Your e-mail has been verified."), ['code' => ""], 201);
                }
                return $this->fail(__("messages.Wrong code."), 400);
            }
            return $this->success(__("messages.Your e-mail has already been verified"), ['code' => ""], 201);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function reGetCode(Request $request)
    {
        try {
            $user = Auth::user();
            if (is_null($user->email_verified_at)) {
                if (VerifyUserController::sendCode($user, $request))
                    return $this->success(__("messages.we resent you new code"));
                return $this->fail(__("messages.somthing went wrong"), 500);
            }
            return $this->success(__("messages.Your e-mail has already been verified"));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }

    public static function newEmailSendCode($user, $email_token, $newEmail)
    {
        self::sendEmailVerifyCode($email_token, $user->f_name, $newEmail);
        return true;
    }

    public static function newEmailReGetCode(Request $request)
    {
        try {
            $user = Auth::user();
            if ($newEmail = NewEmail::where('user_id', $user->id)->first()) {
                $token = Str::upper(Str::random(5));
                $newEmail->email_token = $token;
                $newEmail->save();
                if (VerifyUserController::newEmailSendCode($user, $token, $newEmail->new_email))
                    return response()->json([
                        'success' => true,
                        'status' => 200,
                        'message' => 'ok',
                        'data' => [],
                    ], 200);
            }
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => __("messages.Access denied"),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
                // 'message' => __("messages.somthing went wrong"),
            ], 500);
        }
    }
}
