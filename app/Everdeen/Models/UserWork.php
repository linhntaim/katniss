<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserWork extends Model
{
    protected $table = 'user_works';

    protected $fillable = [
        'user_id', 'company', 'position', 'description',
        'start_month', 'start_year',
        'end_month', 'end_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
