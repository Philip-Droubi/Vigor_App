<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class CheckLikeAble
{
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        if (Post::where('id', $request->id)->first()->type == 1)
            return $next($request);
        return $this->fail(__('messages.UnAccepted action'));
    }
}
