<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Models\Recovery;
use App\Models\UserDevice;
use App\Models\UserInfo;
use App\Models\NewEmail;
use App\Models\Follow;
use App\Models\Block;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\App;
use App\Traits\GeneralTrait;
use App\Traits\EmailTrait;
use App\Traits\NotificationTrait;

class AuthController extends Controller
{
    use GeneralTrait, EmailTrait, NotificationTrait;
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->only('f_name', 'l_name', 'email', 'password', 'password_confirmation', 'm_token'), [
                'f_name' => ['required', 'min:2', 'max:50', 'string'],
                'l_name' => ['required', 'min:2', 'max:50', 'string'],
                'email' => ['required', 'email', 'unique:users,email', 'min:7', 'max:255'],
                'password' => ['required', 'min:6', 'max:255', 'confirmed', 'string'],
                'm_token' => ['string', 'nullable']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $input = $request->only('f_name', 'l_name', 'email', 'password');
            $input['password'] = Hash::make($request['password']);

            if (!User::first()) { //if users table is empty then make the first user the app owner
                $input['f_name'] = 'Vigor';
                $input['l_name'] = 'App';
                $input['role_id'] = 5;
                $input['prof_img_url'] = 'Default/Logo/ku76tfgyuytrewedr432qwsdfgtyhnLOGO.png';
            } //make him super admin
            $user = User::create($input);
            if ($request->m_token)
                UserDevice::updateOrCreate(
                    [
                        'mobile_token' => $request->m_token
                    ],
                    [
                        'user_id' => $user->id
                    ],
                );
            $oClient = OClient::where('password_client', 1)->first();
            $collection = $this->getTokenAndRefreshToken($oClient, $user->email, $request->password);
            $data = [
                "user" => new UserResource(User::find($user->id)),
                "provider" => false,
                'is_verified' => false,
                'is_info' => false,
                "token_type" => $collection->get('token_type'),
                "access_token" => $collection->get('access_token'),
                "refresh_token" => $collection->get('refresh_token'),
                "expire_at" => Carbon::now()->utcOffset((int)config('app.timeoffset'))->addDays(7)->format("Y-m-d H-i-s")
            ];
            $message = 'You need to confirm your email. We have sent you a Verification Code, please check your email.';
            Storage::makeDirectory('public/images/users/' . $user->id);
            if (VerifyUserController::sendCode($user, $request))
                return $this->success(__("messages." . $message), $data, 201);
        } catch (\Exception $e) {
            Storage::deleteDirectory('public/images/users/' . $user->id);
            $user->delete(); //very important code line
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {
        try {
            $oClient = OClient::where('password_client', 1)->first();
            $http = new Client;
            $response = $http->request('POST', 'http://localhost:8002/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);
            $life = $response->getBody();
            $json = json_decode($life, true);
            $collection = collect($json);

            return ($collection);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }
    //get new token
    public function getTokenfromRefreshToken(Request $request)
    {
        try {
            $oClient = OClient::where('password_client', 1)->first();
            $http = new Client;
            // $response = $http->request('POST', 'http://localhost:8002/oauth/token', [
            $response = $http->request('POST', 'http://127.0.0.1:8002/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refreshToken,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '*',
                ],
            ]);

            $result = json_decode((string) $response->getBody(), true);
            return $this->success("", $result);
        } catch (\Exception $e) {
            return $this->fail("Unauthorized", 401);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->only('email', 'password', 'm_token'), [
                'email' => ['required', 'email', 'min:7', 'max:255', 'exists:users,email'],
                'password' => ['required', 'min:6', 'max:255', 'string'],
                'm_token' => ['string', 'nullable']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $oClient = OClient::where('password_client', 1)->first();
                $collection = $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
                //Check if user logged in from different device
                if ($request->m_token) {
                    UserDevice::updateOrCreate(
                        [
                            'mobile_token' => $request->m_token,
                        ],
                        [

                            'user_id' => $request->user()->id,
                        ],
                    );
                }
                $is_verified = true;
                if (!$request->user()->email_verified_at) {
                    $is_verified = false;
                }
                $info = false;
                if ($request->user()->info()->get()->last()) {
                    $info = true;
                }
                $data = [
                    "user" => new UserResource($request->user()),
                    "provider" => false,
                    'is_verified' => $is_verified,
                    'is_info' => $info,
                    "token_type" => $collection->get('token_type'),
                    "access_token" => $collection->get('access_token'),
                    "refresh_token" => $collection->get('refresh_token'),
                    "expire_at" => Carbon::now()->utcOffset((int)config('app.timeoffset'))->addDays(7)->format("Y-m-d H-i-s")
                ];
                if (!($request->user()->deleted_at == NULL)) {
                    app('App\Http\Controllers\AuthController')->recoveryMail($request->user()->id, $request->user()->f_name, $request->user()->email);
                    return $this->success(__("messages.Welcome back, Please check your email for verification code"), $data, 250);
                }
                return $this->success(__("messages.Welcome") . $request->user()->f_name, $data, 201);
            } else {
                return $this->fail('Unauthorised', 401);
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    //delete mac from here
    public function logout(Request $request)
    {
        try {
            $request->user()->devices()->where('mobile_token', $request->header('m_token'))->delete();
            $id = $request->user()->token()->id;
            DB::table('oauth_refresh_tokens')->where('access_token_id', $id)->delete();
            $request->user()->token()->delete();
            return $this->success(__("messages.Logged out"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
    //Logout from all devices
    public function allLogout(Request $request)
    {
        try {
            foreach ($request->user()->tokens()->get() as $token) {
                $id = $token->id;
                DB::table('oauth_refresh_tokens')->where('access_token_id', $id)->delete();
                $token->delete();
            }
            UserDevice::where('user_id', $request->user()->id)->delete();
            return $this->success(__("messages.Logged out"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function useraccount(Request $request)
    {
        try {
            if ($request->user()->role_id == 2 || $request->user()->role_id == 3)
                return $this->success('ok', [
                    "user" => new UserProfileResource($request->user()),
                    "followers" => $request->user()->followers()->count(),
                    "following" => $request->user()->follows()->count(),
                ]);
            return $this->success('ok', [
                "user" => new UserProfileResource($request->user()),
                "following" => $request->user()->follows()->count(),
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
    // following = أنا أتابعهم // Followers = الناس يلي بتابعني
    public function show($id)
    {
        try {
            $me = Auth::user();
            $following = false;
            $is_blocked = false;
            $I_blocke = false;
            $user = User::find($id);
            if ($user && $user->deleted_at == Null) {
                // if ($user->role_id == 2 || $user->role_id == 3) {
                if (!is_null(Follow::where(['follower_id' => $me->id, 'following' => $user->id])->first()))
                    $following = true;
                if (!is_null(Block::where(['user_id' => $user->id, 'blocked' => $me->id])->first()))
                    $is_blocked = true;
                if (!is_null(Block::where(['user_id' => $me->id, 'blocked' => $user->id])->first()))
                    $I_blocke = true;
                return $this->success('ok', [
                    "user" => new UserProfileResource($user),
                    "followers" => $user->followers()->count(),
                    "following" => $user->follows()->count(),
                    "is_following" => $following,
                    "is_blocked" => $is_blocked,
                    "I_block" => $I_blocke,
                ]);
            }
            return $this->fail(__("messages.User not found"));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->only('fname', 'lname', 'bio', 'birthdate', 'gender', 'country', 'height', 'weight', 'height_unit', 'weight_unit', 'img'), [
                'fname' => ['string', 'min:2', 'max:50', 'nullable'],
                'lname' => ['string', 'min:2', 'max:50', 'nullable'],
                'bio' => ['string', 'nullable'],
                'birthdate' => ['string', 'nullable'],
                'gender' => ['string', 'nullable'],
                'country' => ['string', 'nullable'],
                'weight' => ['string', 'nullable', 'min:1', 'string'],
                'height' => ['string', 'nullable', 'min:1', 'string'],
                'height_unit' => ['string', 'min:2', 'max:2', 'nullable'],
                'weight_unit' => ['string', 'min:2', 'max:2', 'nullable'],
                'img' => ['image', 'mimes:jpg,png,jpeg,gif,svg,bmp', 'max:4096', 'nullable']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $user = $request->user();
            $info = $user->info()->get()->last();
            //start update
            // return $request->fname . ' ' . $request->country;
            // $user->f_name = $request->fname;
            if ($request->fname && $request->fname != $user->f_name) {
                $user->f_name = $request->fname;
            }
            if ($request->lname && $request->lname != $user->l_name) {
                $user->l_name = $request->lname;
            }
            if ($request->country && $request->country != $user->country) {
                $user->country = $request->country;
            }
            if ($request->gender && $request->gender != $user->gender && ($request->gender == 'male' || $request->gender == 'female')) {
                $user->gender = $request->gender;
            }
            if ($request->birthdate && $request->birthdate != $user->birth_date) {
                $user->birth_date = Carbon::parse($request->birthdate)->format('Y-m-d');
            }
            if (!(is_null($request->bio) || $request->bio != $user->bio)) {
                $user->bio = $request->bio;
            }
            if ((($request->height && $request->height != $info->height) ||
                    ($request->weight && $request->weight != $info->weight) ||
                    ($request->height_unut && $request->height_unut != $info->height_unit) ||
                    ($request->weight_unit && $request->weight_unit != $info->weight_unit))
                && ($request->height_unit == 'cm' || $request->height_unit == 'ft')
                && ($request->weight_unit == 'kg' || $request->weight_unit == 'lb')
            ) {
                $info->update([
                    'changed_at' => Carbon::now()->utcOffset(config('app.timeoffset'))->format('Y-m-d H:i:s'),
                ]);
                UserInfo::create([
                    'user_id' => $user->id,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'height_unit' => $request->height_unit,
                    'weight_unit' => $request->weight_unit,
                ]);
            }
            if ($user->role_id == 5) { // app Logo
                if ($request->hasFile('img')) {
                    $destination_path = 'public/images/users';
                    $image = $request->file('img');
                    $randomString = Str::random(30);
                    $image_name =  "Default/Logo/" . $randomString . $image->getClientOriginalName();
                    $path = $image->storeAs($destination_path, $image_name);
                    $user->prof_img_url = $image_name;
                }
            } else
            if ($request->hasFile('img')) {
                if ($user->prof_img_url != "Default/RrmDmqreoLbR6dhjSVuFenDAii8uBWdqhi2fYSjK9pRISPykLSdefaultprofileimg.jpg") {
                    Storage::delete('public/images/users/' . $user->prof_img_url);
                }
                $destination_path = 'public/images/users';
                $image = $request->file('img');
                $randomString = Str::random(30);
                $image_name = $user->id . '/' . "profilePic" . '/' . $randomString . $image->getClientOriginalName();
                $path = $image->storeAs($destination_path, $image_name);
                $user->prof_img_url = $image_name;
            }
            $user->save();
            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    //Update email
    public function updateEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->only('oldEmail', 'newEmail', 'password'), [
                'oldEmail' => ['required', 'email', 'exists:users,email',],
                'password' => ['required', 'min:6', 'max:255', 'string'],
                'newEmail' => ['required', Rule::unique('users', 'email')->ignore(Auth::id()), 'email'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $user = Auth::user();
            if ($user->email == $request->oldEmail && Hash::check($request->password, $user->password)) {
                if ($request->user()->email == $request->newEmail) {
                    return $this->success();
                }
                $email_token = Str::upper(Str::random(5));
                $back_token = Str::random(64);
                if (VerifyUserController::newEmailSendCode($request->user(), $email_token, $request->newEmail)) {
                    NewEmail::updateOrCreate([
                        'user_id' => Auth::id(),
                    ], [
                        'new_email' => $request->newEmail,
                        'email_token' => $email_token,
                        'back_token' => $back_token,
                    ]);
                }
                $message = __('messages.You need to confirm your new email. We have sent you a Verification Code, please check your new email.');
                return $this->success(__($message), ["token" => $back_token], 201);
            }
            return $this->fail(__("messages.Invalid credentials"), 400);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function confirmNewEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->only('token', 'code'), [
                'code' => ['required', 'string',],
                'token' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $user = $request->user();
            if ($NewEmail = NewEmail::where(['user_id' => Auth::id(), 'back_token' => $request->token, 'email_token' => $request->code])->first()) {
                if ((Carbon::parse($NewEmail->updated_at)->addMinutes(20))->gte(Carbon::now())) {
                    $user->email = $NewEmail->new_email;
                    $user->save();
                    $NewEmail->delete();
                    return $this->success(__("messages.Your Email has been changed"));
                }
                VerifyUserController::newEmailReGetCode($request);
            }
            return $this->fail(__("messages.Wrong code."), 400);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }
    //End update email

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->only('oldPassword', 'newPassword', 'newPassword_confirmation'), [
                'newPassword' => ['required', 'min:6', 'max:255', 'confirmed', 'string'],
                'oldPassword' => ['required', 'min:6', 'max:255', 'string'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $user = $request->user();
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                $this->sendResetPasswordConfirm($user);
                return $this->success(__("messages.Password changed"));
            }
            return $this->fail(__("messages.Invalid credentials"), 400);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function firstdestroy(Request $request)
    {
        try {
            $user = $request->user();
            if (Hash::check($request->password, $user->password) || !is_null($user->providers()->get())) {
                $this->sendDeleteEmail($user->f_name, $user->email);
                foreach ($request->user()->token()->get() as $token) {
                    $id = $token->id;
                    DB::table('oauth_refresh_tokens')->where('access_token_id', $id)->delete();
                    $token->delete();
                }
                $user->devices()->delete();
                $user->deleted_at = Carbon::now();
                $user->save();
                return $this->success(__("messages.account deleted"));
            }
            return $this->fail(__("messages.Invalid credentials"));
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function recoveryMail($id, $f_name, $email)
    {
        try {
            $code = Str::upper(Str::random(5));
            $this->sendRecoverEmail($f_name, $code, $email);
            Recovery::updateOrCreate([
                'user_id' => $id,
            ], [
                'code' => $code,
            ]);
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function reGetRecoveryCode(Request $request)
    {
        try {
            if ($request->user()->deleted_at != NULL) {
                app('App\Http\Controllers\AuthController')->recoveryMail($request->user()->id, $request->user()->f_name, $request->user()->email);
                return $this->success(__("messages.we resent you new code"), [], 200);
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function recoverVerify(Request $request)
    {
        try {
            if ($request->user()->deleted_at != NULL) {
                $validator = Validator::make($request->only('code'), [
                    'code' => ['required', 'min:5', 'max:5', 'string']
                ]);
                if ($validator->fails())
                    return $this->fail($validator->errors()->first(), 400);
                $user = $request->user();
                if (is_null(Recovery::where('user_id', $user->id)->first()) || (Carbon::parse(Recovery::where('user_id', $user->id)->first()->updated_at)->addMinutes(20))->lt(Carbon::now())) {
                    app('App\Http\Controllers\AuthController')->recoveryMail($request->user()->id, $request->user()->f_name, $request->user()->email);
                    return $this->success(__("messages.Welcome back, Please check your email for verification code"), [], 200);
                }
                if ($recover = Recovery::where(['user_id' => $user->id, 'code' => $request->code])->first()) {
                    $user->deleted_at = NULL;
                    $user->save();
                    $recover->delete();
                    return $this->success(__("messages.Your e-mail has been verified."));
                }
                return $this->fail(__("messages.Wrong code."));
            }
            return $this->success(__("messages.Account already active"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function info(Request $request)
    {
        try {
            $validator = Validator::make($request->only('height', 'weight', 'height_unit', 'weight_unit', 'gender', 'country', 'birth_date'), [
                'height' => ['required', 'min:1', 'max:255', 'string'],
                'weight' => ['required', 'min:1', 'max:255', 'string'],
                'height_unit' => ['required', 'string', 'min:2', 'max:2'],
                'weight_unit' => ['required', 'string', 'min:2', 'max:2'],
                'gender' => ['required', 'string'],
                'birth_date' => ['required', 'string'],
                'country' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            //
            if (!(($request->height_unit == 'cm' || $request->height_unit == 'ft') &&
                ($request->weight_unit == 'kg' || $request->weight_unit == 'lb')
            )) {
                return $this->fail(__("messages.invalid data"), 400);
            }
            $user = $request->user();
            $user->country = $request->country;
            $user->birth_date = $request->birth_date;
            $user->gender = $request->gender;
            $user->save();
            //
            UserInfo::create([
                'user_id' => $user->id,
                'height' => $request->height,
                'weight' => $request->weight,
                'height_unit' => $request->height_unit,
                'weight_unit' => $request->weight_unit,
            ]);
            return $this->success();
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
}
