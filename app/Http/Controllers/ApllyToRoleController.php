<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendCVResponse;
use Illuminate\Support\Facades\Gate;

class ApllyToRoleController extends Controller
{
    use GeneralTrait;
    public function DowngradeRole(Request $request)
    {
        try {
            if (Gate::allows('Coach-Dietitian-Protection')) {
                $validator = Validator::make($request->only('password'), [
                    'password' => ['required', 'min:6', 'max:255', 'string'],
                ]);
                if ($validator->fails())
                    return $this->fail($validator->errors()->first(), 400);
                $user = $request->user();
                if (Hash::check($request->password, $user->password)) {
                    $user->posts()->delete();
                    $user->CV()->delete();
                    $user->challenges()->delete();
                    Storage::deleteDirectory('public/images/users/' . Auth::id() . '/CV');
                    Storage::deleteDirectory('public/images/users/' . Auth::id() . '/posts');
                    Storage::deleteDirectory('public/images/users/' . Auth::id() . '/challenges');
                    $user->role_id = 1;
                    $user->save();
                    return $this->success(__("messages.You are now a normal user"));
                }
                return $this->fail(__("messages.Access denied"));
            }
            return $this->fail(__("messages.Access denied"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->only(['desc', 'role', 'cv',]), [
                'desc' => ['string', 'max:1500', 'nullable'],
                'role' => ['integer', 'required', 'between:2,3'],
                'cv' => ['file', 'max:4096', 'mimes:pdf']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            //
            $request->cv;
            $user = $request->user();
            if ($user->role_id == 1 && is_null($user->CV()->first()) && !$request->cv)
                return $this->fail(__("messages.CV is required"));
            if ($user->role_id != 1 && is_null($user->CV()->first()))
                return $this->fail(__("messages.Only users with no role can aplly"));
            if ($request->hasFile('cv') && !is_null($user->CV()->first())) {
                Storage::deleteDirectory('public/images/users/' . Auth::id() . '/CV');
            }
            //
            $cvObj = CV::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'description' => (string)$request->desc,
                'role_id' => $request->role,
            ]);
            if ($request->hasFile('cv')) {
                $destination_path = 'public/images/users';
                $cv = $request->file('cv');
                $randomString = Str::random(30);
                $cv_name = $user->id . '/' . "CV" . '/' . $randomString . $cv->getClientOriginalName();
                $cvObj->cv_path = $cv_name;
                $cvObj->save();
                $cv->storeAs($destination_path, $cv_name);
            }
            return $this->success(__("messages.CV has been sent successfully"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getLine(), 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $user = $request->user();
            if ($cv = $user->CV()->first(['id', 'user_id', 'cv_path', 'description', 'role_id', 'acception'])) {
                $cv = collect($cv);
                $cv['role'] = Role::where('id', $cv['role_id'])->first()->name;
                $cv['role_id'] = (int)$cv['role_id'];
                $cv['cv_path'] = (string)'storage/images/users/' . $cv['cv_path'];
                $data = [
                    $cv
                ];
                return $this->success('ok', $data);
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    //user_id
    public function showOthers(Request $request, $id)
    {
        try {
            $user = $request->user();
            $other = User::find($id);
            if ($cv = CV::where('user_id', $id)->first(['id', 'user_id', 'cv_path', 'description', 'role_id'])) {
                $cv = collect($cv);
                $cv['role'] = Role::where('id', $cv['role_id'])->first()->name;
                $cv['role_id'] = (int)$cv['role_id'];
                $cv['cv_path'] = (string)'storage/images/users/' . $cv['cv_path'];
                $data = [
                    $cv
                ];
                return $this->success('ok', $data);
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = $request->user();
            if ($user->role_id == 2 || $user->role_id == 3)
                return $this->fail(__('messages.You can not delete your cv'));
            if (!is_null($user->CV()->first())) {
                CV::query()->delete('user_id', Auth::id());
                Storage::deleteDirectory('public/images/users/' . Auth::id() . '/CV');
            }
            return $this->success(__("messages.Your CV has been deleted"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getLine(), 500);
        }
    }

    public function Accept(Request $request, $id)
    {
        try {
            $cv = CV::find($id);
            if ($cv && $cv->acception == 0) {
                if ($user = User::find($cv->user_id)) {
                    $cv->acception = 1;
                    $cv->save();
                    $user->role_id = $cv->role_id;
                    $user->save();
                    (dispatch(new SendCVResponse($user, Role::where('id', $cv->role_id)->first()->name, true)));
                    return $this->success();
                }
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function Refuse(Request $request, $id)
    {
        try {
            $cv = CV::find($id);
            if ($cv && $cv->acception == 0) {
                if ($user = User::find($cv->user_id)) {
                    Storage::deleteDirectory('public/images/users/' . $cv->user_id . '/CV');
                    $cv->delete();
                    dispatch(new SendCVResponse($user, Role::where('id', $cv->role_id)->first()->name, false));
                    return $this->success();
                }
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            // return $this->fail(__('messages.somthing went wrong'), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }
}
