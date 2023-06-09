<?php

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Http\Resources\TweetResource;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\TweetsResource;
use App\Http\Resources\SingleTweetResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/tweets', function (Request $request) {
        $request->validate([
            'content' => 'required|string|max:280',
            'reply_to' => 'nullable|integer',
            'selectedImage' => 'nullable|array',
            'selectedImage.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,webm,mp3,wav|max:100000'
        ]);
    
        $tweet = auth()->user()->tweets()->create([
            'content' => $request->content,
            'reply_to' => $request->reply_to ?? null
            
        ]);
        
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
    
        
        return response()->json([
            'message' => 'success',
            'tweet' => new TweetResource($tweet)
        ]);
    });

    Route::get('/tweets', function () {
        $tweets = TweetsResource::collection(Tweet::with(['user', 'mediaFiles'])->latest()->get());
        
        return response()->json([
            'tweets' => $tweets,
        ]);
    });

    Route::get('/tweets/{tweet}', function (Tweet $tweet) {
        // $tweet = new TweetsResource(Tweet::with(['user', 'mediaFiles'])->find($id));
        $tweet = new SingleTweetResource($tweet->load(['user', 'mediaFiles']));

        return response()->json([
            'tweet' => $tweet,
        ]);
    });

    Route::get('/test', function () {
        $tweet = Tweet::find(14);
        
        return response()->json([
            'tweets' => $tweet->parent,
        ]);
    });

});
