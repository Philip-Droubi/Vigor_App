<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\Provider;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;
use App\Traits\GeneralTrait;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    use GeneralTrait;
    public function handleProviderCallback(Request $request)
    {
        try {
            $request->validate([
                'provider' => ['required', 'string'],
                'access_provider_token' => ['required', 'string']
            ]);
            $provider = $request->provider;
            $validated = $this->validateProvider($provider);
            if (!is_null($validated))
                return $validated;
            $providerUser = Socialite::driver($provider)->userFromToken($request->access_provider_token);
            // dd($providerUser = Socialite::driver($provider)->userFromToken($request->access_token));

            if ($provider == 'google' && !$providerUser->user['email_verified']) {
                return $this->fail(__("messages.Your Email must be VERIFIED by google first"));
            }
            $password = 'pFAZtE360HkS9lRFMLO74EayfPn3ka5HRnvXCIXG8D7Cwhxzmo';
            $user = User::firstOrCreate(
                [
                    'email' => $providerUser->getEmail()
                ],
                [
                    'email_verified_at' => Carbon::now(),
                    'f_name' => $providerUser->getName(),
                    'prof_img_url' => $providerUser->getAvatar(),
                    'password' => Hash::make($password),
                ]
            );
            if (!$user->role_id) {
                $user->role_id = 1;
                $user->save();
            }
            Provider::create(
                [
                    'provider' => $provider,
                    'provider_id' => $providerUser->getId(),
                    'user_id' => $user->id,
                ]
            );
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
            $info = false;
            if ($user->info()->get()->last()) {
                $info = true;
            }
            $collection = $this->getTokenAndRefreshToken($oClient, $user->email, $password);
            $data = [
                "user" => new UserResource($user),
                'provider' => true,
                'is_verified' => true,
                "is_info" => $info,
                "token_type" => $collection->get('token_type'),
                "access_token" => $collection->get('access_token'),
                "refresh_token" => $collection->get('refresh_token'),
                "expire_at" => Carbon::now()->utcOffset(config('app.timeoffset'))->addDays(7)->format("Y-m-d H-i-s")
            ];
            if (!($user->deleted_at == NULL)) {
                app('App\Http\Controllers\AuthController')->recoveryMail($user->id, $user->f_name, $user->email);
                return $this->success(__("messages.Welcome back, Please check your email for verification code"), $data, 250);
            }
            return $this->success(__("messages.Welcome") . $user->f_name, $data, 201);
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return response()->json(["message" => __("messages.Please login using google only")], 400);
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
}
