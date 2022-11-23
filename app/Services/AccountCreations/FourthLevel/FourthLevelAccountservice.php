<?php

namespace App\Services\AccountCreations\FourthLevel;

use App\Models\AccountHead;
use DB;

class FourthLevelAccountservice implements FourthLevelACcountinterface
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
                'code' => $inputs['third_level'].$inputs['account_code'],
                'name' => $inputs['name'],
                'level' => 4,
            ];

            $fourthLevelAccount = $this->model()->create($data);

            return $fourthLevelAccount;
        });
    }
}
