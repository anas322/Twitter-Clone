<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Notifications\NotifyUser;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function like(Tweet $tweet)
    { 
        if($tweet->isLikedBy(auth()->user())){
            return response()->json([
                'message' => 'Tweet already liked',
            ]);
        }

        $like =  $tweet->likes()->create([
            'user_id' => auth()->id(),
        ]);     

        if($like->user->id != $tweet->user->id ){
            $tweet->user->notify(new NotifyUser($like, 'like'));
        }

        return response()->json([
            'message' => 'Tweet liked',
        ]);
    }

    public function unlike(Tweet $tweet)
    {
        if(!$tweet->isLikedBy(auth()->user())){
            return response()->json([
                'message' => 'Tweet not liked',
            ]);
        }
        
        $likeRecord = $tweet->likes()->where([
            'user_id' => auth()->id(),
        ])->first();
        $tweet->user->notifications()->where('data->typeOFtweet', 'like')->where('data->data',$likeRecord->id )->delete();

        $tweet->likes()->where([
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json([
            'message' => 'Tweet unliked',
        ]);
    }
}
