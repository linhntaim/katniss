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

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(config('entrust.permission'), config('entrust.permission_role_table'), 'role_id', 'permission_id');
    }
}
