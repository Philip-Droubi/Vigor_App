<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class CheckDeletedAccount
{
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->deleted_at === NULL)
            return $next($request);
        return $this->fail(__('messages.You need to recover this account first'), 400);
    }
}
