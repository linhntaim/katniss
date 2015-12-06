<?php

namespace Katniss\Models;

use Zizaco\Entrust\EntrustRole;

class UserRole extends EntrustRole
{
    protected $fillable = [
        'name', 'display_name', 'public', 'description',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
