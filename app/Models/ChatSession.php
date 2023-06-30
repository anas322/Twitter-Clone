<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatSession extends Model
{
    use HasFactory, HasUuids;


    protected $fillable = [
        'uuid',
        'first_user',
        'second_user',
    ];
    
    public function chats()
    {
        return $this->hasMany(Chat::class,'session_id');
    }

    public function latestChat()
    {
        return $this->hasOne(Chat::class,'session_id')->latest();
    }

 
    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }
    
    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function scopeBetween($query,$first_user,$second_user)
    {
        return $query->where('first_user',$first_user)->where('second_user',$second_user)
        ->orWhere('first_user',$second_user)->where('second_user',$first_user);
    }
    

}
