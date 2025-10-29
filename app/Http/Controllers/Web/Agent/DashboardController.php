<?php

namespace App\Http\Controllers\Web\Agent;

use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Agent.dashboard');
    }

    public function showWaitingSessions()
    {
        $sessions = SessionChat::where('status', 'waiting_agent')->get();
        return view('Agent.sessions', compact('sessions'));
    }

    public function joinWaitingSessions(SessionChat $session)
    {
        $session->status = 'in_agent';
        $session->agent_id = auth()->guard('agent')->user()->id;
        $session->save();

        $database = $this->getFirebaseDatabase();
        $database->getReference("sessions/{$session->id}")
            ->update([
                'status' => 'in_agent',
                'agent_name' => auth()->guard('agent')->user()->name,
                'agent_id' => auth()->guard('agent')->id(),
            ]);

        $user = $session->chat->user;
        $messages = $session->messages()->oldest()->get();
        return view('Agent.joinSession', compact('session', 'user', 'messages'));
    }

    public function sendMessageByAgent(Request $request){
        $request->validate([
            'session_id' => 'required|exists:session_chats,id',
            'message' => 'required|string',
        ]);

        $session = SessionChat::find($request->session_id);

        $message = $session->messages()->create([
            'sender' => 'agent',
            'content' => $request->message,
            'session_chat_id' => $session->id,
        ]);

        $database = $this->getFirebaseDatabase();

        $firebaseMessage = [
            'id' => $message->id,
            'sender' => 'agent',
            'content' => $message->content,
            'created_at' => $message->created_at->toDateTimeString(),
        ];

        $ref = "chats/{$session->id}/messages";
        $firebaseRecord = $database->getReference($ref)->push($firebaseMessage);

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
