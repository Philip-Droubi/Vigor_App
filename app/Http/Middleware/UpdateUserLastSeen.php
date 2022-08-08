<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $user->last_seen = Carbon::now();
        $user->save();
        return $next($request);
    }
}
