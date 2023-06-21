<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            'bio' => $this->bio ,
            'location' => $this->location,
            'avatar' => $this->avatar ? $this->getAvatarUrl($this->avatar) : null,
            'banner' => $this->banner ? $this->getBannerUrl($this->banner) : null,
            'joined' => $this->created_at->format('F Y'),   
            'ownsProfile' => $request->user()->can('ownsProfile', $this->resource),
            'isAuthUserFollowThisProfile' => $request->user()->following->contains($this->user),
            'isThisProfileFollowAuthUser' => $this->user->following->contains($request->user()),
            'followersCount' => number_format($this->user->followers()->count()),
            'followingCount' => number_format($this->user->following()->count()),

        ];
    }

    private function getAvatarUrl($avatar) {
       $imageUrl =  Storage::url('public/profile/avatar/' . $avatar);
        return URL::to('/') . $imageUrl;
    }
    private function getBannerUrl($banner) {
        $imageUrl = Storage::url('public/profile/banner/' . $banner);
        return URL::to('/') . $imageUrl;
    }
}
