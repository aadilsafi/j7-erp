<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Auth;
use App\Exceptions\FailException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class ApiAuthService
{

    public function login($data)
    {
        $validation = Validator::make($data, [
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        if ($validation->fails()) {
            return response()->json([
            'error' => $validation->getMessageBag()], 404);
        }
        if (!Auth::attempt($data)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::find(Auth::user()->id);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register($data)
    {
        $this->validation->customerRegister($data);
        return $this->userRepo->register($data);
    }
}
