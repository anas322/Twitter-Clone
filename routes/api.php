<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
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


Route::middleware(['auth:sanctum'])->group( function () {

    //get authenticated user
    Route::get('/user', [UserController::class , 'index']);

    
    //create tweet 
    Route::post('/user/tweets', [TweetController::class , 'store']);
    //get all tweets
    Route::get('/tweets', [TweetController::class , 'index']);
    //get single tweet
    Route::get('/tweets/{tweet}', [TweetController::class , 'getSingleTweet']);

    //get user tweets
    Route::get('/profile/tweets/{user:username}', [ProfileController::class , 'getUserTweets']);
    

});
