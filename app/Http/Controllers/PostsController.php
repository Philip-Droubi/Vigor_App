<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Follow;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\PostLike;
use App\Models\PostReport;
use App\Models\PostVote;
use App\Models\Role;
use App\Models\SavedPost;
use App\Models\User;
use App\Models\UserVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Exception;

use function PHPUnit\Framework\isEmpty;

class PostsController extends Controller
{
    use GeneralTrait;
    public function index(Request $request)
    {
        try {
            $blocks = Block::where('blocked', Auth::id())->get('user_id'); //users who blocked me
            $coaches_ids = Follow::query()
                ->where('follower_id', Auth::id())
                ->whereNotIn('following', $blocks)
                ->whereNotIn('following', User::query()->whereNotNull('deleted_at')->get('id'))
                ->get('following'); //users I following without who blocked me
            $posts = [];
            if ($request->user()->role_id == 2 || $request->user()->role_id == 3 || $request->user()->role_id == 5) {
                $posts = Post::query() //posts if I am a coach
                    ->orWhere('user_id', Auth::id())
                    ->whereIn('user_id', $coaches_ids)
                    ->orwhere('is_accepted', true)
                    ->orderByDesc('created_at')
                    ->paginate(10, ['id', 'user_id', 'text', 'type', 'created_at']);
            } elseif (!($request->user()->role_id == 2 || $request->user()->role_id == 3 || $request->user()->role_id == 5)) {
                $posts = Post::query() //posts if I am not a coach
                    ->whereIn('user_id', $coaches_ids)
                    ->whereNot('type', 2)
                    ->where('is_accepted', true)
                    ->orderByDesc('created_at')
                    ->paginate(10, ['id', 'user_id', 'text', 'type', 'created_at']);
            }
            // if there is too few posts
            if ($posts->count() == 0) {
                if ($request->user()->role_id == 2 || $request->user()->role_id == 3 || $request->user()->role_id == 5) {
                    $moreposts = Post::query()
                        ->orWhere('user_id', Auth::id())
                        ->whereIn('user_id', $coaches_ids)
                        ->orwhere('is_accepted', true)
                        ->inRandomOrder()
                        ->limit(2)->get(['id', 'user_id', 'text', 'type', 'created_at']);
                    $moreposts2 = Post::query()
                        ->whereNotIn('user_id', $blocks)
                        ->whereNotIn('user_id', User::query()->whereNotNull('deleted_at')->get('id'))
                        ->where('is_accepted', true)
                        ->inRandomOrder()
                        ->limit(4)->get(['id', 'user_id', 'text', 'type', 'created_at']);
                } elseif (!($request->user()->role_id == 2 || $request->user()->role_id == 3 || $request->user()->role_id == 5)) {
                    $moreposts = Post::query()
                        ->whereIn('user_id', $coaches_ids)
                        ->whereNot('type', 2)
                        ->where('is_accepted', true)
                        ->inRandomOrder()
                        ->limit(2)->get(['id', 'user_id', 'text', 'type', 'created_at']);
                    $moreposts2 = Post::query()
                        ->whereNotIn('user_id', $blocks)
                        ->whereNotIn('user_id', User::query()->whereNotNull('deleted_at')->get('id'))
                        ->whereNot('type', 2)
                        ->where('is_accepted', true)
                        ->inRandomOrder()
                        ->limit(4)->get(['id', 'user_id', 'text', 'type', 'created_at']);
                }
                $moreposts3 = ($moreposts->merge($moreposts2));
                $posts = $posts->merge($moreposts3);
            }
            return $this->success('ok', $this->postData($posts));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function postData($posts)
    {
        $data = [];
        foreach ($posts as $post) {
            $user = $post->user()->first(['id', 'f_name', 'l_name', 'role_id', 'prof_img_url']);
            $url = $user->prof_img_url;
            if (!(Str::substr($url, 0, 4) == 'http')) {
                $url = 'storage/images/users/' . $url;
            }

            $my_like = -1;
            if ($like = PostLike::where(['post_id' => $post->id, 'user_id' => Auth::id()])->first('type')) {
                $my_like = $like->type;
            }

            $my_vote = -1;
            if ($vote = UserVote::where('user_id', Auth::id())
                ->whereIn('vote_id', PostVote::where('post_id', $post->id)->get('id'))
                ->first('vote_id')
            ) {
                $my_vote = $vote->vote_id;
            }

            $is_saved = false;
            if (SavedPost::where(['post_id' => $post->id, 'user_id' => Auth::id()])->first())
                $is_saved = true;
            $on_hold = false;
            if (Post::where('id', $post->id)->first('is_accepted')->is_accepted == 0)
                $on_hold = true;
            $role = '(💪)';
            if ($user->role_id == 3) {
                $role = '(☘️)';
            } elseif ($user->role_id == 5)
                $role = '(👑)';
            $data[] = [
                'post_main_data' => [
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'text' => $post->text,
                    'type' => $post->type,
                    'created_at' => (string)Carbon::parse($post->created_at)->utcOffset(config('app.timeoffset'))->format('Y/m/d g:i A'),
                    'comments' => $post->comments()->count(),
                    'my_like' => $my_like,
                    'my_vote' => $my_vote,
                    'on_hold' => $on_hold,
                    'is_saved' => $is_saved
                ],
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->f_name . ' ' . $user->l_name,
                    'img' => $url,
                    'role' => $role
                    // 'role' => Role::where('id', $user->role_id)->first()->name
                ],
                'post_likes' => [
                    "type1" => PostLike::where(['post_id' => $post->id, 'type' => 1])->count(),
                    "type2" => PostLike::where(['post_id' => $post->id, 'type' => 2])->count(),
                    "type3" => PostLike::where(['post_id' => $post->id, 'type' => 3])->count(),
                    "type4" => PostLike::where(['post_id' => $post->id, 'type' => 4])->count(),
                    "type5" => PostLike::where(['post_id' => $post->id, 'type' => 5])->count(),
                ],
                'media' => $this->getPostMedia($post),
                'votes' => $this->getVotes($post->id)
            ];
        }
        return $data;
    }

    public function getPostMedia($post)
    {
        $data = [
            'imgs' => [],
            'vids' => []
        ];
        $media = PostMedia::where('post_id', $post->id)->get(['id', 'url']);
        foreach ($media as $med) {
            if (substr($med->url, -4) == 'jpeg' || in_array(substr($med->url, -3), ['jpg', 'png', 'gif', 'svg', 'bmp'])) {
                $data['imgs'][] = [
                    'id' => $med->id,
                    'url' => 'storage/images/users/' . $med->url,
                ];
            } elseif (substr($med->url, -4) == 'mpeg' || in_array(substr($med->url, -3), ['mp4', 'avi', 'ogv', '3gp', 'm4v', 'wmv'])) {
                $data['vids'][] = [
                    'id' => $med->id,
                    'url' => 'storage/images/users/' . $med->url
                ];
            }
        }
        return $data;
    }

    public function storeNormal(Request $request)
    {
        try {
            $validator = Validator::make($request->only('text', 'media'), [
                'text' => ['string', 'max:10000', 'nullable'],
                'media' => ['array', 'nullable', 'max:99']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            if (!(is_null($request->text)) || !(is_null($request->media))) {
                $is_accepted = false;
                $is_reviewed = false;
                if ($request->user()->posts()->where(['is_accepted' => true, 'type' => 1])->count() >= 5 || $request->user()->role_id == 5) {
                    $is_accepted = true;
                    $is_reviewed = true;
                }
                $post = Post::create([
                    'user_id' => Auth::id(),
                    'text' => $request->text,
                    'is_accepted' => $is_accepted,
                    'is_reviewed' => $is_reviewed
                ]);
                if ($request->media) {
                    foreach ($request->media as $med) {
                        //
                        if (is_file($med)) {
                            $mimes = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'bmp', 'mp4', 'avi', 'mpeg', 'ogv', '3gp', 'm4v', 'wmv'];
                            if (in_array($med->getClientOriginalExtension(), $mimes) && $med->getsize() <= 41943040) {
                                $destination_path = 'public/images/users/';
                                $media = $med;
                                $randomString = Str::random(30);
                                $media_name =  Auth::id() . '/posts/' . $post->id . '/' . $randomString . $media->getClientOriginalName();
                                $path = $media->storeAs($destination_path, $media_name);
                                PostMedia::create([
                                    'post_id' => $post->id,
                                    'url' => $media_name,
                                ]);
                            }
                        }
                    }
                }
            }
            return $this->success(__('messages.Post has been created successfully'));
        } catch (\Exception $e) {
            $post->delete();
            // return $this->fail(__("messages.somthing went wrong"), 500);
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function storepoll(Request $request)
    {
        try {
            $validator = Validator::make($request->only('text', 'type'), [
                'text' => ['string', 'required'],
                'type' => ['required', 'integer', 'between:2,3'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);

            if ($request->type == 2) {
                return $this->storetype2($request);
            }

            if ($request->type == 3) {
                return $this->storetype3($request);
            }
            return $this->fail(__("messages.somthing went wrong"), 500);
        } catch (\Exception $e) {
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    // tip post type
    public function storetype2(Request $request)
    {
        try {
            $validator = Validator::make($request->only('text'), [
                'text' => ['max:50'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);

            $post = Post::create([
                'user_id' => Auth::id(),
                'text' => $request->text,
                'type' => 2,
                'is_accepted' => true,
                'is_reviewed' => true
            ]);
            $post->votes()->createMany([
                [
                    'post_id' => $post->id,
                    'vote' => 'Agree'
                ],
                [
                    'post_id' => $post->id,
                    'vote' => 'Disgree'
                ],
            ]);
            return $this->success(__('messages.Post has been created successfully'));
        } catch (\Exception $e) {
            $post->delete();
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    // public poll post type
    public function storetype3(Request $request)
    {
        try {
            $request->votes = json_decode($request->votes);
            $validator = Validator::make($request->only('text', 'votes'), [
                'text' => ['max:100'],
                // 'votes' => ['array', 'required', 'between:2,10'],
                // 'votes.*' => ['string', 'between:1,36'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);

            $post = Post::create([
                'user_id' => Auth::id(),
                'text' => $request->text,
                'type' => 3,
                'is_accepted' => true,
                'is_reviewed' => true
            ]);
            foreach ($request->votes as $vote) {
                PostVote::create([
                    'post_id' => $post->id,
                    'vote' => $vote
                ]);
            }
            return $this->success(__('messages.Post has been created successfully'));
        } catch (\Exception $e) {
            $post->delete();
            return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function showMyPosts(Request $request)
    {
        try {
            return $this->success(
                'ok',
                $this->postData(Post::where('user_id', Auth::id())
                    ->paginate(10, ['id', 'user_id', 'text', 'type', 'created_at']))
            );
        } catch (\Exception $e) {
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    //user_id
    public function showOthersPosts($user_id, Request $request)
    {
        try {
            if (User::find($user_id)->deleted_at != Null)
                return $this->fail(__("messages.Not found"));
            return $this->success(
                'ok',
                $this->postData(Post::where(['user_id' => $user_id, 'is_accepted' => true])
                    ->paginate(10, ['id', 'user_id', 'text', 'type', 'created_at']))
            );
        } catch (\Exception $e) {
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    //updates //three type
    public function updateNormal(Request $request, $id)
    {
        try {
            // $request->deleteMedia = json_decode($request->deleteMedia);
            $validator = Validator::make($request->only('text', 'deleteMedia', 'addMedia'), [
                'text' => ['string', 'nullable'],
                'addMedia' => ['array', 'nullable', 'max:99'],
                // 'deleteMedia' => ['array', 'nullable'],
                // 'deleteMedia.*' => ['nullable', 'integer', 'exists:posts_media,id'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            if ($post = Post::where(['id' => $id, 'user_id' => Auth::id(), 'type' => 1])->first()) {
                if ((is_null($request->text) && $post->media()->count() == 0 && isEmpty($request->addMedia)))
                    return $this->success(__('messages.No changes occurred'));
                //addMedia
                if ($request->addMedia) {
                    foreach ($request->addMedia as $am) {
                        if (is_file($am)) {
                            $mimes = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'bmp', 'mp4', 'avi', 'mpeg', 'ogv', '3gp', 'm4v', 'wmv'];
                            if (in_array($am->getClientOriginalExtension(), $mimes) && $am->getsize() <= 41943040) {
                                $destination_path = 'public/images/users/';
                                $media = $am;
                                $randomString = Str::random(30);
                                $media_name =  Auth::id() . '/posts/' . $post->id . '/' . $randomString . $media->getClientOriginalName();
                                $path = $media->storeAs($destination_path, $media_name);
                                PostMedia::create([
                                    'post_id' => $post->id,
                                    'url' => $media_name,
                                ]);
                            }
                        }
                    }
                }
                //deleteMedia
                if (count($dMedia = collect($request->deleteMedia)->filter()) > 0) {
                    if (
                        PostMedia::where('post_id', $post->id)
                        ->whereNotIn('id', $dMedia)
                        ->count() == 0
                        &&
                        is_null($request->text)
                    )
                        return $this->success(__('messages.No changes occurred'));
                    foreach ($dMedia as $dm) {
                        $med = PostMedia::where(['post_id' => $id, 'id' => $dm])->first();
                        if ($med) {
                            Storage::delete('public/images/users/' . $med->url);
                            $med->delete();
                        }
                    }
                }
                //editText
                if ($request->text != $post->text)
                    $post->update([
                        'text' => (string)$request->text
                    ]);
                return $this->success(__('messages.Updated successfully'));
            }
            return $this->fail(__('messages.Not found'), 400);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function updatePoll(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->only('text', 'type'), [
                'text' => ['string', 'nullable'],
                'type' => ['required', 'integer', 'between:2,3'],
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);

            if ($request->type == 2) {
                return $this->updatePolltype2($request, $id);
            }

            if ($request->type == 3) {
                return $this->updatePolltype3($request, $id);
            }
            return $this->fail(__("messages.somthing went wrong"), 500);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"), 500);
        }
    }

    public function updatePolltype2(Request $request, $id)
    {
        if ($post = Post::where(['id' => $id, 'user_id' => Auth::id(), 'type' => 2])->first()) {
            if (!(is_null($request->text) || $request->text == $post->text)) {
                $post->update([
                    'text' => $request->text
                ]);
                return $this->success(__('messages.Updated successfully'));
            }
            return $this->success(__('messages.No changes occurred'));
        }
        return $this->fail(__('messages.Not found'));
    }

    public function updatePolltype3(Request $request, $id)
    {
        $request->deleteVote = json_decode($request->deleteVote);
        $request->addVote = json_decode($request->addVote);
        $validator = Validator::make($request->only('deleteVote', 'addVote'), [
            // 'deleteVote' => ['array', 'nullable', 'exists:posts_votes,id'],
            // 'deleteVote.*' => ['nullable', 'integer', 'exists:posts_votes,id'],
            // 'addVote' => ['array',  'nullable'],
            // 'addVote.*' => ['string',  'nullable'],
        ]);
        if ($validator->fails())
            return $this->fail($validator->errors()->first(), 400);
        if ($post = Post::where(['id' => $id, 'user_id' => Auth::id(), 'type' => 3])->first()) {
            if (!(is_null($request->text))) {
                //add votes
                if ($request->addVote)
                    foreach ($request->addVote as $av) {
                        PostVote::create([
                            'post_id' => $post->id,
                            'vote' => $av
                        ]);
                    }
                //delete votes
                if (count($dVotes = collect($request->deleteVote)->filter()) > 0) {
                    if (
                        PostVote::where('post_id', $post->id)
                        ->whereNotIn('id', $dVotes)
                        ->count() == 0
                    )
                        return $this->success(__('messages.No changes occurred'));
                    foreach ($dVotes as $dv) {
                        PostVote::where(['post_id' => $id, 'id' => $dv])->delete();
                    }
                }
                //edit text
                if ($request->text != $post->text)
                    $post->update([
                        'text' => $request->text
                    ]);
                return $this->success(__('messages.Updated successfully'));
            }
            return $this->success(__('messages.No changes occurred'));
        }
        return $this->fail(__('messages.Not found'));
    }
    //End updates

    public function destroy($id)
    {
        try {
            if ($post = Post::find($id)) {
                if (Post::where(['id' => $id, 'user_id' => Auth::id()])->first()) {
                    $post->delete();
                    Storage::deleteDirectory('public/images/users/' . Auth::id() . '/posts/' . $id);
                    return $this->success(__('messages.deleted'));
                }
                return $this->fail(__("messages.Access denied"), 401);
            }
            return $this->fail(__("messages.Not found"), 404);
        } catch (\Exception $e) {
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    public function vote($id, $vote_id)
    {
        try {
            if (Post::where('id', $id)->first()->user()->first()->deleted_at != Null)
                return $this->fail(__("messages.Not found"));
            if (!(($post = Post::find($id)) && ($postVote = PostVote::where(['psot_id' => $id, 'id' => $vote_id])))) {
                return $this->fail(__("messages.Not found"), 404);
            }
            if (!is_null($vote = UserVote::where(['user_id' => Auth::id(), 'vote_id' => $vote_id])->first())) {
                $vote->delete();
                return $this->success('ok', $this->getVotes($id));
            }
            $vote = UserVote::updateOrCreate([
                'user_id' => Auth::id(),
            ], [
                'vote_id' => $vote_id,
            ]);
            return $this->success('ok', $this->getVotes($id));
        } catch (\Exception $e) {
            $vote->delete();
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    public function getVotes($id)
    {
        try {
            if (Post::where('id', $id)->first()->user()->first()->deleted_at != Null)
                return $this->fail(__("messages.Not found"));
            $votes = Post::find($id)->votes()->get(['id', 'vote']);
            $allVotesCount = 0;
            $data = [];
            foreach ($votes as $vote) {
                $allVotesCount += $vote->votes()->count();
            }
            foreach ($votes as $vote) {
                if ($allVotesCount == 0) {
                    $data[] = [
                        'vote_id' => $vote->id,
                        'vote' => $vote->vote,
                        'rate' => (string)0
                    ];
                } else {
                    $thisVoteCount = $vote->votes()->count(); //How many users vote for this option
                    $data[] = [
                        'vote_id' => $vote->id,
                        'vote' => $vote->vote,
                        'rate' => (string)((int)(100 * ($thisVoteCount / $allVotesCount)))
                    ];
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    public function report($id)
    {
        try {
            if (Post::where('id', $id)->first()->user()->first()->deleted_at != Null)
                return $this->fail(__("messages.Not found"));
            if ($post = Post::where('id', $id)->first()) {
                if (PostReport::query()->where(['post_id' => $post->id, 'user_id' => Auth::id()])->count() < 2)
                    PostReport::create([
                        'user_id' => Auth::id(),
                        'post_id' => $post->id,
                    ]);
                return $this->success();
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    public function savePost($id)
    {
        try {
            if (Post::where('id', $id)->first()->user()->first()->deleted_at != Null)
                return $this->fail(__("messages.Not found"));
            if ($save = SavedPost::where(['user_id' => Auth::id(), 'post_id' => $id])->first()) {
                $save->delete();
                return $this->success(__('messages.Removed from your saved-posts list'));
            }
            SavedPost::create([
                'user_id' => Auth::id(),
                'post_id' => $id
            ]);
            return $this->success(__('messages.Added to your saved-posts list'));
        } catch (\Exception $e) {
            return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }

    public function savePostList(Request $request)
    {
        try {
            $posts = Post::whereIn('id', $request->user()->savedPosts()->get('post_id'))
                ->where('is_accepted', true)
                ->whereNotIn('user_id', User::query()->whereNotNull('deleted_at')->get('id'))
                ->paginate(10, ['id', 'user_id', 'text', 'type', 'created_at']);
            return $this->success('ok', $this->postData($posts));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__('messages.somthing went wrong'), 500);
        }
    }
}
