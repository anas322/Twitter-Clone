<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->post('/user/tweets', function (Request $request) {
    $request->validate([
        'content' => 'required|string|max:280',
        'reply_to' => 'nullable|integer',
        'selectedImage' => 'nullable|array',
        'selectedImage.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,webm,mp3,wav|max:100000'
    ]);

    $tweet = auth()->user()->tweets()->create([
        'content' => $request->content,
        'reply_to' => $request->reply_to
        
    ]);
    
    if($request->selectedImage && count($request->selectedImage) > 0) {
        foreach ($request->selectedImage as $selectedImage) {
            $selectedImage->store('public/tweets');
    
            $tweet->media()->create([
                'url' => $selectedImage->hashName(),
                'type' => explode('/', $selectedImage->getMimeType())[0],
                'user_id' => auth()->user()->id
            ]);
        }
    }

    
    return response()->json([
        'message' => 'success',
    ]);
});


Route::middleware(['auth:sanctum'])->post('/test', function (Request $request) {

    return response()->json([
        'message' => 'success message',
    ]);
});


