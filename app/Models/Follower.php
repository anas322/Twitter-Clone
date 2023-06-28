<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'follower_id',
    ];

    public function userThatMadeFollow()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function userThatGotFollowed()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
