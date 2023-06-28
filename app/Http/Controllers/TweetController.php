<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Notifications\NotifyUser;
use App\Http\Resources\TweetsResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\SingleTweetResource;
use Illuminate\Support\Facades\Log;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            $tweets = TweetsResource::collection(Tweet::with(['user', 'mediaFiles'])->whereNull('reply_to')->latest()->get());     

            return response()->json([
                'tweets' => $tweets,
            ]);

        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while retrieving the tweets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTweetRequest $request)
    {
        try {

            if($request->retweet_of && !$request->content){
                $findTweet = Tweet::where('retweet_of', $request->retweet_of)->where('user_id', auth()->id())->get();
                if($findTweet?->count() > 0){
                    return response()->json([
                        'message' => 'You already retweeted this tweet'
                    ], 422);
                }
            }

            // create tweet
            $tweet = auth()->user()->tweets()->create([
                'content' => $request->content,
                'reply_to' => $request->reply_to ?? null,
                'retweet_of' => $request->retweet_of ?? null,
            ]);

            // create media files if exist
            $this->createMediaFiles($request, $tweet);

            if($request->reply_to ){
                $userOfParentTweetId = Tweet::findOrFail($request->reply_to)->user->id;
                if(auth()->id() != $userOfParentTweetId){
                    $tweeta = Tweet::findOrFail($request->reply_to);
                    $tweeta->user->notify(new NotifyUser($tweet, 'tweet'));
                }
            }


            if($request->retweet_of ){
                $userOfParentTweetId = Tweet::findOrFail($request->retweet_of)->user->id;
                if(auth()->id() != $userOfParentTweetId){
                    $tweeta = Tweet::findOrFail($request->retweet_of);
                    $tweeta->user->notify(new NotifyUser($tweet, 'retweet'));
                }
            }

            return response()->json([
                'message' => 'success',
                'tweet' => new SingleTweetResource($tweet)
            ]);
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while creating the tweet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function createMediaFiles($request, $tweet)
    {
        if($request->selectedImage && count($request->selectedImage) > 0) {
            foreach ($request->selectedImage as $selectedImage) {
                $selectedImage->store('public/tweets');
        
                $tweet->mediaFiles()->create([
                    'url' => $selectedImage->hashName(),
                    'type' => explode('/', $selectedImage->getMimeType())[0],
                    'user_id' => auth()->user()->id
                ]);
            }
        }
    }

    public function unretweet(Tweet $tweet)
    {
        try {

            $tweet->user->notifications()->where('data->typeOFtweet', 'retweet')->where('data->data', $tweet->retweets()->where('user_id', auth()->user()->id)->first()->id)->delete();

            $tweet->retweets()->where('user_id', auth()->user()->id)->delete();

            return response()->json([
                'message' => 'success',
                'tweet' => new SingleTweetResource($tweet)
            ]);
        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while unretweeting the tweet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getSingleTweet(Tweet $tweet)
    {
        try {
            $tweet = new SingleTweetResource($tweet->load(['user', 'mediaFiles']));
    
            return response()->json([
                'tweet' => $tweet,
            ]);

        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while getting the tweet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);
        
        try {
            $tweet->retweets()->delete();
            $tweet->likes()->delete();
            $this->deleteMediaFiles($tweet);
            $tweet->delete();

            return response()->json([
                'message' => 'Tweet deleted successfully.',
            ]);

        } catch (\Exception $e) {
            // handle the exception and return an appropriate response
            return response()->json([
                'message' => 'An error occurred while deleting the tweet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function deleteMediaFiles($tweet)
    {
        if($tweet->mediaFiles && count($tweet->mediaFiles) > 0) {
            foreach ($tweet->mediaFiles as $mediaFile) {
                Storage::delete('public/tweets/' . $mediaFile->url);
            }
        }
    }
}
