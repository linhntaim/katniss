<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserEducation extends Model
{
    protected $table = 'user_educations';

    protected $fillable = ['user_id', 'school', 'field', 'description', 'start', 'end', 'current'];

    public function getStartAttribute()
    {
        return empty($this->attributes['start']) || $this->attributes['start'] == '0000-00-00 00:00:00' ? '' : DateTimeHelper::getInstance()->shortDate($this->attributes['start']);
    }

    public function getEndAttribute()
    {
        return empty($this->attributes['end']) || $this->attributes['end'] == '0000-00-00 00:00:00' ? '' : DateTimeHelper::getInstance()->shortDate($this->attributes['end']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
