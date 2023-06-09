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
}
