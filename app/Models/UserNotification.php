<?php

namespace Katniss\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    const NORMAL = 0;
    const MESSAGE = 1;

    protected $table = 'user_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'url_index', 'url_params',
        'message_index', 'message_params', 'type', 'read',
    ];

    public function getUrlParamsAttribute()
    {
        if (empty($this->attributes['url_params'])) return [];
        $params = json_decode($this->attributes['url_params'], true);
        return $params === false ? [] : $params;
    }

    public function getMessageParamsAttribute()
    {
        if (empty($this->message_params)) return [];
        $params = json_decode($this->message_params, true);
        return $params === false ? [] : $params;
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getPushRawUrlAttribute()
    {
        return homeUrl($this->url_index, $this->urlParams, $this->user->settings->locale);
    }

    public function getPushUrlAttribute()
    {
        return homeUrl('notification/confirm/{id}', ['id' => $this->id], $this->user->settings->locale);
    }

    public function getPushMessageAttribute()
    {
        return trans('notification.' . $this->message_index, $this->messageParams, '', $this->user->settings->locale);
    }

    public function getRawUrlAttribute()
    {
        return homeUrl($this->url_index, $this->urlParams);
    }

    public function getUrlAttribute()
    {
        return homeUrl('notification/confirm/{id}', ['id' => $this->id]);
    }

    public function getMessageAttribute()
    {
        return trans('notification.' . $this->message_index, $this->messageParams);
    }

    public function getTimeAttribute()
    {
        return defaultTime($this->created_at);
    }

    public function getTimeTzAttribute()
    {
        return defaultTimeTZ($this->created_at);
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'url' => $this->url,
            'message' => $this->message,
            'read' => $this->read ? 'read' : 'unread',
            'time' => $this->time,
            'time_tz' => $this->timeTz,
            'secret' => $this->user->notification_channel
        ];
    }

    public function toPushArray()
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'url' => $this->pushUrl,
            'message' => $this->pushMessage,
            'read' => $this->read ? 'read' : 'unread',
            'time' => $this->time,
            'time_tz' => $this->timeTz,
            'secret' => $this->user->notification_channel
        ];
    }
}
