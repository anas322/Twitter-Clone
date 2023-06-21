<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
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
            'url' => $this->getMediaUrl($this->url),
            'type' => $this->type,
        ];
    }

    private function getMediaUrl($url)
    {
         $imageUrl =  Storage::url('public/tweets/' . $url);
        return URL::to('/') . $imageUrl;
    }
}
