<?php

namespace App\Http\Resources;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'content' => $this->content,
            'user' => new UserResource($this->user),
            'media' => MediaFileResource::collection($this->mediaFiles),
            // 'replies' => TweetResource::collection($this->replies),
            'replies_count' => $this->replies?->count(),
            // 'reply_to' => new TweetResource($this->parent),
            'isLikedByAuthUser' => $this->isLikedBy(auth()->user()),
            'likes_count' => number_format($this->likes?->count()),
            'isUserFollowTweetAuthor' => $this->isUserFollowTweetAuthor(auth()->user()),
            'created_at' => $this->getTimeDiffForHumans($this->created_at),

            
        ];


        // dd( $this->id , $this->parent->id);
        if ($this->parent instanceof Tweet && $this->id !== $this->parent->id) {
            // Check if the parent tweet exists and is not the same as the current tweet
            $data['reply_to'] = new TweetResource($this->parent);
        }

        return $data;
    }

     private function isUserFollowTweetAuthor($user)
    {
        if ($user) {
            return $this->user->followers->contains($user);
        }
        return false;
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
