<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\EmailTrait;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use GeneralTrait, EmailTrait;
    public function submitForgetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        if ($validator->fails())
            return $this->fail($validator->errors()->first(), 400);
        if (User::where('email', $request->email)->first()->providers()->first()) {
            return $this->fail(__("messages.You can login with your provider account"));
        }
        if (User::where('email', $request->email)->first()->deleted_at != Null) {
            return $this->fail(__("messages.You need to recover this account first"));
        }
        $token = Str::upper(Str::random(5));
        if (!DB::table('password_resets')->where('email', $request->email)->first()) {
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()->format("Y-m-d H-i-s"),
            ]);
        } else {
            DB::table('password_resets')->where('email', $request->email)->limit(1)->update([
                'token' => $token,
                'is_used' => 0,
                'created_at' => Carbon::now()->format("Y-m-d H-i-s"),
            ]);
        }
        try {
            $this->sendForgetPassword($token, User::query()->where('email', request()->email)->first()->name, $request->email);
            return $this->success(__('messages.You need to confirm your email'), [], 201);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }
    //
    public function verifytoken(Request $request)
    {
        $validator = Validator::make($request->only('code'), [
            'code' => ['required', 'string', 'exists:password_resets,token'],
        ]);
        if ($validator->fails())
            return $this->fail($validator->errors()->first(), 400);

        $token = request()->code;
        $rp = DB::table('password_resets')->where('token', $token)->first(); //rp = reset password record in DB

        if (((Carbon::parse($rp->created_at)->addMinutes(20))->gte(Carbon::now())) && $rp->is_used != 1) {
            DB::table('password_resets')->where('token', $token)->limit(1)->update([
                'is_used' => 1,
                'token' => $token = Str::random(64)
            ]);
            return $this->success("", ['code' => $token], 201);
        } elseif (((Carbon::parse($rp->created_at)->addMinutes(20))->lt(Carbon::now())) && $rp->is_used != 1) {
            DB::table('password_resets')->where('token', $token)->limit(1)->update([
                'token' => $token = Str::upper(Str::random(5)),
                'created_at' => Carbon::now()->format("Y-m-d H-i-s")
            ]);
            try {
                $this->sendForgetPassword($token, User::query()->where('email', $rp->email)->first()->name, $rp->email);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage(), 500);
            }
            return $this->fail(__("messages.This code has Expired"), 400);
        }
        return $this->fail(__("messages.Wrong code."), 400);
    }
    //
    public function resetpassword(Request $request)
    {

        $validator = Validator::make($request->only(['email', 'password', 'password_confirmation', 'code']), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:6', 'max:255', 'confirmed', 'string'],
            'code' => ['required', 'string', 'exists:password_resets,token'],
        ]);
        if ($validator->fails())
            return $this->fail($validator->errors()->first(), 400);

        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'is_used' => 1, 'token' => request()->code])
            ->first();

        if ($updatePassword) {
            $user = User::query()->where('email', $updatePassword->email)->first();
            $user->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();

            try {
                $this->sendResetPasswordConfirm($user);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage(), 500);
            }
            // $user->tokens()->revoke();
            return $this->success(__("messages.Your password has been changed!"), [], 201);
        }
        return $this->fail(__("messages.invalid data"), 400);
    }
}
