<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

     protected $fillable = [
        'url',
        'type',
        'user_id'
    ];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
