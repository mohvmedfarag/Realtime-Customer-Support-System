<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view('Admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required','string','min:8',]
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Credentials do not match');
        }

        auth()->guard('admin')->login($admin);

        return redirect()->route('dashboard.index');
    }

    public function logout(){
        auth()->guard('admin')->logout();
        return redirect()->route('dashboard.showLoginForm');
    }
}
