<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use App\Notifications\NotifyUser;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\FollowersResource;

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

        $follow = Follower::where('user_id',$user->id )->where('follower_id', auth()->id())->first();
        $user->notify(new NotifyUser($follow, 'follow'));

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

        $followRecord = Follower::where('user_id',$user->id )->where('follower_id', auth()->id())->first();
        $user->notifications()->where('data->typeOFtweet', 'follow')->where('data->data',$followRecord->id )->delete();
        
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
