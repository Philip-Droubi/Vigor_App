<?php

namespace App\Http\Controllers;

use App\Models\DietSubscribe;
use App\Models\Practice;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Callback;

class HomePageController extends Controller
{
    use GeneralTrait;
    public $default_sequence = ['Chest' , 'Arms' , 'Rest' , 'Back' , 'Legs' ,'Rest' , 'Stomach'];
    public function reccomendations()
    {
        $user = User::find($request->user()->id);

        $last_practice = User::practice->last();
        if($last_practice == null)
        {
            //default way or coach way
        }
        else
        {
            //go by the streak
        }
    }

    public function summary()
    {
        $burnt_calories = 0;
        $workouts_played = 0;
        $user = User::find(Auth::id());
        $weight =0;
        $height =0;
        $user->info->last()->height_unit == 'ft' ? $height = $user->info->last()->height * 30.48 : $height = $user->info->last()->height;
        $user->info->last()->weight_unit == 'lb' ? $weight = $user->info->last()->weight / 2.205 : $weight = $user->info->last()->weight;
        //return response([$weight,$height]);
        $bmi = 10000 * $weight / ($height * $height);
        $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $tillDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $practices = Practice::query()
                             ->where('user_id' , $user->id)
                             ->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString());
        return response(json_encode($practices));
        foreach($practices as $practice)
        {
            $burnt_calories += $practice->getQuery()->where('created_at', '=', Carbon::now()->subMonth()->month);
        }
        $workouts_played = $user->practice->where('created_at', '=', Carbon::now()->subMonth()->month)->count();
        //$current_diet = DietSubscribe::where('user_id',$user->id)->last();
        $data = [
            'BMI' => $bmi,
            'Workouts Played' => $workouts_played,
            'Calories Burnt' => $burnt_calories,
            //'Current Diet' => $current_diet
        ];
        return $this->success("Summary" , $data , 200);
    }

    public function monthly_summary()
    {

    }

    public function yearly_summary()
    {

    }
}
