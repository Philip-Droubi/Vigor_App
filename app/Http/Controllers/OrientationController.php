<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\GeneralTrait;
use Database\Seeders\WorldSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvolvedGroup\LaravelLangCountry\LangCountry;
use Nnjeim\World\World;

class OrientationController extends Controller
{
    use GeneralTrait;
    public function prototype_algorithm(Request $request)
    {
        $fields = Validator::make($request->only('user_id') , [
            'user_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $user = User::find($fields['user_id']);
        $coach_list = User::where('role_id' , 2)->sortBy('review');
        $list = array();
        foreach($coach_list as $coach)
        {
            if($coach->region == $user->region && $coach->trainees->count() < 15 && sizeof($list) < 10)
            {
                $list[] = $coach;
            }
        }
        if(sizeof($list) < 10)
        {
            foreach($coach_list as $coach)
            {
                if($coach->language == $user->language && $coach->trainess->count() < 15 && sizeof($list) < 10)
                {
                    $list[] =$coach;
                }
            }
        }
        else
        {
            foreach($coach_list as $coach)
            {
                if($coach->trainees->count()<15 && sizeof($list) < 10)
                {
                    $list[] = $coach;
                }
            }
        }
        return $list;
    }

    public function second_algo(Request $request)
    {
        $user = $request->user()->id;
        $user = User::find($user);
        $user_country = $user->country;
        $coaches = User::query()->where('role_id',2)->where('country',$user_country);
        $by_country_list = array();
        $by_langugae_list = array();
        foreach($coaches as $coach)
        {
            if($coach->trainees->count() < 15)
            {
                $by_country_list[] = $coach;
            }
        }

        $language = $user->lang;
        $coaches = User::where('role_id',2);
        $world_countries_languages = World::countries();
        $lang = LangCountry::lang();
        foreach($world_countries_languages['data'] as $country)
        {
            if($country['lang'] == $lang)
            {
                $countries[] = $country;
            }
        }
        foreach($countries as $country)
        {
            $coaches[] = User::where('role_id',2)->where('lang_country',$country['lang']);
        }

        return $coaches;


    }
}
