<?php

namespace App\Http\Controllers\Web\User\Dashboard;

use App\Models\Agent;
use App\Models\Message;
use App\Models\ChatTopic;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $topics = ChatTopic::where('parent_id', null)->get();
        $sessions = $user->chat->sessionChats()->get();
        return view('User.layout', compact('user', 'topics', 'sessions'));
    }

    public function getSubTopics($id)
    {
        $topic = ChatTopic::with('children')->findOrFail($id);
        return response()->json([
            'children' => $topic->children
        ]);
    }

    public function createSessionFromTopic(Request $request)
    {
        $topic = ChatTopic::find($request->topic_id);
        $user = Auth::user();

        $activeSession = $user->chat->sessionChats()
        ->whereIn('status', ['waiting_agent', 'in_agent'])
        ->first();

        if ($activeSession) {
            return response()->json([
                'error' => 'لا يمكنك فتح جلسة جديدة أثناء وجود جلسة نشطة بالفعل.',
                'active_session_id' => $activeSession->id
            ], 403);
        }

        $randomAgent = Agent::where('status', 'online')->inRandomOrder()->first();

        if (!$topic || !$topic->is_final) {
            return response()->json(['error' => 'الموضوع غير صالح أو ليس نهائيًا.'], 400);
        }

        // ✅ البحث عن جلسة قديمة بنفس الاسم
        $existingSession = $user->chat->sessionChats()
            ->where('name', $topic->title)
            ->first();

        if ($existingSession) {
            if ($existingSession->status === 'in_agent') {
                $messages = $existingSession->messages()->oldest()->get();
                return response()->json([
                    'messages' => $messages,
                    'session' => $existingSession,
                ]);
            }

            $existingSession->update([
                'status' => 'waiting_agent',
                'agent_id' => $randomAgent->id,
            ]);
            $messages = $existingSession->messages()->oldest()->get();
            return response()->json([
                'messages' => $messages,
                'session' => $existingSession,
            ]);
        }

        // ✅ إنشاء جلسة جديدة
        $session = $user->chat->sessionChats()->create([
            'name' => $topic->title,
            'status' => 'waiting_agent',
            'agent_id' => $randomAgent->id,
        ]);

        $messages = $session->messages()->oldest()->get();

        return response()->json([
            'messages' => $messages,
            'session' => $session,
        ]);
    }

    public function getMessages(SessionChat $session)
    {
        $user = Auth::user();
        $randomAgent = Agent::where('status', 'online')->inRandomOrder()->first();

        if ($session->status === 'in_agent') {
            $messages = $session->messages()->oldest()->get();
            return response()->json([
                'messages' => $messages,
                'session'  => $session,
            ]);
        }

        $activeSession = $user->chat->sessionChats()
        ->whereIn('status', ['waiting_agent', 'in_agent'])
        ->first();

        if ($activeSession && $activeSession->id != $session->id) {
            return response()->json([
                'error' => 'لا يمكنك فتح جلسة جديدة أثناء وجود جلسة نشطة بالفعل.',
                'active_session_id' => $activeSession->id
            ], 403);
        }

        if (!$randomAgent && $session->status !== 'in_agent') {
            return response()->json([
                'error' => 'No agents available at the moment. Please try again later.'
            ], 503);
        }

        $session->update([
            'status' => 'waiting_agent',
            'agent_id' => $randomAgent->id,
        ]);

        $database = $this->getFirebaseDatabase();
        $database->getReference("sessions/{$session->id}")
            ->set([
                'id'         => $session->id,
                'name'       => $session->name,
                'status'     => $session->status,
                'user_name'  => $user->name,
                'agent_name' => $randomAgent->name,
                'agent_id'   => $randomAgent->id,
                'created_at' => now()->toDateTimeString(),
            ]);
        $messages = $session->messages()->oldest()->get();
        return response()->json([
            'messages' => $messages,
            'session'  => $session,
        ]);
    }

    public function createSession(Request $request)
    {
        $user = Auth::user();

        $activeSession = $user->chat->sessionChats()
        ->whereIn('status', ['waiting_agent', 'in_agent'])
        ->first();

        if ($activeSession) {
            return response()->json([
                'error' => 'لا يمكنك فتح جلسة جديدة أثناء وجود جلسة نشطة بالفعل.',
                'active_session_id' => $activeSession->id
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $existing = $user->chat->sessionChats()->where('name', $request->name)->first();
        if ($existing) {
            return response()->json([
                'message'    => 'لديك جلسة بالفعل بنفس الاسم',
                'session_id' => $existing->id,
            ], 409);
        }

        $randomAgent = Agent::where('status', 'online')->inRandomOrder()->first();
        $session = $user->chat->sessionChats()->create([
            'name'   => $request->name,
            'status' => 'waiting_agent',
            'agent_id' => $randomAgent->id,
        ]);

        $database = $this->getFirebaseDatabase();
        $database->getReference("sessions/{$session->id}")
            ->set([
                'id'         => $session->id,
                'name'       => $session->name,
                'status'     => $session->status,
                'user_name'  => $user->name,
                'agent_name' => $randomAgent->name,
                'agent_id'   => $randomAgent->id,
                'created_at' => now()->toDateTimeString(),
            ]);

        $session->messages()->create([
            'sender'  => 'user',
            'content' => "موضوع المحادثة: " . $session->name,
        ]);

        return response()->json([
            'message' => 'تم إنشاء الجلسة بنجاح',
            'session' => $session,
        ]);
    }

    public function sendMessageByUser(Request $request){
        $request->validate([
            'session_id' => 'required|exists:session_chats,id',
            'content' => 'required|string',
        ]);

        $session = SessionChat::findOrFail($request->session_id);

        $message = $session->messages()->create([
            'sender' => 'user',
            'sender_id' => Auth::id(),
            'receiver_id' => $session->agent_id ?? null,
            'content' => $request->content,
            'session_chat_id' => $session->id,
        ]);

        $database = $this->getFirebaseDatabase();

        $firebaseMessage = [
            'id' => $message->id,
            'sender' => 'user',
            'content' => $message->content,
            'created_at' => $message->created_at->toDateTimeString(),
            'sender_name' => Auth::user()->name,
        ];

        $ref = "chats/{$session->id}/messages";
        $firebaseRecord = $database->getReference($ref)->push($firebaseMessage);

        $message->update(['firebase_id' => $firebaseRecord->getKey()]);

        return response()->json([
            'message' => 'تم إرسال الرسالة بنجاح',
            'data' => $message,
        ]);
    }

    public function closeChat(SessionChat $session)
    {
        if ($session->status === 'waiting_agent' || $session->status === 'in_agent') {
            $session->update([
                'status' => 'closed',
                'agent_id' => null,
            ]);

            $database = $this->getFirebaseDatabase();
            $firebasePath = "sessions/{$session->id}";
            $database->getReference($firebasePath)->update([
                'agent_id' => null,
                'agent_name' => null,
                'department_id' => null,
                'status' => 'closed',
                'updated_at' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'message' => 'تم غلق الجلسة بنجاح',
            ], 200);
        }
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
