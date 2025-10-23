<?php

namespace App\Http\Controllers\Auth\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $agent = Agent::where('email', $request->email)->first();

        if (!$agent || Hash::check($request->password, $agent->password) == false) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $agent->status = 'online';
        $agent->save();
        $token = $agent->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'agent' => $agent,
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        $agent = auth()->guard('agent-api')->user();

        if ($agent) {
            $agent->status = 'offline';
            $agent->save();
            $agent->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}
