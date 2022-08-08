<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLang
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale('en');  //default value
        if ((request()->header("lang")) && request()->header("lang") == 'ar') {
            app()->setLocale('ar'); // if request have ar
        }
        return $next($request);
    }
}
