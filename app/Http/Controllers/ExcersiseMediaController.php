<?php

namespace App\Http\Controllers;

use App\Models\ExcersiseMedia;
use App\Http\Requests\StoreExcersiseMediaRequest;
use App\Http\Requests\UpdateExcersiseMediaRequest;
use App\Models\Excersise;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExcersiseMediaController extends Controller
{
    use GeneralTrait;
    function index()
    {
        return ExcersiseMedia::all();
    }

    public function show(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required|integer'
        ]);
        return ExcersiseMedia::find($fields['id']);
    }

    public function create(array $data)
    {
        // $excersise = Excersise::find($data['excersise_id']);
        // $original_path = 'public/images/excersises/' . $excersise->id;
        // Storage::makeDirectory($original_path);
        // $image = $data->file('excersise_media');
        // error_log("hello");
        // $randomString = Str::random(30);
        // $image_name = $randomString . $image->getClientOriginalName();
        // $path = $image->storeAs($original_path,$image_name);
        // $data = [
        //     'excersise_id' => $excersise->id,
        //     'excersies_media_url' => $image_name,
        //     'user_id' => $data['user_id']
        // ];
        // ExcersiseMedia::create($data);
        // return $this->success();
    }

    public function edit(ExcersiseMedia $excersiseMedia)
    {
        //
    }

    public function destroy(ExcersiseMedia $excersiseMedia)
    {
        //
    }
}
