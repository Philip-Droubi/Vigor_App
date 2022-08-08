<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class DiseasesController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        return $this->success('ok', Disease::query()->orderBy('name')->get(['id', 'name']));
    }

    public function store(Request $request)
    {
        if (Gate::allows('Add-Diseseas-Protection')) {
            $validator = Validator::make($request->only('name'), [
                'name' => ['required', 'min:2', 'max:50', 'string', 'unique:diseases,name'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            Disease::create([
                'name' => $request->name
            ]);
            return $this->success();
        }
        return $this->fail(__("messages.Access denied"), 401);
    }

    public function update($id, Request $request)
    {
        if (Gate::allows('Add-Diseseas-Protection')) {
            if (Disease::where('id', $id)->first()) {
                $validator = Validator::make($request->only('name'), [
                    'name' => ['required', 'min:2', 'max:50', 'string', Rule::unique('diseases', 'name')->ignore(Disease::where('id', $id)->first())],
                ]);
                if ($validator->fails())
                    return $this->fail($validator->errors()->first(), 400);
                Disease::Where('id', $id)->update([
                    'name' => $request->name
                ]);
                return $this->success();
            }
            return $this->fail(__("messages.Not found"), 404);
        }
        return $this->fail(__("messages.Access denied"), 401);
    }


    public function destroy($id)
    {
        if (Gate::allows('Add-Diseseas-Protection')) {
            if (Disease::where('id', $id)->first()) {
                Disease::Where('id', $id)->delete();
                return $this->success();
            }
            return $this->fail(__("messages.Not found"), 404);
        }
        return $this->fail(__("messages.Access denied"), 401);
    }
}
