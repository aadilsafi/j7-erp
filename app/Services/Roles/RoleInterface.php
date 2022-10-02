<?php

namespace App\Services\Roles;

interface RoleInterface
{
    public function model();
    public function getAllWithTree();
}
