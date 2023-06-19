<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
  
    /**
     * Determine whether the user can view the model.
     */
    public function ownsProfile(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }


}
