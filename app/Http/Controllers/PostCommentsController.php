<?php

namespace App\Http\Controllers;

use App\Models\PostCommentReport;
use App\Models\PostComments;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostCommentsController extends Controller
{
    use GeneralTrait;
    //post $id
    public function index($id)
    {
        try {
            $comments = PostComments::query()
                ->where('post_id', $id)
                ->whereNotIn('user_id', User::query()->whereNotNull('deleted_at')->get('id'))
                ->orderByDesc('created_at')
                ->paginate(25, ['id', 'user_id', 'text', 'created_at']);
            $data = [];
            foreach ($comments as $comment) {
                $user = User::find($comment->user_id);
                $url = $user->prof_img_url;
                if (!(Str::substr($url, 0, 4) == 'http')) {
                    $url = 'storage/images/users/' . $url;
                }
                $data[] = [
                    "user_id" => $user->id,
                    "name" => $user->f_name . ' ' . $user->l_name,
                    "img" => $url,
                    "comment_id" => $comment->id,
                    "comment" => $comment->text,
                    'created_at' => (string)Carbon::parse($comment->created_at)->utcOffset(config('app.timeoffset'))->format('Y/m/d g:i A')
                ];
            }
            return $this->success('ok', $data);
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    //post $id
    public function store($id, Request $request)
    {
        try {
            $validator = Validator::make($request->only('text'), [
                'text' => ['string', 'max:600', 'nullable'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $data = [];
            if ($request->text) {
                $comment = PostComments::create([
                    'post_id' => $id,
                    'user_id' => $request->user()->id,
                    'text' => $request->text
                ]);
                $user = $request->user();
                $url = $user->prof_img_url;
                if (!(Str::substr($url, 0, 4) == 'http')) {
                    $url = 'storage/images/users/' . $url;
                }
                $data[] = [
                    "user_id" => Auth::id(),
                    "name" => $user->f_name . ' ' . $user->l_name,
                    "img" => $url,
                    "comment_id" => $comment->id,
                    "comment" => $comment->text,
                    'created_at' => (string)Carbon::parse($comment->created_at)->utcOffset(config('app.timeoffset'))->format('Y/m/d g:i A'),
                    'total' => Post::find($id)->comments()->count()
                ];
                return $this->success(__("messages.Comment added"), $data);
            }
            return $this->fail(__("messages.Empty comments are not accepted"), 400);
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    // comment $id
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->only('text'), [
                'text' => ['string', 'max:600', 'nullable'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            if (PostComments::find($id)) {
                if ($request->text) {
                    PostComments::find($id)->update([
                        'text' => $request->text
                    ]);
                }
                return $this->success();
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    // comment $id
    public function destroy($id)
    {
        try {
            if ($comment = PostComments::where(['user_id' => Auth::id(), 'id' => $id])->first()) {
                if ($post = Post::where(['id' => $comment->post_id]) && is_null($comment->post()->first()->user()->first()->deleted_at)) {
                    $comment->delete();
                    return $this->success();
                }
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
    // comment $id
    public function report($id)
    {
        try {
            if ($comment = PostComments::where('id', $id)
                ->whereNotIn('user_id', User::query()->whereNotNull('deleted_at')->get('id'))
                ->first()
            ) {
                if (PostCommentReport::query()->where(['comment_id' => $comment->id, 'user_id' => Auth::id()])->count() < 2)
                    PostCommentReport::create([
                        'user_id' => Auth::id(),
                        'comment_id' => $comment->id,
                    ]);
                return $this->success();
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }
}
