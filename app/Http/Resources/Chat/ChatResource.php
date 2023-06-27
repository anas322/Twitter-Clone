<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'recipient' => new UserResource($this->recipient),
            'session_id' => $this->session_id,
            'created_at' => $this->created_at->format('M d, Y, g:i A'),
            'last_message' => $this->getLastMessage()->message,
            'latest_message_created_at' => $this->getTimeDiffForHumans($this->getLastMessage()->created_at)
        ];
    }

    private function getLastMessage(){
        return $this->latestMessage($this->user_id,$this->recipient_id)->first();
    }

    private function getTimeDiffForHumans($time)
    { 
        // Calculate the difference in hours between the timestamp and the current time
        $diffInHours = $time->diffInHours();

        // Check if the timestamp is within the last 24 hours
        if ($diffInHours < 24) {
            // Format the time without the 'ago' word
            $formattedTime = $time->diffForHumans(['syntax' => Carbon::DIFF_ABSOLUTE]);
        } else {
            // Format the time as a specific date
            $formattedTime = $time->format('j M');
        }

        return $formattedTime;
    }
}
