<?php

namespace App\Http\Middleware;

use App\Models\Block;
use App\Models\Post;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class Blocked
{
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        if ($user = User::find($request->user_id)) {
            if (Block::Where(['user_id' => $user->id, 'blocked' => Auth::id()])->first()) {
                return $this->fail(__('messages.Access denied'));
            }
            return $next($request);
        }
        if (Post::find($request->id)) {
            if (Block::Where(['user_id' => Post::find($request->id)->user->id, 'blocked' => Auth::id()])->first()) {
                return $this->fail(__('messages.Access denied'));
            }
            return $next($request);
        }
        return $this->fail(__("messages.Not found"));
    }
}
