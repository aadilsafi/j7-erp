<?php

namespace App\Services\AccountCreations\ThirdLevel;

interface ThirdLevelAccountinterface
{
    public function model();
    public function store($site_id, $inputs);
}
