<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'message',
        'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function scopeBetween($query, $sender_id, $recipient_id)
    {
        return $query->where('user_id', $sender_id)->where('recipient_id', $recipient_id)
            ->orWhere('user_id', $recipient_id)->where('recipient_id', $sender_id);
    }

    public function scopeLatestMessage($query, $sender_id, $recipient_id)
    {
        return $query->where('user_id', $sender_id)->where('recipient_id', $recipient_id)
            ->orWhere('user_id', $recipient_id)->where('recipient_id', $sender_id)->latest();
    }

}
