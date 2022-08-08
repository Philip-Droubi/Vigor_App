<?php

namespace App\Http\Controllers;

use App\Models\Diet_System;
use App\Http\Requests\StoreDiet_SystemRequest;
use App\Http\Requests\UpdateDiet_SystemRequest;
use App\Models\Diet;

class DietSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDiet_SystemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiet_SystemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet_System  $diet_System
     * @return \Illuminate\Http\Response
     */
    public function show(Diet_System $diet_System)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet_System  $diet_System
     * @return \Illuminate\Http\Response
     */
    public function edit(Diet_System $diet_System)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDiet_SystemRequest  $request
     * @param  \App\Models\Diet_System  $diet_System
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiet_SystemRequest $request, Diet_System $diet_System)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet_System  $diet_System
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diet_System $diet_System)
    {
        //
    }
}
