<?php

namespace App\Http\Controllers\Web\Agent;

use App\Models\Agent;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentSessionController extends Controller
{
    public function transfer(Request $request, SessionChat $session){
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $newAgent = Agent::where('department_id', $request->department_id)
        ->where('status', 'online')->inRandomOrder()->first();

        if (!$newAgent) {
            return response()->json(['error' => 'لا يوجد أي وكيل في هذا القسم حالياً'], 404);
        }

        DB::transaction(function () use ($session, $newAgent) {
            $session->update([
                'agent_id' => $newAgent->id,
                'status' => 'waiting_agent',
                'waiting_started_at' => now(),
            ]);
            $database = $this->getFirebaseDatabase();
            $firebasePath = "sessions/{$session->id}";

            $database->getReference($firebasePath)->update([
                'agent_id' => $newAgent->id,
                'agent_name' => $newAgent->name,
                'department_id' => $newAgent->department_id,
                'status' => 'waiting_agent',
                'updated_at' => now()->toDateTimeString(),
            ]);
        });

        return response()->json([
            'success' => 'تم تحويل الجلسة بنجاح',
            'new_agent' => $newAgent->name,
        ]);
    }

    public function transferToAgent(Request $request, SessionChat $session){
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $agent = Agent::where('id', $request->input('agent_id'))->first();

        DB::transaction(function () use ($session, $agent) {
            $session->update([
                'agent_id' => $agent->id,
                'status' => 'waiting_agent',
                'waiting_started_at' => now(),
            ]);
            $database = $this->getFirebaseDatabase();
            $firebasePath = "sessions/{$session->id}";

            $database->getReference($firebasePath)->update([
                'agent_id' => $agent->id,
                'agent_name' => $agent->name,
                'department_id' => $agent->department_id,
                'status' => 'waiting_agent',
                'updated_at' => now()->toDateTimeString(),
            ]);
        });

        return response()->json([
            'success' => 'تم تحويل الجلسة بنجاح',
            'new_agent' => $agent->name,
        ]);
    }

    public function myWaitingSessions(){
        $sessions = SessionChat::where('agent_id', Auth::guard('agent')->user()->id)
        ->where('status', 'waiting_agent')->get();

        return view('Agent.myWaitingSessions', compact('sessions'));
    }

    private function getFirebaseDatabase()
    {
        $database = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();

        return $database;
    }
}
