<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showRegisterForm(){
        return view('Admin.register');
    }
    public function register(Request $request){
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $admin = Admin::where('email', $request->input('email'))->first();

        if($admin){
            return redirect()->back()->with('error', 'credentials does not match');
        }

        $admin = Admin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return [
            'admin' => $admin,
            'message' => 'Admin Created Successful',
        ];
    }

    public function showLoginForm(){
        return view('Admin.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email', $request->input('email'))->first();

        if(!$admin){
            return redirect()->back()->with('error', 'credentials does not match');
        }

        auth()->guard('admin')->login($admin);

        return redirect()->route('dashboard.index');
    }

    public function logout(){
        auth()->guard('admin')->logout();
        return redirect()->route('dashboard.showLoginForm');
    }
}
