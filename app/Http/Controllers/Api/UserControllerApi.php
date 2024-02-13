<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserControllerApi extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
