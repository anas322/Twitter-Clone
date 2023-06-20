<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Profile\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile' => new ProfileResource($this->profile),
        ];
    }
}
