<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowersResource;
use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function follow(User $user)
    {
        if(auth()->user()->following->contains($user)) {
            return response()->json([
                'message' => 'You are already following this user'
            ], 422);
        }

        auth()->user()->following()->attach($user->id);
        return response()->json([
            'message' => 'followed successfully'
        ]);
    }

    public function unfollow(User $user)
    {
        if(!auth()->user()->following->contains($user)) {
            return response()->json([
                'message' => 'You are not following this user'
            ], 422);
        }
        
        auth()->user()->following()->detach($user->id);
        return response()->json([
            'message' => 'unfollowed successfully'
        ]);
    }

    public function getFollowers(User $user)
    {
        $followers = $user->followers()->get();
        return response()->json([
            'followers' => FollowersResource::collection($followers)
        ]);
    }

    public function getFollowing(User $user)
    {
        $following = $user->following()->get();
        return response()->json([
            'following' => FollowersResource::collection($following)
        ]);
    }

}
