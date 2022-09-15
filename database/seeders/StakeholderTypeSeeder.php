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
        //
        $stakeholderTypeData  = [
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 1,
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 1,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 1,
            ],

            // StakeHolder2
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 1,
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => 2,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . str_pad(1, 3, "0", STR_PAD_LEFT),
                'status' => 1,
            ],
        ];
        $stakeholder_type = StakeholderType::insert($stakeholderTypeData);
    }
}
