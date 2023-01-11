<?php

namespace App\Services\AccountCreations\FirstLevel;

use App\Models\AccountHead;
use DB;

class FirstLevelAccountservice implements FirstLevelAccountinterface
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
                'code' => $inputs['account_code'],
                'name' => $inputs['name'],
                'account_type'=>$inputs['account_type'],
                'level' => 1,
            ];

            $firstLevelAccount = $this->model()->create($data);

            return $firstLevelAccount;
        });
    }
}