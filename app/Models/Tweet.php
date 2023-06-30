<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'reply_to',
        'retweet_of'
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

    public function retweets()
    {
        return $this->hasMany(Tweet::class, 'retweet_of');
    }

    public function retweetOf()
    {
        return $this->belongsTo(Tweet::class, 'retweet_of');
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
    public function isRetweetedBy(User $user)
    {
        return $this->retweets->contains('user_id', $user->id);
    }

    public function retweetsWithQuotes()
    {
        return $this->hasMany(Tweet::class, 'retweet_of')->whereNotNull('content');
    }

    public function retweetsWithoutQuotes()
    {
        return $this->hasMany(Tweet::class, 'retweet_of')->whereNull('content');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'tweet_id', 'user_id')->withTimestamps();
    }
    
    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks->contains('id', $user->id);
    }
}
