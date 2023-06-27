<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Http\Resources\TweetsResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\SingleTweetResource;

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
            // create tweet
            $tweet = auth()->user()->tweets()->create([
                'content' => $request->content,
                'reply_to' => $request->reply_to ?? null,
                'retweet_of' => $request->retweet_of ?? null,
            ]);

            // create media files if exist
            $this->createMediaFiles($request, $tweet);

            return response()->json([
                'message' => 'success',
                // 'tweet' => new TweetResource($tweet)
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
