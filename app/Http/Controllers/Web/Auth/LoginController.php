<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::guard('web')->login($user);
            return redirect()->route('sessions');
        }

        $agent = Agent::where('email', $email)->first();
        if ($agent) {
            Auth::guard('agent')->login($agent);
            $agent->update([ 'status' => 'online' ]);
            return redirect()->route('agent');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('showLoginForm');
    }

    public function AgentLogout(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        Agent::where('id', $agent->id)->update(['status' => 'offline']);
        Auth::guard('agent')->logout();
        return redirect()->route('showLoginForm');
    }
}
