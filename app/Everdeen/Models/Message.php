<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = ['conversation_id', 'user_id', 'device_id', 'content'];

    public function getIsOwnerAttribute()
    {
        return (!empty($this->attributes['user_id']) && deviceRealId() == $this->attributes['user_id'])
            || (!empty($this->attributes['device_id']) && deviceRealId() == $this->attributes['device_id']);
    }

    public function getIsFromUserAttribute()
    {
        return !empty($this->attributes['user_id']);
    }

    public function getIsFromDeviceAttribute()
    {
        return !empty($this->attributes['device_id']);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}
