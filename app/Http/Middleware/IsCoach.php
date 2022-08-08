<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Traits\GeneralTrait;

class IsCoach
{
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        if (Gate::allows('Coach-Protection')) {
            return $next($request);
        }
        return $this->fail(__("messages.Access denied"), 401);
    }
}
