<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Http\Resources\TweetsResource;

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
                'message' => 'An error occurred while creating the tweet.',
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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
