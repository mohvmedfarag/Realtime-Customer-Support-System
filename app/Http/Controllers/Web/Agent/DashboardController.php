<?php

namespace App\Http\Controllers\Web\Agent;

use App\Http\Controllers\Controller;
use App\Models\SessionChat;
use Illuminate\Http\Request;

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

        return response()->json([
            'message' => 'تم ارسال الرسالة بنجاح',
            'data' => $message
        ]);
    }
}
