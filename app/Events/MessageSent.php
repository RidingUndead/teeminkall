<?php

namespace App\Events;


use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $group;
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('group.'.$this->message->group);
        broadcast(new MessageSent($message))->toOthers();
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'username' => $this->message->user,
            ],
            'text' => $this->message->text,
            'group' => $this->message->group,
        ];
    }
}
