<?php

namespace App\Http\Controllers\Web\Agent;

use App\Models\Agent;
use App\Models\Department;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Agent.dashboard');
    }

    public function joinWaitingSessions(SessionChat $session)
    {
        $agent = auth()->guard('agent')->user();
        $session->status = 'in_agent';
        $session->agent_id = $agent->id;
        $session->waiting_started_at = null;
        $session->save();

        $session->messages()
        ->whereNull('receiver_id')
        ->where('sender', 'user')
        ->update([
            'receiver_id' => $agent->id,
        ]);

        $database = $this->getFirebaseDatabase();
        $database->getReference("sessions/{$session->id}")
            ->update([
                'status' => 'in_agent',
                'agent_name' => auth()->guard('agent')->user()->name,
                'agent_id' => auth()->guard('agent')->id(),
            ]);

        $user = $session->chat->user;
        $messages = $session->messages()->oldest()->get();
        $departments = Department::all();
        $agents = Agent::where('id', '!=', Auth::guard('agent')->user()->id)->get();
        return view('Agent.joinSession', compact('session', 'user', 'messages', 'departments', 'agents'));
    }

    public function sendMessageByAgent(Request $request){
        $request->validate([
            'session_id' => 'required|exists:session_chats,id',
            'message' => 'required|string',
        ]);

        $session = SessionChat::find($request->session_id);

        $message = $session->messages()->create([
            'sender' => 'agent',
            'sender_id' => Auth::guard('agent')->user()->id,
            'receiver_id' => $session->chat->user_id,
            'content' => $request->message,
            'session_chat_id' => $session->id,
        ]);

        $database = $this->getFirebaseDatabase();

        $firebaseMessage = [
            'id' => $message->id,
            'sender' => 'agent',
            'sender_name' => Auth::guard('agent')->user()->name,
            'sender_id' => Auth::guard('agent')->user()->id,
            'receiver_name' => $session->chat->user->name,
            'receiver_id' => $session->chat->user_id,
            'content' => $message->content,
            'created_at' => $message->created_at->toDateTimeString(),
        ];

        $ref = "chats/{$session->id}/messages/{$message->id}";
        $firebaseRecord = $database->getReference($ref)->set($firebaseMessage);

        $message->update(['firebase_id' => $firebaseRecord->getKey()]);

        return response()->json([
            'message' => 'تم ارسال الرسالة بنجاح',
            'data' => $message
        ]);
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
