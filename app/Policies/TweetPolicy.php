<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TweetPolicy
{
    

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tweet $tweet): bool
    {
           return $user->id === $tweet->user_id;
    }
}
