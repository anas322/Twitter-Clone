<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Tweet;
use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Resources\SingleTweetResource;
use App\Http\Resources\UserResource;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $notify = $request->user()->notifications()->latest()->get();
        
        $notifications = $notify->map(function($notification){
             $data =  [
                'id' => $notification->id,
                'typeOFtweet' => $notification->data['typeOFtweet'],
                'read_at' => $notification->read_at,
            ];

            if($notification->data['typeOFtweet'] == 'tweet' || $notification->data['typeOFtweet'] == 'retweet'){
                $tweeta = Tweet::find($notification->data['data']);

                if(!$tweeta) {
                    return;
                }
                $data['tweet'] = new SingleTweetResource($tweeta);

            }elseif($notification->data['typeOFtweet'] == 'like'){

                $likeRecord = Like::find($notification->data['data']);
                if(!$likeRecord) {
                    return;
                }
                $data['whoMadeLike'] = new UserResource($likeRecord->user);
                $data['tweet'] = new SingleTweetResource($likeRecord->tweet);           

            }elseif($notification->data['typeOFtweet'] == 'follow'){

                $followRecord = Follower::find($notification->data['data']);

                if(!$followRecord) {
                    return;
                }

                $data['WhoMadeFollow'] = new UserResource($followRecord->userThatMadeFollow);
            }
                
            return $data;
        });

        return response()->json([
            'notifications' =>  $notifications,
        ]);
    }


    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        
        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}


