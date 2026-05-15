<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required','string','min:8',],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // User Login
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('web')->login($user);

            return redirect()->route('user.dashboard');
        }

        // Agent Login
        $agent = Agent::where('email', $email)->first();

        if ($agent && Hash::check($password, $agent->password)) {

            Auth::guard('agent')->login($agent);

            $agent->update([
                'status' => 'online',
            ]);

            return redirect()->route('agent.dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function UserLogout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('showLoginForm');
    }

    public function AgentLogout()
    {
        $agent = Auth::guard('agent')->user();
        Agent::where('id', $agent->id)->update(['status' => 'offline']);
        Auth::guard('agent')->logout();
        return redirect()->route('showLoginForm');
    }
}
