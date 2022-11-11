<?php

namespace App\Services\AccountCreations\FourthLevel;

interface FourthLevelACcountinterface
{
    public function model();
    public function store($site_id, $inputs);
}
