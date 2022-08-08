<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class messageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id, $message, $chat_id;
    public function __construct($user_id, $chat_id, $message)
    {
        $this->user_id = $user_id;
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user_id,
            'message' => $this->message,
            'chat_id' => $this->chat_id,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->user_id);
    }
}
