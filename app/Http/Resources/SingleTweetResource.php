<?php

namespace App\Http\Resources;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleTweetResource extends JsonResource
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
            'replies' => TweetResource::collection($this->replies),
            'replies_count' => $this->replies?->count(),
            // 'reply_to' => new TweetResource($this->parent),
            'created_at' => $this->getTimeDiffForHumans($this->created_at),
        ];

        if ( $this->id != $this->parent?->id) {
            // Check if the parent tweet exists and is not the same as the current tweet
            $data['reply_to'] = new TweetResource($this->parent);
        }

        return $data;
    }

    private function getTimeDiffForHumans($time)
    {
        $carbonTimestamp = Carbon::createFromTimestamp($time);

        // Calculate the difference in hours between the timestamp and the current time
        $diffInHours = $carbonTimestamp->diffInHours();

        // Check if the timestamp is within the last 24 hours
        if ($diffInHours < 24) {
            // Format the time without the 'ago' word
            $formattedTime = $carbonTimestamp->diffForHumans(['syntax' => Carbon::DIFF_ABSOLUTE]);
        } else {
            // Format the time as a specific date
            $formattedTime = $carbonTimestamp->format('j M');
        }

        return $formattedTime;
    }
}
