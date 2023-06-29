<?php

namespace App\Notifications;

use App\Models\Like;
use App\Models\Tweet;
use App\Models\Follower;
use Illuminate\Bus\Queueable;
use App\Http\Resources\LikeResource;
use App\Http\Resources\UserResource;
use Illuminate\Notifications\Notification;
use App\Http\Resources\SingleTweetResource;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifyUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     * @object object<\App\Models\User, App\Model\Like, App\Model\Tweet, App\Model\Follower>
     */
    public function __construct(
        public object $object,
        public string $type
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    private function getData(){

        $data =  [
            'typeOFtweet' => $this->type,
            'read_at' => null,
        ];

        if($this->type == 'tweet' || $this->type == 'retweet'){

            $data['tweet'] = new SingleTweetResource($this->object);

        }elseif($this->type == 'like'){

            $data['whoMadeLike'] = new UserResource($this->object->user);
            $data['tweet'] = new SingleTweetResource($this->object->tweet);           

        }elseif($this->type == 'follow'){

            $data['WhoMadeFollow'] = new UserResource($this->object->userThatMadeFollow);
        }

        return $data;
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->getData());
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'typeOFtweet' => $this->type,
            'data' => $this->object->id,
        ];
    }
}
