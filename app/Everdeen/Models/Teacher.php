<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const REQUESTED = 0;
    const APPROVED = 1;
    const REJECTED = -1;

    protected $table = 'teachers';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id', 'approving_user_id', 'approving_at', 'status',
        'video_teaching_url', 'video_introduce_url',
        'about_me', 'experience', 'methodology',
        'available_times', 'payment_info',
    ];

    public function getHtmlAboutMeAttribute()
    {
        if (empty($this->attributes['about_me'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['about_me']))) . '</p>';
    }

    public function userProfile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approvingUser()
    {
        return $this->belongsTo(User::class, 'approving_user_id', 'id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topics_teachers', 'teacher_id', 'topic_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'user_id', 'teacher_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Teacher::APPROVED);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', Teacher::REQUESTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Teacher::REJECTED);
    }
}
