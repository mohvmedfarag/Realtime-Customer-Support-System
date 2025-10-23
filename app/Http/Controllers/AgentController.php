<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;

class AgentController extends Controller
{
    public function index(){
        $agents = Agent::all();
        return response()->json([
            'agents' => $agents,
        ]);
    }

    public function resetAgentsStatus(){
        $agents = Agent::all();

        foreach($agents as $agent){
            $agent->status = 'offline';
            $agent->save();
        }

        return response()->json([
            'agents' => $agents,
        ]);
    }

    public function show(){
        $agent = auth()->guard('agent-api')->user();
        return response()->json([
            'agent' => $agent,
        ]);
    }
}
