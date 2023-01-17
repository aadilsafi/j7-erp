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
                'lead_as' => 'required|string',
                // 'cnic' => 'sometimes|nullable',
                // 'email' => 'sometimes|nullable|email',
                // 'office_email' => 'sometimes|nullable|email',
                // 'office_contact' => 'sometimes|nullable',
                // 'primary_address' => 'sometimes|nullable',
                // 'primay_address_postal_code' => 'sometimes|nullable',
                'country_id' => 'sometimes|nullable|exists:countries,id',
                'state_id'  => 'sometimes|nullable|exists:states,id',
                'city_id'   => 'sometimes|nullable|exists:cities,id',
                // 'other_address' => 'sometimes|nullable',
                // 'other_address_postal_code' => 'sometimes|nullable',
                // 'other_address_country_id' => 'sometimes|nullable|exists:countries,id',
                // 'other_address_state_id'  => 'sometimes|nullable|exists:states,id',
                // 'other_address_city_id'   => 'sometimes|nullable|exists:cities,id',
                // 'description' => 'sometimes|nullable',
                // 'refered_by' => 'sometimes|nullable',
                // 'is_local' => 'sometimes|nullable',
                // 'nationality' => 'sometimes|nullable|exists:countries,id'
            ],[
                'nationality.exists' => 'Selected nationlity Country not Exists.',
                'primay_address_country_id.exists' => 'Selected primary address country not Exists.',
                'primay_address_state_id.exists' => 'Selected primary address state not Exists.',
                'primay_address_city_id.exists' => 'Selected primary address city not Exists.',
                'other_address_country_id.exists' => 'Selected other address country not Exists.',
                'other_address_state_id.exists' => 'Selected other address state not Exists.',
                'other_address_city_id.exists' => 'Selected other address city not Exists.',
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
                // 'father_name' => $request->father_name,
                // 'date_of_birth' => $request->date_of_birth,
                'mobile_contact' => strpos($request->contact_no, '+') == 0 ?  $request->contact_no : '+' . $request->contact_no,
                'stakeholder_as' => $request->lead_as,
                'residential_country_id' => $request->country_id,
                'residential_state_id' => $request->state_id,
                'residential_city_id' => $request->city_id,
                // 'residential_address' => $request->primary_address,
                // 'residential_postal_code' => $request->primay_address_postal_code,
                // 'mailing_country_id' => $request->other_address_country_id,
                // 'mailing_state_id' => $request->other_address_state_id,
                // 'mailing_city_id' => $request->other_address_city_id,
                // 'mailing_address' => $request->other_address,
                // 'mailing_postal_code' => $request->other_address_postal_code,
                // 'cnic' => $request->cnic,
                // 'designation' => $request->designation,
                // 'nationality' => $request->nationality,
                // 'occupation' => $request->occupation,
                // 'email' => $request->email,
                // 'office_email' => $request->office_email,
                // 'office_contact' => $request->office_contact,
                // 'comments' => $request->description,
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
