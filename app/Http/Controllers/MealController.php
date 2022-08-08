<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Food;
use App\Models\MealFood;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        return Meal::all(['id','type','description','calorie_count']);
    }

    public function create(Request $request)
    {
        if($request->user()->role_id == 3)
        {
            $fields = Validator::make($request->only(['type','description','food_ids','day']), [
                'type' => 'required|string',
                'description' => 'required|string',
                'food_ids' => 'string|required'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),401);
            }
            $fields = $fields->safe()->all();
            $fields['user_id'] = $request->user()->id;
            $meal = Meal::create($fields);
            $food_list = json_decode($fields['food_ids']);
            foreach($food_list as $food_id)
            {
                $food = Food::find($food_id);
                $data = [
                    'meal_id' => $meal->id,
                    'food_id' => $food->id,
                    'user_id' => $request->user()->id
                ];
                MealFood::create($data);
                $meal->calorie_count += $food->calories;
            }
            $meal->update();
            $message = 'Meal Created Successfully. Awaiting Approval';
            return $this->success(_($message), $meal, 201);
        }
    }

    public function show(Request $request)
    {
        $fields = Validator::make($request->only(['meal_id']) , [
            'meal_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields = $fields->safe()->all();
        $meal = Meal::find($fields['meal_id']);
        return $this->success("Success" , $meal , 201);
    }

    public function edit(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5)
        {
            $fields = Validator::make($request->only('type','meal_id'),[
                'type' => 'string|nullable',
                'meal_id' => 'required|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $meal = Meal::find($fields['meal_id']);
            if($request->user()->id == $meal->user_id){
                if($fields['type']!=null) $meal->name = $fields['type'];
                $meal->update();
                $message = 'Meal Edited Successfully';
                return $this->success(_($message),$meal,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_($message),400);
        }
    }


    public function destroy(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5)
        {
            $fields = Validator::make($request->only(['meal_id']),[
                'meal_id' => 'required|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $meal = Meal::find($fields['meal_id']);
            if($request->user()->id == $meal->user_id){
                $meal->mealfood()->delete();
                $meal->delete();
                $message = 'Meal Deleted Successfully';
                return $this->success(_($message),$meal,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_($message),400);
        }
    }
}
