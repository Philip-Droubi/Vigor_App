<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class MessageController extends Controller
{
    use GeneralTrait;
    public function index(Request $request)
    {
        try {
            $id = (int)$request->header('id');
            $request->id = (int)$request->id;
            $validator = Validator::make($id, [
                'id' => ['required', 'exists:users,id']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            $chat = Chat::where(['user_id' => Auth::id(), 'to_user_id' => $id])->get('id');
            if (!$chat)
                $chat = Chat::create([
                    'user_id' => Auth::id(),
                    'to_user_id' => $id
                ]);
            //
            return $this->success(
                'ok',
                Message::query()
                    ->where(['Chat_id' => $chat->id])
                    ->orderByDesc('created_at')
                    ->get(['id', 'message', 'user_id'])
            );
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"));
        }
    }

    public function store(Request $request)
    {
        try {
            $request->chat_id = (int)$request->chat_id;
            $validator = Validator::make($request->only('chat_id', 'message'), [
                'message' => ['required', 'min:1', 'max:2000', 'string'],
                'chat_id' => ['required', 'exists:chats,id']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            Message::create([
                'message' => $request->message,
                'chat_id' => $request->chat_id,
                'user_id' => 1,
            ]);
            event(new messageEvent(1, $request->chat_id, $request->message));
            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"));
        }
    }

    public function destroy($id)
    {
        try {
            if ($message = Message::where('id', $id)->get()) {
                $message->message = 'Deleted';
                $message->save();
                return $this->success();
            }
            return $this->fail(__("messages.Not found"));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"));
        }
    }

    public function chatList()
    {
        try {
            $id = Auth::id();
            $chats = Chat::query()->where(function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->orWhere('to_user_id', $id);
            })
                ->paginate(20);
            $data = [];
            foreach ($chats as $chat) {
                if ($chat->user_id == Auth::id()) {
                    $user = User::where('id', $chat->to_user_id)->get();
                    $url = $user->prof_img_url;
                    if (!(Str::substr($url, 0, 4) == 'http')) {
                        $url = 'storage/images/users/' . $url;
                    }
                    $data[] = [
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->f_name . ' ' . $user->l_name,
                            'img' => $url,
                            'role_id' => $user->role_id,
                            'role' => Role::find($user->role_id)->name,
                        ],
                        'chat' => [
                            'id' => $chat->id,
                            'last_msg' => (string)Message::where('chat_id', $chat->id)->get('message')->last()->message,
                            'last_date' => (string)Carbon::parse(Message::where('chat_id', $chat->id)->get('created_at')->last()->created_at)->utcOffset(config('app.timeoffset'))->format('Y/m/d g:i A'),
                        ]
                    ];
                } elseif ($chat->to_user_id == Auth::id()) {
                }
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"));
        }
    }

    public function block(Request $request)
    {
        try {
            $id = (int)$request->header('id');
            $validator = Validator::make($id, [
                'id' => ['required', 'exists:users,id']
            ]);
            if ($validator->fails())
                return $this->fail($validator->errors()->first(), 400);
            if ($chat = Chat::where('id', $id)->get()) {
                if ($chat->blocked == 0)
                    $chat->blocked = 1;
                elseif ($chat->blocked == 1)
                    $chat->blocked = 1;
                $chat->save();
                return $this->success();
            }
            return $this->fail(__("messages.somthing went wrong"));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
            // return $this->fail(__("messages.somthing went wrong"));
        }
    }
}
