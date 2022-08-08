<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeExcercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;

class ChallengesExcercisesController extends Controller
{
    use GeneralTrait;
    //return list to chose from
    public function index()
    {
        $data = [];
        $exs = ChallengeExcercise::all(['id', 'name', 'desc', 'img_path', 'ca']);
        foreach ($exs as $ex) {
            $data[] = [
                'id' => $ex->id,
                'name' => $ex->name,
                'desc' => $ex->desc,
                'ca' => $ex->ca,
                'img' => 'public/images/ChallengesEx/' . $ex->img_path
            ];
        }
        return $this->success('ok', $data);
    }

    //create ex
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->only('name', 'desc', 'img', 'ca'), [
                'name' => ['string', 'min:2', 'max:100', 'required',],
                'desc' => ['string', 'min:2', 'max:1000', 'required'],
                'img' => ['image', 'mimes:jpg,png,jpeg,gif,svg,bmp', 'max:4096', 'required'],
                'ca' => ['required', 'numeric'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $ex = ChallengeExcercise::firstOrCreate([
                'name' => $request->name
            ], [
                'desc' => $request->desc,
                'ca' => $request->ca
            ]);
            if ($request->hasFile('img')) {
                $destination_path = 'public/images/ChallengesEx/';
                $image = $request->file('img');
                $randomString = Str::random(30);
                $image_name =  $ex->id . '/' . $randomString . $image->getClientOriginalName();
                $path = $image->storeAs($destination_path, $image_name);
                $ex->img_path = $image_name;
                $ex->save();
            }
            return $this->success();
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            if ($ex = ChallengeExcercise::find($id)) {
                $ex->img_path = 'public/images/ChallengesEx/' . $ex->img_path;
                return $this->success('ok', $ex);
            }
            return $this->fail(__('messages.Not found'));
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    //no need
    public function update(Request $request, $id)
    {
        try {
            if ($ex = ChallengeExcercise::find($id)) {
                $validator = Validator::make($request->only('name', 'desc', 'img', 'ca'), [
                    'desc' => ['string', 'min:2', 'max:1000'],
                    'name' => ['string', 'min:2', 'max:100'],
                    'img' => ['image', 'mimes:jpg,png,jpeg,gif,svg,bmp', 'max:4096'],
                    'ca' => ['integer'],
                ]);
                if ($validator->fails())
                    return $this->fail($validator->errors()->first(), 400);
                if ($request->desc && $request->desc != $ex->desc) {
                    $ex->desc = $request->desc;
                    $ex->save();
                }
                if ($request->name && $request->name != $ex->name) {
                    $ex->name = $request->name;
                    $ex->save();
                }
                if ($request->ca && $request->ca != $ex->ca) {
                    $ex->ca = $request->ca;
                    $ex->save();
                }
                if ($request->hasFile('img')) {
                    Storage::delete('public/images/ChallengesEx/' . $ex->img_path);
                    $destination_path = 'public/images/ChallengesEx/';
                    $image = $request->file('img');
                    $randomString = Str::random(30);
                    $image_name =  $ex->id . '/' . $randomString . $image->getClientOriginalName();
                    $path = $image->storeAs($destination_path, $image_name);
                    $ex->img_path = $image_name;
                    $ex->save();
                }
                return $this->success();
            }
            return $this->fail(__('messages.Not found'));
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    //delete ex
    public function destroy($id)
    {
        try {
            if ($ex = ChallengeExcercise::find($id)) {
                Storage::deleteDirectory('public/images/ChallengesEx/' . $ex->id);
                $ex->delete();
                return $this->success();
            }
            return $this->fail(__('messages.Not found'));
        } catch (\Exception $e) {
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }
}
