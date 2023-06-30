<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Http\Resources\TweetsResource;

class BookmarkController extends Controller
{
    public function getBookmarks()
    {
        $bookmarks = TweetsResource::collection(auth()->user()->tweetsThroughBookmarks()->get());

        return response()->json([
            'bookmarksTweets' => $bookmarks
        ], 200);
    }

    public function bookmark(Tweet $tweet)
    {
        if($tweet->bookmarks()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json([
                'message' => 'Tweet already bookmarked'
            ], 200);
        }

        $tweet->bookmarks()->attach(auth()->user());

        return response()->json([
            'message' => 'Tweet bookmarked successfully'
        ], 200);
    }

    public function unbookmark(Tweet $tweet)
    {
        if(!$tweet->bookmarks()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json([
                'message' => 'Tweet not bookmarked'
            ], 200);
        }

        $tweet->bookmarks()->detach(auth()->user());

        return response()->json([
            'message' => 'Tweet unbookmarked successfully'
        ], 200);
    }
}
