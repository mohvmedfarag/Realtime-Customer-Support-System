<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $admin = auth()->guard('admin')->user();
        return view('Admin.dashboard', compact('admin'));
    }
}
