<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiAuthService;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $apiAuthService;

    public function __construct(ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     */
    public function login(Request $request)
    {
        $data = $request->only(
            'email',
            'password',
        );
        return $this->apiAuthService->login($data, $request);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Session Expire'
        ], 200);
       
    }

    public function checkAuth()
    {
        if (auth()->check()) {
            return response()->json(['message'  =>  'success']);
        } else {
            return response('error', 401);
        }
    }
}
