<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('apikey') != env('API_KEY')) {
            return response()->json(["message" => "Unauthenticated"], 401);
        }
        return $next($request);
    }
}
