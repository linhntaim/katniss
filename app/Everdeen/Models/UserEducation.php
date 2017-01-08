<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserEducation extends Model
{
    protected $table = 'user_educations';

    protected $fillable = [
        'user_id', 'school', 'field', 'description',
        'start_month', 'start_year',
        'end_month', 'end_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
