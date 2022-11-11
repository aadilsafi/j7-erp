<?php

namespace App\Services\AccountCreations\FifthLevel;

interface FifthLevelAccountinterface
{
    public function model();
    public function store($site_id, $inputs);
}
