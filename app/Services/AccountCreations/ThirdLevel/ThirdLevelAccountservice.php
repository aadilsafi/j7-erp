<?php

namespace App\Services\AccountCreations\ThirdLevel;

use App\Models\AccountHead;
use DB;

class ThirdLevelAccountservice implements ThirdLevelAccountinterface
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
                'code' => $inputs['second_level'].$inputs['account_code'],
                'name' => $inputs['name'],
                'account_type'=>$inputs['account_type'],
                'level' => 3,
            ];

            $thirdLevelAccount = $this->model()->create($data);

            return $thirdLevelAccount;
        });
    }
}