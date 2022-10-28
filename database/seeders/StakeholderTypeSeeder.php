<?php

namespace Database\Seeders;

use App\Models\StakeholderType;
use Illuminate\Database\Seeder;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StakeholderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paddingZero = str_pad(1, 3, "0", STR_PAD_LEFT);
        $stakeholderTypeData  = [
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $paddingZero,
                'status' => 1,
                'receivable_account' => null,
                'payable_account' => '20201010000001',
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => '20201030000001',
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => '20201020000001',
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => null,
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $paddingZero,
                'status' => 1,
                'receivable_account' => null,
                'payable_account' => null,
            ],

            // StakeHolder2
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $paddingZero,
                'status' => 1,
                'receivable_account' => null,
                'payable_account' => '20201010000002',
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => '20201030000002',
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => '20201020000002',
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $paddingZero,
                'status' => 0,
                'receivable_account' => null,
                'payable_account' => null,
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $paddingZero,
                'status' => 1,
                'receivable_account' => null,
                'payable_account' => null,
            ],
        ];

        foreach ($stakeholderTypeData as $key => $value) {
            (new StakeholderType())->create($value);
        }
    }
}
