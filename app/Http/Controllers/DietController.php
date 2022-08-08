<?php

namespace App\Http\Controllers;

use App\Models\Diet;
use App\Http\Requests\StoreDietRequest;
use App\Http\Requests\UpdateDietRequest;
use App\Models\DietMeal;
use App\Models\DietReview;
use App\Models\FavoriteDiet;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\JsonDecoder;

class DietController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Diet::all(['id','name','user_id','created_by']);
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
            $fields = Validator::make($request->only('name','meals'), [
                'name' => 'required|string',
                'meals' => 'required|string'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $fields['created_by'] = $request->user()->id;
            $days = json_decode($fields['meals']);
            unset($fields['meals']);
            $diet = Diet::create($fields);
            $message = 'Diet Created Successfully';
            $i =0;
            $result = [];
            foreach($days as $daymeals)
            {
                $i++;
                $fullmeals =[];
                //$daymeals = json_decode($daymeals);
                foreach($daymeals as $meal)
                {
                    $data = [
                        'meal_id' => $meal,
                        'diet_id' => $diet->id,
                        'day' => $i
                    ];
                    $dietmeal = DietMeal::create($data);
                    $fullmeals[] = $dietmeal->meal;
                }
                $result[] = [
                    'day' => $i,
                    'meals' => $fullmeals
                ];
            }
            $diet = [
                'name' => $diet->name,
                'created_by' => $request->user(),
                'schedule' => $result
            ];
            return $this->success(_($message), $diet, 201);
        }
    }


    public function show(Request $request)
    {
        $fields = Validator::make($request->only(['diet_id']) , [
            'diet_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields = $fields->safe()->all();
        $diet = Diet::find($fields['diet_id']);
        $dietmeals = DietMeal::where('diet_id',$diet->id);
        return $this->success("Success" , [$diet,$diet->dietmeal->meal] , 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet  $diet
     * @return \Illuminate\Http\Response
     */
    //check if user exists
    public function edit(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5 || $request->user()->id == 3)
        {
            $fields = Validator::make($request->only('diet_id','name','user_id'),[
                'diet_id' => 'required|integer',
                'user_id' => 'integer|nullable',
                'name' => 'nullable|string'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $diet = Diet::find($fields->diet_id);
            if($request->user()->id == $diet->user_id){
                if($fields['name']!=null) $diet->name = $fields['name'];
                if($fields['user_id']!=null)
                {
                    if(User::find($fields['user_id'])!=null)
                    {
                        $diet->user_id = $fields['user_id'];
                    }
                    $message = "Deignated User doesn't exist";
                    return $this->fail(_('message.' . $message),401);
                }
                $diet->update();
                $message = 'Diet Edited Successfully';
                return $this->success(_("message." . $message),$diet,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_('message.' . $message),400);
        }
    }

    public function destroy(Request $request)
    {
        if($request->user()->role_id == 4 || $request->user()->role_id == 5)
        {
            $fields = Validator::make($request->only(['diet_id']),[
                'diet_id' => 'required|integer'
            ]);
            if($fields->fails())
            {
                return $this->fail($fields->errors()->first(),400);
            }
            $fields = $fields->safe()->all();
            $diet = Diet::find($fields['diet_id']);
            if($request->user()->id == $diet->created_by){
                $diet->meal()->delete();
                $diet->delete();
                $message = 'Diet Deleted Successfully';
                return $this->success(_($message),$diet,201);
            }
            $message = 'Permission Denied. Not the owner';
            return $this->fail(_($message),400);
        }
    }

    public function favorite(Request $request)
    {
        $fields = Validator::make($request->only('diet_id'), [
            'diet_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),400);
        }
        $fields = $fields->safe()->all();
        $fields['user_id'] = $request->user()->id;
        $favorite = FavoriteDiet::create($fields);
        return $this->success("Added to favorites!" , $favorite , 200);
    }

    public function unfavorite(Request $request)
    {
        $fields = Validator::make($request->only('diet_id'), [
            'diet_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),400);
        }
        $fields = $fields->safe()->all();
        $favorite = FavoriteDiet::where('user_id', $request->user()->id)
                                   ->where('diet_id' ,$fields['diet_id']);
        $favorite->delete();
        return $this->success("Deleted from favorites!" , $favorite , 200);
    }

    public function favorites()
    {
        $user_id = Auth::id();
        $favorites = User::find($user_id)->favorites->diet;
        return $this->success("Favorites" , $favorites , 200);
    }

    public function review(Request $request)
    {
        $fields = Validator::make($request->only('diet_id','description','stars') , [
            'diet_id' => 'required|integer',
            'description' => 'required|string',
            'stars' => 'required|integer:1,2,3,4,5'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),400);
        }
        $fields = $fields->safe()->all();
        $fields['user_id'] = $request->user()->id;
        $review = DietReview::create($fields);
        $diet = Diet::find($fields['diet_id']);
        $review_count = $diet->review->count();
        $review_rating = (float)(($diet->review_count * ($review_count-1)) + $fields['stars'] )/($review_count);
        $diet->review_count = $review_rating;
        $diet->update();
        return $this->success("Done" , $diet , 200);
    }
}
