<?php

namespace App\Services\AccountCreations\FifthLevel;

use App\Models\AccountHead;
use DB;

class FifthLevelAccountservice implements FifthLevelAccountinterface
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
                'code' => $inputs['fourth_level'].$inputs['account_code'],
                'name' => $inputs['name'],
                'level' => 5,
            ];

            $fifthLevelAccount = $this->model()->create($data);

            return $fifthLevelAccount;
        });
    }
}
