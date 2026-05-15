<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Models\Agent;
use App\Models\Message;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function index(){
        $sessions = SessionChat::with('chat.user')->get()
        ->map(function($session){
            return (object) [
                'session_id' => $session->id,
                'session_name' => $session->name,
                'user_id' => $session->chat->user_id,
                'user_name' => $session->chat->user->name,
                'status' => $session->status,
            ];
        });

        $count = $sessions->count();
        return view('Admin.sessions', compact('sessions', 'count'));
    }

    public function showWaitingSessions(){
        $sessions = SessionChat::where('status', 'waiting_agent')
        ->where('agent_id','!=', null)->get();

        $count = $sessions->count();

        $agents = Agent::where('status', 'online')->get();

        return view('Admin.Session.waitingSessions', compact('sessions', 'agents', 'count'));
    }

    public function transferSessionToAgent(Request $request){
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'session_id' => 'required|exists:session_chats,id',
        ]);

        $agent = Agent::where('id', $request->input('agent_id'))->first();
        $session = SessionChat::where('id', $request->input('session_id'))->first();

        $session->update([
            'agent_id' => $agent->id,
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

        // flash()->addSuccess('تم تعيين الجلسة بنجاح إلى ' . $agent->name);
        return response()->json([
            'message' => 'session assigned successfully to ' . $agent->name,
        ]);
    }

    public function showActiveSessions(){
        $sessions = SessionChat::where('status', 'in_agent')
        ->where('agent_id', '!=', null)->get();

        $count = $sessions->count();

        return view('Admin.Session.activeSessions', compact('sessions', 'count'));
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
