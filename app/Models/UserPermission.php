<?php

namespace Katniss\Models;


use Zizaco\Entrust\EntrustPermission;

class UserPermission extends EntrustPermission
{
    protected $fillable = [
        'name', 'display_name', 'description',
    ];
}
