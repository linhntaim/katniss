<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class ClassTime extends Model
{
    protected $table = 'class_times';

    protected $fillable = [
        'classroom_id', 'subject', 'content', 'hours', 'start_at'
    ];

    public function getStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->shortDate($this->attributes['start_at']);
    }

    public function getHtmlContentAttribute()
    {
        if (empty($this->attributes['content'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['content']))) . '</p>';
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }
}
