<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        try
        {
            $validated = $registerRequest->validated();

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            $token = $user->createToken('Laravel Personal Access Client')->accessToken;

            $userResponse = array_merge($user->toArray(), ["token" => $token]);

            return response()->json(["status" => 200, "user" => $userResponse, "message" => "Registration Successful"]);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()]);
        }

    }
}
