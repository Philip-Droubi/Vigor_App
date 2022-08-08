<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class FollowController extends Controller
{
    use GeneralTrait;
    public function follow($id, Request $request)
    {
        $user = $request->user();
        if ($user->id == $id) {
            return $this->fail(__("messages.You cannot follow your self"));
        }
        $follow = User::find($id);
        if ($follow && !is_null($follow->email_verified_at) && $follow->deleted_at == Null) {
            if (Gate::allows('Follow-Protection', $follow)) {
                Follow::firstOrCreate([
                    "follower_id" => $user->id,
                    "following" => $id,
                ]);
                $data = ["followers" => $follow->followers()->count(), "followings" => $follow->follows()->count()];
                return $this->success('ok', $data);
            }
            return $this->fail(__("messages.You can only follow coaches or dietitians"));
        }
        return $this->fail(__("messages.User not found"));
    }

    public function unfollow($id, Request $request)
    {
        $follower = $request->user();
        if ($follower->id == $id) {
            return $this->fail(__("messages.You cannot unfollow your self"));
        }
        $user = User::find($id);
        if ($user && !is_null($user->email_verified_at) && $user->deleted_at == Null) {
            if (Gate::allows('Follow-Protection', $user)) {
                $follow = Follow::where(['follower_id' => $follower->id, 'following' => $user->id])->first();
                if ($follow) {
                    $follow->delete();
                    $data = ["followers" => $user->followers()->count(), "followings" => $user->follows()->count()];
                    return $this->success('ok', $data);
                }
            }
            return $this->fail(__("messages.You can only follow coaches or dietitians"));
        }
        return $this->fail(__("messages.User not found"));
    }
    //Who follow this user
    public function getFollowers($id)
    {
        $acc = User::find($id);
        $followers = Follow::query()
            ->where('following', $acc->id)
            ->whereNotIn('follower_id', User::query()->whereNotNull('deleted_at')->get('id'))
            ->paginate(20, 'follower_id');
        $data = [];
        foreach ($followers as $follower) {
            $user = User::find($follower->follower_id);
            $url = $user->prof_img_url;
            if (!(Str::substr($url, 0, 4) == 'http')) {
                $url = 'storage/images/users/' . $url;
            }
            $data[] = ["id" => $user->id, "name" => $user->f_name . ' ' . $user->l_name, "img" => $url];
        }
        return $this->success("ok", $data);
    }

    //Who this user following
    public function getFollowing($id)
    {
        $acc = User::find($id);
        $following = Follow::query()
            ->where('follower_id', $acc->id)
            ->whereNotIn('following', User::query()->whereNotNull('deleted_at')->get('id'))
            ->paginate(20, 'following');
        $data = [];
        foreach ($following as $follow) {
            $user = User::find($follow->following);
            $url = $user->prof_img_url;
            if (!(Str::substr($url, 0, 4) == 'http')) {
                $url = 'storage/images/users/' . $url;
            }

            $data[] = ["id" => $user->id, "name" => $user->f_name . ' ' . $user->l_name, "img" => $url];
        }
        return $this->success("ok", $data);
    }

    public function block($id, Request $request)
    {
        $user = $request->user();
        if ($user->id == $id) {
            return $this->fail(__("messages.You cannot Block your self"));
        }
        if (Gate::allows('Coach-Dietitian-Protection')) {
            $toBeBlocked = User::find($id);
            if ($toBeBlocked->role_id == 5) {
                return $this->fail(__("messages.You cannot Block super Admin"));
            }
            if ($toBeBlocked && $toBeBlocked->deleted_at == Null) {
                Block::firstOrCreate([
                    "user_id" => $user->id,
                    "blocked" => $id,
                ]);
                return $this->success();
            }
            return $this->fail(__("messages.User not found"));
        }
        return $this->fail(__("messages.You canot do this change"));
    }
    public function unblock($id, Request $request)
    {
        $user = $request->user();
        if ($user->id == $id) {
            return $this->fail(__("messages.You cannot unBlock your self"));
        }
        if (Gate::allows('Coach-Dietitian-Protection')) {
            $toBeBlocked = User::find($id);
            if ($toBeBlocked  && $toBeBlocked->deleted_at == Null) {
                Block::where([
                    "user_id" => $user->id,
                    "blocked" => $id,
                ])->delete();
                return $this->success();
            }
            return $this->fail(__("messages.User not found"));
        }
        return $this->fail(__("messages.You canot do this change"));
    }
    public function blocklist(Request $request)
    {
        // $user = $request->user();
        if (Gate::allows('Coach-Dietitian-Protection')) {
            $data = [];
            $blocks = Block::query()
                ->where('user_id', Auth::id())
                ->whereNotIn('blocked', User::query()->whereNotNull('deleted_at')->get('id'))
                ->paginate(20, 'blocked');
            foreach ($blocks as $block) {
                $user = User::find($block->blocked);
                $url = $user->prof_img_url;
                if (!(Str::substr($url, 0, 4) == 'http')) {
                    $url = 'storage/images/users/' . $url;
                }
                $data[] = ["id" => $user->id, "name" => $user->f_name . ' ' . $user->l_name, "img" => $url];
            }
            return $this->success('ok', $data);
        }
        return $this->fail(__("messages.Access denied"));
    }
}
