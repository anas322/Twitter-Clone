<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'reply_to'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Tweet::class, 'reply_to');
    }

    public function parent()
    {
        return $this->belongsTo(Tweet::class, 'reply_to');
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesCount()
    {
        return $this->likes->count();
    }
    
    public function isLikedBy(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }
}
