<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $loginRequest)
    {
        try
        {
            $validated = $loginRequest->validated();

            $user = User::where('email', $validated['email'])->first();

            if(!Hash::check($validated['password'], $user->password))
            {
                return response()->json(['status' => 401, 'message' => "Invalid credentials"], 401);
            }

            $token = $user->createToken('Laravel Personal Access Client')->accessToken;

            $userResponse = array_merge($user->toArray(), ["token" => $token]);

            return response()->json(["status" => 200, "user" => $userResponse, "message" => "Login Successful"], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }
}
