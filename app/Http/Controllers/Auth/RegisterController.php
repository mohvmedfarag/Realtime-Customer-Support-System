<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string']
        ]);

        if (!$data) {
            return response()->json([
                'status' => false,
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['name'],
            'password' => Hash::make($data['password'])
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Account created successfully',
            'token' => $user->createToken($data['email'])->plainTextToken,
        ]);
    }
}
