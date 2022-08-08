<?php

namespace App\Http\Controllers;

use App\Models\Excersise;
use App\Http\Requests\StoreExcersiseRequest;
use App\Http\Requests\UpdateExcersiseRequest;
use App\Models\ExcersiseMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\JsonDecoder;

class ExcersiseController extends Controller
{
    function index()
    {
        return Excersise::all();
    }

    public function show(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required|integer'
        ]);
        return Excersise::find($fields['id']);
    }

    public function create(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
            'name'=>'required|string',
            'burn_calories' => 'required|integer',
            'length' => 'required|integer',
            'excersise_media' => 'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:4096'
        ]);
        $fields['user_id'] = $request->user()->id;
        $excersise = Excersise::create($fields);
        $original_path = 'public/images/excersises/' . $excersise->id;
        Storage::makeDirectory($original_path);
        $image = $request->file('excersise_media');
        $randomString = Str::random(30);
        $image_name = $randomString . $image->getClientOriginalName();
        $path = $image->storeAs($original_path,$image_name);
        $data = [
            'excersise_id' => $excersise->id,
            'excersies_media_url' => $image_name,
            'user_id' => $request->user()->id
        ];
        $excersise_media = ExcersiseMedia::create($data);
        return response($excersise);
        }
        else
        {
            return response('Not a Coach!!');
        }
    }

    public function edit(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
                'excersise_id' => 'integer|required',
                'name' => 'string'
            ]);
            $excersise = Excersise::find($fields['excersise_id']);
            if($excersise->user->id == $request->user()->id)
            {
                if($fields['name'] != null)
                    $excersise->name = $fields['name'];
                $excersise->save();
                return response($excersise);
            }
            return response('fail');
        }
    }

    public function destroy(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
                'excersise_id' => 'required|integer'
            ]);
            $excersise = Excersise::find($fields['excersise_id']);
            if($excersise->user->id == $request->user()->id)
            {
                $excersise->delete();
                return response('Success');
            }
            return response('Fail');
        }
    }
}
