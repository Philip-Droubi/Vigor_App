<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class CheckProvider
{
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->providers()->first()) {
            return $this->fail(__("messages.You canot do this change"));
        }
        return $next($request);
    }
}
