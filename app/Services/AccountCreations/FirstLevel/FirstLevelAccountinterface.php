<?php

namespace App\Services\AccountCreations\FirstLevel;

interface FirstLevelAccountinterface
{
    public function model();
    public function store($site_id, $inputs);
}
