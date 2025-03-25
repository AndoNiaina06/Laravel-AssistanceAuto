<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user;
    public string $message;
    public int $recipientId;

    public function __construct(User $user, string $message, $recipientId)
    {
        //
        $this->user = $user;
        $this->message = $message;
        $this->recipientId = $recipientId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.' . $this->recipientId);
//        return [
//            new channel('user.' . $this->recipientId),
//        ];
        //return ['public'];
        //return [new Channel('public')];
        //return ['chat'];
//        return [
//            new PrivateChannel('channel-name'),
//        ];
    }

//    public function broadcastWith(): array{
//        return [
//            'user' => $this->user,
//            'message' => $this->message,
//            'recipientId' => $this->recipientId,
//        ];
//    }
}
