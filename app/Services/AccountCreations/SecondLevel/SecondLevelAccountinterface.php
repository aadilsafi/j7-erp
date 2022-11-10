<?php

namespace App\Services\AccountCreations\SecondLevel;

interface SecondLevelAccountinterface
{
    public function model();
    public function store($site_id, $inputs);
}
