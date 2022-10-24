<?php

namespace Database\Seeders;

use App\Models\AccountHead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10',
                'name' => 'Asset',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1010',
                'name' => 'Non-current Asset',
                'level' => 2,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1020',
                'name' => 'Current Asset',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\Type',
                'code' => '102020',
                'name' => 'Accounts Receviable - Shops',
                'level' => 3,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200001',
                'name' => 'GF-01 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202000011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],

            [
                'site_id' => 1,
                'modelable_id' => 2,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200002',
                'name' => 'GF-02 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202000021001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 3,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200003',
                'name' => 'GF-03 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 4,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200004',
                'name' => 'GF-04 Receviable',
                'level' => 4,
            ],

            [
                'site_id' => 1,
                'modelable_id' => 5,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200005',
                'name' => 'GF-05 Receviable',
                'level' => 4,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 2,
                'modelable_type' => 'App\Models\Type',
                'code' => '102021',
                'name' => 'Accounts Receviable - Hotel Suites',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1020210001',
                'name' => '3F-01 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202100011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 3,
                'modelable_type' => 'App\Models\Type',
                'code' => '102022',
                'name' => 'Accounts Receviable - Offices',
                'level' => 3,
            ],
        ];

        foreach ($data as $item) {
            (new AccountHead())->create($item);
        }
    }
}
