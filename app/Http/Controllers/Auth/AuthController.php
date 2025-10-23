<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'message' => 'logged in successfully',
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'credentials does not match',
        ], 403);
    }

    public function logout(Request $request)
    {
        $user = auth()->guard('api')->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}
