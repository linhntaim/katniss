<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id', 'user_id', 'content',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'conversation_id', 'id');
    }
}
