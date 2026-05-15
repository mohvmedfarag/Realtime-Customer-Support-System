<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::select('id', 'name', 'email', 'status')->get();

        return view('Admin.Agent.agents', compact('agents'));
    }

    public function showCreateAgentForm()
    {
        $departments = Department::all();

        return view('Admin.Agent.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:agents,email'],
            'password' => ['required', 'min:8'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $agent = Agent::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department_id' => $validated['department_id'] ?? null,
            'status' => 'offline',
        ]);

        return redirect()
            ->route('dashboard.agents')
            ->with('success', 'Agent created successfully');
    }

    public function show(Agent $agent)
    {
        $departments = Department::all();

        return view('Admin.Agent.show', compact('agent', 'departments'));
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'department' => 'nullable|exists:departments,id',
        ]);

        $agent->department_id = $request->department;
        $agent->save();

        return redirect()->back()->with('success', 'تم تحديث بيانات الوكيل بنجاح ✅');
    }
}
