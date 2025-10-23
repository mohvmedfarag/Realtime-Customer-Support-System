<?php

namespace App\Http\Controllers\Portal;

use App\Models\Agent;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\AssignWaitingSessionsJob;

class AgentController extends Controller
{
    public function sendMessageAsAgent(Request $request)
    {
        $request->validate([
            'session_uuid' => 'required|exists:session_chats,uuid',
            'message'      => 'nullable|string',
            'file'         => 'nullable|file|max:20480',
        ]);

        $agent = auth()->guard('agent-api')->user();
        $session = SessionChat::where('uuid', $request->session_uuid)
            ->where('agent_id', $agent->id)
            ->first();

        if (! $session) {
            return response()->json([
                'message' => 'هذه الجلسة غير مرتبطة بك.',
            ], 403);
        }

        if ($request->hasFile('file')) {
            $path = 'http://192.168.1.143/EstbnAI/public/storage/' . $request->file('file')->store('chat_files', 'public');
            $content     = $path;
            $messageType = 'file';
        } else {
            $content     = $request->message;
            $messageType = 'text';
        }

        // $session->touchActivity();

        $message = $session->messages()->create([
            'sender'     => 'agent',
            'content'    => $content,
            'type'       => $messageType,
            'media_path' => $request->hasFile('file') ? $path : null,
            'status'     => true,
        ]);

        return response()->json([
            'message' => 'تم إرسال الرد بنجاح',
            'data' => [
                'message_id'      => $message->id,
                'chat_id'         => $session->chat_id,
                'session_chat_id' => $message->session_chat_id,
                'session_uuid'    => $session->uuid,
                'sender'          => $message->sender,
                'content'         => $message->content,
                'media_path'      => $message->media_path,
                'type'            => $message->type,
                'status'          => (bool) $message->status,
            ],
        ]);
    }

    public function transferToAgent(Request $request)
    {
        $sessionUUID = $request->input('session_uuid');
        $session = SessionChat::where('uuid', $sessionUUID)->first();

        if (! $session) {
            return response()->json(['status' => false, 'message' => 'Session not found'], 404);
        }

        // update session status to waiting_agent
        $session->update(['status' => 'waiting_agent']);

        AssignWaitingSessionsJob::dispatchSync($session);

        return response()->json([
            'message' => 'تم تحويل المحادثة الي خدمة العملاء برجاء الانتظار',
            'session_status' => $session->status,
        ]);
    }

    public function assignSessionToAgent(Request $request)
    {
        $sessionUUID = $request->input('session_uuid');
        $session = SessionChat::where('uuid', $sessionUUID)
            ->where('status', 'waiting_agent')->first();

        if (! $session) {
            return response()->json(['status' => false, 'message' => 'No waiting session found']);
        }

        $agent = Agent::where('status', 'online')->first();

        if (! $agent) {
            return response()->json(['status' => false, 'message' => 'No available agent online']);
        }

        // connect session to agent
        $session->update([
            'status' => 'in_agent',
            'agent_id' => $agent->id,
        ]);

        // update agent status to busy
        $agent->update(['status' => 'busy']);

        return response()->json([
            'message' => 'Session assigned to agent successfully',
            'agent' => $agent,
            'session' => $session
        ]);
    }

    public function closeSession(Request $request)
    {
        $sessionUUID = $request->input('session_uuid');
        $session = SessionChat::where('uuid', $sessionUUID)
            ->where('status', 'in_agent')->first();

        if (! $session) {
            return response()->json(['status' => false, 'message' => 'No active session found']);
        }

        $agent = $session->agent;

        $session->update([
            'status' => 'closed',
            'agent_id' => null,
        ]);

        if ($agent && $agent->status === 'busy') {
            $agent->update(['status' => 'online']);

            // check if there are waiting sessions
            $waitingSession = SessionChat::where('status', 'waiting_agent')
                ->orderBy('created_at', 'asc') // FIFO
                ->first();

            if ($waitingSession) {
                $waitingSession->update([
                    'status' => 'in_agent',
                    'agent_id' => $agent->id,
                ]);

                $agent->update(['status' => 'busy']);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Session closed. Agent assigned to new waiting session if available.',
            'session' => $session,
        ]);
    }

    public function getSessionHistory($sessionUuid)
    {
        $session = SessionChat::where('uuid', $sessionUuid)->first();
        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Session not found'
            ], 404);
        }
        $sessionId = $session->id;

        // user messages
        $userMessages = DB::table('messages')
            ->where('session_chat_id', $sessionId)
            ->select('id', 'content as text', 'created_at', DB::raw("'user' as sender"))
            ->get();

        // bot responses
        $botResponses = DB::table('responses')
            ->join('messages', 'responses.message_id', '=', 'messages.id')
            ->where('messages.session_chat_id', $sessionId)
            ->select(
                'responses.id',
                'responses.created_at',
                DB::raw("'bot' as sender"),
                'responses.type',
                'responses.content'
            )
            ->get()
            ->map(function ($item) {
                $item->content = json_decode($item->content, true); // هنا بتفك JSON
                return $item;
            });

        // merge and sort by created_at
        $history = $userMessages
            ->merge($botResponses)
            ->sortBy('created_at')
            ->values()
            ->all();

        return response()->json([
            'status' => true,
            'session_uuid' => $sessionUuid,
            'history' => $history
        ]);
    }

    public function showSessionStatus(Request $request)
    {
        $sessionUUID = $request->input('session_uuid');
        $session = SessionChat::where('uuid', $sessionUUID)->first();

        if (! $session) {
            return response()->json(['status' => false, 'message' => 'Session not found'], 404);
        }

        return response()->json([
            'status' => true,
            'session' => $session,
        ]);
    }

    public function resetSessionToBot(Request $request)
    {
        $sessionUUID = $request->input('session_uuid');
        $session = SessionChat::where('uuid', $sessionUUID)->first();

        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Session not found',
            ], 404);
        }

        $session->update([
            'status' => 'bot',
            'agent_id' => null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Session reset to bot',
            'session' => $session,
        ]);
    }

    public function checkAvailableAgent()
    {
        $agent = Agent::where('status', 'online')->first();

        if ($agent) {
            return response()->json([
                'available' => true,
                'message'   => 'There is an available agent',
                'agent'     => $agent,
            ]);
        }

        return response()->json([
            'available' => false,
            'message' => 'No available agent right now',
        ]);
    }
}
