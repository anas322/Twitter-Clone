<?php

use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\NotificationsController;

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
    //get search users
    Route::get('/user/{search}', [UserController::class , 'search']);

    
    //create tweet 
    Route::post('/user/tweets', [TweetController::class , 'store']);
    //unretweet
    Route::post('/tweets/{tweet}/unretweet', [TweetController::class , 'unretweet']);
    //get all tweets
    Route::get('/tweets', [TweetController::class , 'index']);
    //get single tweet
    Route::get('/tweets/{tweet}', [TweetController::class , 'getSingleTweet']);
    //delete tweet
    Route::delete('/tweets/{tweet}', [TweetController::class , 'destroy']);

    //get user tweets
    Route::get('/profile/tweets/{user:username}', [ProfileController::class , 'getUserTweets']);
    //get user profile 
    Route::get('/profile/{user:username}', [ProfileController::class , 'show']);
    //update user profile
    Route::post('/profile', [ProfileController::class , 'update']);
    //delete user banner
    Route::post('/profile/banner/delete', [ProfileController::class , 'deleteBanner']);

    //follow user
    Route::post('/profile/{user:username}/follow', [FollowerController::class , 'follow']);
    //unfollow user
    Route::post('/profile/{user:username}/unfollow', [FollowerController::class , 'unfollow']);
    //get followers
    Route::get('/profile/{user:username}/followers', [FollowerController::class , 'getFollowers']);
    //get following
    Route::get('/profile/{user:username}/following', [FollowerController::class , 'getFollowing']);

    //get user bookmarks
    Route::get('/bookmarks', [BookmarkController::class , 'getBookmarks']);    
    //bookmark tweet
    Route::post('/tweets/{tweet}/bookmark', [BookmarkController::class , 'bookmark']);
    //unbookmark tweet
    Route::post('/tweets/{tweet}/unbookmark', [BookmarkController::class , 'unbookmark']);
    

    //like tweet
    Route::post('/tweets/{tweet}/like', [LikeController::class , 'like']);
    //unlike tweet
    Route::post('/tweets/{tweet}/unlike', [LikeController::class , 'unlike']);

    //get sender and recipient messages 
    // NOTE: this user is the recipient
    Route::get('/messages/{user}', [ChatController::class , 'getMessages']);
    //get user and recipient chat session
    Route::get('/messages/{user}/session', [ChatController::class , 'getSession']);
    //get user connections chats 
    Route::get('/messages', [ChatController::class , 'getChats']);
    //send message
    Route::post('/messages/{user}', [ChatController::class , 'sendMessage']);

    //get all notifications
    Route::get('/notifications', [NotificationsController::class , 'index']);
    //mark all notifications as read
    Route::post('/notifications', [NotificationsController::class , 'markAllAsRead']);
    
});
