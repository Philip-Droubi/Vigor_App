<?php

namespace App\Http\Controllers;

use App\Models\DietMeal;
use App\Http\Requests\StoreDietMealRequest;
use App\Http\Requests\UpdateDietMealRequest;

class DietMealController extends Controller
{
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
    public function create($data)
    {
        DietMeal::create($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDietMealRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDietMealRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DietMeal  $dietMeal
     * @return \Illuminate\Http\Response
     */
    public function show(DietMeal $dietMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DietMeal  $dietMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(DietMeal $dietMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDietMealRequest  $request
     * @param  \App\Models\DietMeal  $dietMeal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDietMealRequest $request, DietMeal $dietMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DietMeal  $dietMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy(DietMeal $dietMeal)
    {
        //
    }
}
