<?php

namespace App\Events;

use App\Http\Resources\Chat\ChatResource;
use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class ChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Chat $chat,
    ) {
        //
    }


    public function broadcastWith(): array
    {
        return [
            'message' => new ChatResource($this->chat),
        ];
    }

    public function broadcastAs()
    {
        return 'new.chat';
    }

    private function sortbroadCastKeys($user_id, $recipient_id)
    {
        return $user_id < $recipient_id
            ? $user_id . '.' . $recipient_id
            : $recipient_id . '.' . $user_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $ids = $this->sortbroadCastKeys(auth()->id(), $this->chat->recipient_id);
        Log::info($ids);
        return [
          new PrivateChannel('message.' . $ids),
        ];
    }
}
