<?php

namespace App\Services\Roles;

use App\Models\Role;
use App\Services\Roles\RoleInterface;
use Exception;
use Illuminate\Support\Str;

class RoleService implements RoleInterface
{

    public function model()
    {
        return new Role();
    }

    public function getAllWithTree()
    {
        $roles = $this->model()->all();
        return getTreeData(collect($roles), $this->model());
    }
}
