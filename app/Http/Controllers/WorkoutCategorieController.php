<?php

namespace App\Http\Controllers;

use App\Models\WorkoutCategorie;
use App\Http\Requests\StoreWorkoutCategorieRequest;
use App\Http\Requests\UpdateWorkoutCategorieRequest;
use Database\Factories\WorkoutCategorieFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutCategorieController extends Controller
{
    function index()
    {
        return WorkoutCategorie::all(['id','name']);
    }

    public function show(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required|integer'
        ]);
        return WorkoutCategorie::find($fields['id']);
    }

    public function create(Request $request)
    {
        if($request->user()->role_id == 2){
            $fields = $request->validate([
                'name' => 'required|string'
            ]);
            $fields['user_id'] = $request->user()->id;
            $categorie = WorkoutCategorie::create($fields);
            return response($categorie);
        }
        return response('Not a coach!');
    }

    public function edit(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
                'id' => 'required|integer',
                'name' => 'string'
            ]);
            $categorie =  WorkoutCategorie::find($fields['id']);

            if($request->user()->id == $categorie->coach->id)
            {
                if($fields['name'] != null) $categorie['name'] = $fields['name'];
                $categorie->update();
                return response($categorie);
            }
        }
        return response('Not the coach');
    }

    public function destroy(Request $request)
    {
        if($request->user()->role_id == 2)
        {
            $fields = $request->validate([
                'id' => 'required|integer'
            ]);
            $categorie =  WorkoutCategorie::find($fields['id']);
            if($request->user()->id == $categorie->coach->id)
            {
                $categorie->delete();
                return response('Success');
            }
            return response('Not the coach');
        }
    }
}
