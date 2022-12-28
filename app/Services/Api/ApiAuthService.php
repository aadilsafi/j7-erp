<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class ApiAuthService
{

    public function login($data)
    {
        try {
            $validation = Validator::make($data, [
                'email' => 'required|email|string',
                'password' => 'required|string'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validation->getMessageBag()
                ], 401);
            }
            if (!Auth::attempt($data)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.'
                ], 401);
            }
            $user = User::find(Auth::user()->id);
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'accessToken' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function register($data)
    {
        $this->validation->customerRegister($data);
        return $this->userRepo->register($data);
    }
}
