<?php

namespace App\Http\Controllers;

use App\Models\CoachTrainees;
use App\Http\Requests\StoreCoachTraineesRequest;
use App\Http\Requests\UpdateCoachTraineesRequest;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoachTraineesController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fields = Validator::make($request->only('coach_id','trainee_id') , [
            'coach_id' => 'required|integer',
            'trainee_id' => 'required|integer'
        ]);
        if($fields->fails())
        {
            return $this->fail($fields->errors()->first(),401);
        }
        $coachtrainee = CoachTrainees::create($fields);
        return $this->success();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCoachTraineesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCoachTraineesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CoachTrainees  $coachTrainees
     * @return \Illuminate\Http\Response
     */
    public function show(CoachTrainees $coachTrainees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CoachTrainees  $coachTrainees
     * @return \Illuminate\Http\Response
     */
    public function edit(CoachTrainees $coachTrainees)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCoachTraineesRequest  $request
     * @param  \App\Models\CoachTrainees  $coachTrainees
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCoachTraineesRequest $request, CoachTrainees $coachTrainees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CoachTrainees  $coachTrainees
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoachTrainees $coachTrainees)
    {
        
    }
}
