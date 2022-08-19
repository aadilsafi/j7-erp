<?php

namespace App\Services;

use App\Services\Interfaces\RoleTypesInterface;
use Exception;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleTypesService implements RoleTypesInterface
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
