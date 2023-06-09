<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\TweetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TweetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tweets' => TweetResource::collection($this->collection),
            'link' => [
                'self' => 'link-value', //for pagination
            ],
        ];
    }
}
