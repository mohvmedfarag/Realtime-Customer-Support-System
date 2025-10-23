<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index(){
        $departments = Department::withCount('agents')->get();
        return view('Admin.Department.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Department::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'تمت إضافة الإدارة بنجاح ✅');
    }
}
