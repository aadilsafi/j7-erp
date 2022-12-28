<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class SalesPlanApiController extends Controller
{

    public function generateSalesPlan(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'crm_lead_id' => 'required|exists:stakeholders,crm_id',
            ], [
                'crm_lead_id.exists' => 'This Lead Does Not Exists.'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validation->getMessageBag()
                ], 401);
            }

            $user = auth()->guard('sanctum')->user();

            return response()->json([
                'status' => true,
                'redirectUrl' => route('sites.sales_plan.generateSalesPlan', ['site_id' => encryptParams(1), 'user_id' => encryptParams($user->id), 'crm_lead' => encryptParams($request->crm_lead_id)]),
                'method' => 'get',
                'auth' => auth()->guard('sanctum')->check()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
