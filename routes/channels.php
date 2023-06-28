<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return true;
    return (int) $user->id === (int) $id;
});


Broadcast::channel('message.{id}.{recipient_id}', function ($user, $_ ,$recipient_id) {
    return true;
    if( Chat::between($user->id,$recipient_id)->exists()) {
        return true;
    }

});