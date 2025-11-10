<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Department;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(){
        $agents = Agent::select('id', 'name', 'email', 'status')->get();
        return view('Admin.agents', compact('agents'));
    }

    public function show(Agent $agent){
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
