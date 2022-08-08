<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Http\Requests\StorePracticeRequest;
use App\Http\Requests\UpdatePracticeRequest;
use App\Models\Excersise;
use App\Models\Workout;
use App\Traits\GeneralTrait;
use Composer\Pcre\Preg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PracticeController extends Controller
{
    use GeneralTrait;
    public function initiate(Request $request)
    {
        $fields = Validator::make($request->only('workout_id','excersises_played'),[
            'workout_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields['user_id'] = $request->user()->id;
        $practice = Practice::create($fields);
        return $this->success("Start Practicing!",$practice,201);
    }
    public function practice(Request $request)
    {
        $fields = Validator::make($request->only('excersise_id','practice_id','length'),[
            'excersise_id' => 'required|integer',
            'length' => 'requried|integer',
            'practice_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields = $fields->safe()->all();
        $practice = Practice::find($fields['practice_id']);
        $excersise = Excersise::find($fields['excersise_id']);
        $practice->summary_calories += $excersise->burn_calories;
        $practice->summary_time += $fields['length'];
        $excersises_played = json_decode($practice->excersises_played);
        $excersises_played[] = $fields['excersise_id'];
        $practice->excersises_played = json_encode($excersises_played);
        $practice->update();
        return $this->success("Next Excersise!" , $practice , 200);
    }

    public function summary(Request $request)
    {
        $fields = Validator::make($request->only('practice_id'),[
            'practice_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $fields = $fields->safe()->all();
        $practice = Practice::find($fields['practice_id']);
        if(count(json_decode($practice->excersises_played)) == 0)
        {
            $practice->query()
                     ->where('id',$fields['practice_id'])
                     ->delete();
            return $this->success("Not Practiced!", [] , 200);
        }
        return $this->success('Workout Summary' , $practice , 200);
    }
}
