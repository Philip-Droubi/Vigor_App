<?php

namespace App\Http\Controllers;

use App\Models\MealFood;
use App\Http\Requests\StoreMealFoodRequest;
use App\Http\Requests\UpdateMealFoodRequest;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealFoodController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MealFood::all(['id','meal_id','food_id']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($data)
    {
            $mealfood = MealFood::create($data);
            $message = 'MealFood Created Successfully';
            return $this->success(_("messages." . $message), $mealfood, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMealFoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMealFoodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MealFood  $mealFood
     * @return \Illuminate\Http\Response
     */
    public function show(MealFood $mealFood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MealFood  $mealFood
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->user()->role_id == 3)
        {
            $fields = Validator::make($request->only('mealfood_id','meal_id','food_id'),[
                'mealfood_id' => 'required|integer',
                'meal_id' => 'integer|nullable',
                'food_id' => 'nullable|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $mealfood = MealFood::find($fields->mealfood_id);
            if($request->user()->id == $mealfood->user_id){
                if($fields['meal_id']!=null) $mealfood->name = $fields['meal_id'];
                if($fields['food_id']!=null) $mealfood->calories = $fields['food_id'];
                $mealfood->update();
                $message = 'MealFood Edited Successfully';
                return $this->success(_("message." . $message),$mealfood,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_('message.' . $message),400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMealFoodRequest  $request
     * @param  \App\Models\MealFood  $mealFood
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMealFoodRequest $request, MealFood $mealFood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MealFood  $mealFood
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->user()->role_id == 3)
        {
            $fields = Validator::make($request->only('mealfood_id'),[
                'mealfood_id' => 'required|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $mealfood = MealFood::find($fields->mealfood_id);
            if($request->user()->id == $mealfood->user_id){
                $mealfood->delete();
                $message = 'Food Deleted Successfully';
                return $this->success(_("message." . $message),$mealfood,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_('message.' . $message),400);
        }
    }
}
