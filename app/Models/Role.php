<?php

namespace App\Models;

use App\Traits\Searchable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Searchable;

    protected $fillable = [
        'name',
        'guard_name'
    ];

    protected $appends = ['role_permissions'];

    public function getRolePermissionsAttribute()
    {
        $data = $this->permissions->pluck('name');
        $this->unsetRelation('permissions');
        return $data;
    }
}
