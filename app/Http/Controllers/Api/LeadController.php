<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class LeadController extends Controller
{

    public function saveLead(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'crm_id' => 'required',
                'name' => 'required|string',
                'contact_no' => 'required|unique:stakeholders,mobile_contact',
                'lead_as' => 'required|string'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validation->getMessageBag()
                ], 401);
            }

            $stakeholder = Stakeholder::create([
                'site_id' => '1',
                'crm_id' => $request->crm_id,
                'full_name' => $request->name,
                'mobile_contact' => $request->contact_no,
                'stakeholder_as' => $request->lead_as,
                'residential_country_id' => $request->country_id,
                'residential_state_id' => $request->state_id,
                'residential_city_id' => $request->city_id,
            ]);
            $stakeholderTypeCode = Str::of($stakeholder->id)->padLeft(3, '0');

            $stakeholderTypeData  = [
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::CUSTOMER->value,
                    'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::VENDOR->value,
                    'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::DEALER->value,
                    'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                    'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::LEAD->value,
                    'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $stakeholderTypeCode,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            $stakeholderType = (new StakeholderType())->insert($stakeholderTypeData);
            return response()->json([
                'stakeholder' => $stakeholder,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        // $data = $request->all();
        // return $data;
    }
}
