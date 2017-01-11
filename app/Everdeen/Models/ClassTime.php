<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class ClassTime extends Model
{
    const TYPE_NORMAL = 0;
    const TYPE_EXTRA = 1;

    protected $table = 'class_times';

    protected $fillable = [
        'classroom_id', 'subject', 'content', 'hours', 'start_at'
    ];

    public function getFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('shortDate', ' ', 'shortTime', $this->attributes['start_at']);
    }

    public function getFullFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('longDate', ' ', 'shortTime', $this->attributes['start_at']);
    }

    public function getFullFormattedStartAtDateAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->longDate($this->attributes['start_at']);
    }

    public function getInverseFullFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('shortTime', ' ', 'longDate', $this->attributes['start_at']);
    }

    public function getDurationAttribute()
    {
        return toFormattedNumber($this->attributes['hours']);
    }

    public function getHtmlContentAttribute()
    {
        if (empty($this->attributes['content'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', preg_split('/\r*\n/', htmlspecialchars($this->attributes['content']))) . '</p>';
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }
}
