<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array|null
    {  
        if(!$this->latestChat){
            return null;
        }

        $data = [
            'id' => $this->latestChat->id,
            'message' => $this->latestChat->message,
            'session_id' => $this->latestChat->session_id,
            'created_at' => $this->latestChat->created_at->format('M d, Y, g:i A'),
            'last_message' => $this->getLastMessage()->message,
            'latest_message_created_at' => $this->getTimeDiffForHumans($this->getLastMessage()->created_at)
        ];

        if($this->latestChat->user_id == $request->user()->id){
            $data['recipient'] = new UserResource($this->latestChat->recipient);
        }else{
            $data['recipient'] = new UserResource($this->latestChat->user);
        }

        return $data;
    }

    private function getLastMessage(){
        return $this->latestChat->latestMessage($this->latestChat->user_id,$this->latestChat->recipient_id)->first();
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
