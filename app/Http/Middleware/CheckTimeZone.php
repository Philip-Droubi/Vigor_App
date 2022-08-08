<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTimeZone
{
    public function handle(Request $request, Closure $next)
    {
        config(['app.timeoffset' => 0]);  //default value
        if (($request->header("timeZone"))) {
            config(['app.timeoffset' => $request->header('timeZone')]); // if request have timeZone
        }
        return $next($request);
    }
}
