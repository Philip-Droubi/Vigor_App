<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class FoodController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success("Success",Food::all(['id','name','description','calories','food_image_url'])->where('approval' ,1)->map(function($data) {
            if(!$data->description)
            {
                $data->description = '';
            }
            $data->food_image_url = 'public/food/' . $data->id .'/' .$data->food_image_url;
            return $data;
        }),200);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->role_id == 3)
        {
            $fields = Validator::make($request->only(['name','calories','description','food_image']), [
                'name' => 'required|string',
                'calories' => 'required|integer',
                'description' => 'nullable|string',
                'food_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:4096'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $fields['user_id'] = $request->user()->id;
            $food = Food::create($fields);
            if($request->hasFile('food_image'))
            {
                $original_path = 'public/images/food/' . $food->id;
                Storage::makeDirectory($original_path);
                $image = $request->file('food_image');
                $randomString = Str::random(30);
                $image_name =$randomString . $image->getClientOriginalName();
                $path = $image->storeAs($original_path,$image_name);
                $food->food_image_url =$image_name;
                $food->update();
            }
            $message = 'Food Created Successfully . Awaiting Approval';
            return $this->success(_("messages." . $message), $food, 201);
        }
        else{
            return $this->fail("Not a dietitian!",401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFoodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $fields = Validator::make($request->only(['food_id']) , ['food_id' => 'required|integer']);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields = $fields->safe()->all();

        $food = Food::find($fields['food_id']);
        return $this->success("Success" , $food ,201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5)
        {
            $fields = Validator::make($request->only(['name','calories','food_id','food_image']),[
                'name' => 'string|nullable',
                'calories' => 'integer|nullable',
                'description' => 'string|nullable',
                'food_id' => 'required|integer',
                'food_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:4096'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $food = Food::find($fields['food_id']);
            if($request->user()->id == $food->user_id){
                if($fields['name']!=null) $food->name = $fields['name'];
                if($fields['calories']!=null) $food->calories = $fields['calories'];
                if($fields['description']!=null) $food->calories = $fields['description'];
                $original_path = 'public/images/food/' . $food->id;
                if(!file_exists($original_path))
                {
                    Storage::makeDirectory($original_path);
                }
                if($request->hasFile('food_image'))
                {
                    $old_image = $food->food_image_url;
                    Storage::delete($original_path . '/' . $old_image);
                    $image = $request->file('food_image');
                    $randomString = Str::random(30);
                    $image_name = $randomString . $image->getClientOriginalName();
                    $path = $image->storeAs($original_path,$image_name);
                    $food->food_image_url = $image_name;
                }
                $food->update();
                $message = 'Food Edited Successfully';
                return $this->success(_("message." . $message),$food,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_('message.' . $message),401);
        }
        else
        {
            return $this->fail("Not a dietitian!",401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFoodRequest  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5)
        {
            $fields = Validator::make($request->only('food_id'),[
                'food_id' => 'required|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $food = Food::find($fields['food_id']);
            if($request->user()->id == $food->user_id){
                $food->delete();
                $message = 'Food Deleted Successfully';
                return $this->success(_("message." . $message),$food,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_('message.' . $message),400);
        }
    }
}
