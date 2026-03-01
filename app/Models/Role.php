<?php

namespace App\Models;

use App\Helper\Helper;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'level'
    ];

    protected static function booted() {}

}
