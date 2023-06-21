<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Tweet $tweet)
    { 
        if($tweet->isLikedBy(auth()->user())){
            return response()->json([
                'message' => 'Tweet already liked',
            ]);
        }

        $tweet->likes()->create([
            'user_id' => auth()->id(),
        ]);

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
        
        $tweet->likes()->where([
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json([
            'message' => 'Tweet unliked',
        ]);
    }
}
