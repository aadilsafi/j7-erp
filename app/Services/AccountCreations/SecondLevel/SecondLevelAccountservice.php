<?php

namespace App\Services\AccountCreations\SecondLevel;

use App\Models\AccountHead;
use DB;

class SecondLevelAccountservice implements SecondLevelAccountinterface
{
    public function model()
    {
        return new AccountHead();
    }
    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            $data = [
                'site_id' => decryptParams($site_id),
                'code' => $inputs['first_level'].$inputs['account_code'],
                'name' => $inputs['name'],
                'level' => 2,
            ];

            $secondLevelAccount = $this->model()->create($data);

            return $secondLevelAccount;
        });
    }
}
