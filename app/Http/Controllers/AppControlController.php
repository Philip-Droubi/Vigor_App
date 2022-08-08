<?php

namespace App\Http\Controllers;

use App\Models\AppController;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Gate;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AppControlController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        try { //TODO:TRANSLATE_NAMES
            if (Gate::allows('SuperAdmin-Protection'))
                $features = AppController::orderBy('id')->get(['id', 'name', 'is_active']);
            foreach ($features as $feature) {
                $feature->name = __('messages.' . $feature->name);
            }
            return $this->success('ok', $features);
            return $this->fail(__('messages.Access denied'));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            if (Gate::allows('SuperAdmin-Protection')) {
                $id = (int)$request->header('id');
                if ($feature = AppController::find($id)) {
                    if ($feature->is_active == true) {
                        $feature->is_active = false;
                        $feature->save();
                        return $this->success();
                    }
                    $feature->is_active = true;
                    $feature->save();
                    return $this->success();
                }
                return $this->fail(__("messages.Not found"));
            }
            return $this->fail(__('messages.Access denied'));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
}
