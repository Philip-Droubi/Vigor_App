<?php

namespace App\Http\Controllers;

use App\Models\WorkoutExcersises;
use App\Http\Requests\StoreWorkoutExcersisesRequest;
use App\Http\Requests\UpdateWorkoutExcersisesRequest;
use App\Models\Excersise;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutExcersisesController extends Controller
{

    public function index()
    {
        return WorkoutExcersises::all();
    }

    public function show(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required|integer'
        ]);
        return WorkoutExcersises::find($fields['id']);
    }

    public function create(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
            'workout_id'=>'required|integer',
            'excersise_id' => 'required|integer',
            'count' => 'integer',
            'length' => 'integer'
        ]);
        if(!array_key_exists('count',$fields)  && !array_key_exists('length',$fields))
        {
            return response('Count Or Length Required',406);
        }
        if(array_key_exists('count',$fields)  && array_key_exists('length',$fields))
        {
            return response('Count Or Length Required',406);
        }
            $excersise = Excersise::find($fields['excersise_id']);
            $fields['user_id'] = $request->user()->id;
            $workoutexcersise = WorkoutExcersises::create($fields);
            $workout = Workout::find($fields['workout_id']);
            $workout['excersise_count'] = $workout->excersise_count + 1;
            $workout->predicted_burnt_calories += $excersise->burn_calories;
            if($fields['count'] != null)
            {
                $length = $excersise->length * $fields['count'];
            }
            else
            {
                $length = $fields['length'];
            }
            $workout->length += $length;
            $workout->update();
            return response($workoutexcersise);
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
                'workout_excersises_id' => 'required|integer',
                'count' => 'integer|nullable',
                'length' => 'integer|nullable'
            ]);
            $workout_excersise = WorkoutExcersises::find($fields['workout_excersises_id']);
            if($request->user()->id == $workout_excersise->user->id)
            {
                if($fields['count'] != null)
                {
                    if($workout_excersise->count == null) return response("Cant change a counter to a timer");
                    $workout_excersise->count = $fields['count'];
                }
                if($fields['length'] != null)
                {
                    if($workout_excersise->length == null) return response("Cant change a counter to a timer");
                    $workout_excersise->length = $fields['length'];
                }
                $workout_excersise->update();
                return response($workout_excersise);
            }
            return response('Not the coach!');
        }
    }

    public function destroy(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
                'workout_excersises_id' => 'required|integer'
            ]);
            $workout_excersise = WorkoutExcersises::find($fields['workout_excersises_id']);
            if($request->user()->id == $workout_excersise->user->id)
            {
                $workout_excersise->delete();
                return response('Success');
            }
            return response('Not the coach!');
        }
    }
}
