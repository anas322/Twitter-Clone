<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tweet;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\TweetsResource;
use App\Http\Resources\Profile\ProfileResource;

class ProfileController extends Controller
{

    public function getUserTweets(User $user) {
        try {
            $tweets = TweetsResource::collection(Tweet::with(['user', 'mediaFiles'])->where('user_id', $user->id)->latest()->get());

            return response()->json([
                'tweets' => $tweets,
                'tweetsCount' => $this->getTweetsCount($tweets) . ' Tweets',
                'tweetsAndRepliesCount' => $this->getTweetsAndRepliesCount($tweets) . ' Tweets',
                'mediaCount' => $this->getMediaCount($tweets) . ' Photos & videos',
            ]);
          
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while getting the user tweets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getTweetsCount($tweets) {
        return number_format($tweets->filter(function ($tweet){
            return $tweet->reply_to == null;
        })->count());
    }

    private function getTweetsAndRepliesCount($tweets) {
        return number_format($tweets->count());
    }

    private function getMediaCount($tweets) {
        return number_format($tweets->filter(function ($tweet){
            return $tweet->mediaFiles->count() > 0;
        })->count());
    }

    /**
     *  Display the specified resource.
     */
    public function show(User $user)
    {   
        try {
            $profile = new ProfileResource($user->profile);

            return response()->json([
                'profile' => $profile,
            ]);
          
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while retrieving profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request)
    {   
        try {
            $profile = auth()->user()->profile;

            $profile->update([
                'bio' => $request->bio,
                'location' => $request->location,
            ]);

            //store profile media
            $this->storeProfileMedia($request, $profile);

            //update profile name
            $this->updateProfileName($request, $profile);

            return response()->json([
                'profile' => $profile,
            ]);
          
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while updating profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function updateProfileName($request, $profile) {
        if($request->has('name')) {
            $profile->user()->update([
                'name' => $request->name
            ]);
        }
    }

    private function storeProfileMedia($request, $profile) {
        if($request->hasFile('avatar')) {
            if($profile->avatar){
                unlink(storage_path('app/public/profile/avatar/' . $profile->avatar));
            }
            $request->avatar->store('public/profile/avatar');
            $profile->update([
                'avatar' => $request->avatar->hashName()
            ]);
        }

        if($request->hasFile('banner')) {
            if($profile->banner){
                unlink(storage_path('app/public/profile/banner/' . $profile->banner));
            }
            $request->banner->store('public/profile/banner');
            $profile->update([
                'banner' => $request->banner->hashName()
            ]);
        }

        //delete banner if user choose to delete it
        if($request->has('bannerNull') && $request->bannerNull == 'null'){
            $this->deleteBanner();
        }
    }

    private function deleteBanner() {
        try {
            $profile = auth()->user()->profile;

            if($profile->banner){
                unlink(storage_path('app/public/profile/banner/' . $profile->banner));
            }

            $profile->update([
                'banner' => null
            ]);

            return response()->json([
                'profile' => $profile,
            ]);
          
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while deleting banner.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
