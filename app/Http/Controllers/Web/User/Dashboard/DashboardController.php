<?php

namespace App\Http\Controllers\Web\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChatTopic;
use App\Models\Message;
use App\Models\SessionChat;
use Illuminate\Http\Request;
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
                return response()->json($messages);
            }

            $existingSession->update(['status' => 'waiting_agent']);
            $messages = $existingSession->messages()->oldest()->get();
            return response()->json($messages);
        }

        // ✅ إنشاء جلسة جديدة
        $session = $user->chat->sessionChats()->create([
            'name' => $topic->title,
            'status' => 'waiting_agent',
        ]);

        // ✅ أول رسالة في الجلسة
        // $message = $session->messages()->create([
        //     'sender' => 'user',
        //     'content' => "موضوع المحادثة: " . $session->name,
        // ]);

        $messages = $session->messages()->oldest()->get();

        return response()->json($messages);
    }

    public function getMessages(SessionChat $session)
    {
        if ($session->status === 'in_agent') {
            $messages = $session->messages()->oldest()->get();
            return response()->json($messages);
        }

        $session->update(['status' => 'waiting_agent',]);
        $messages = $session->messages()->oldest()->get();
        return response()->json($messages);
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $existing = $user->chat->sessionChats()->where('name', $request->name)->first();
        if ($existing) {
            return response()->json([
                'message' => 'لديك جلسة بالفعل بنفس الاسم',
                'session_id' => $existing->id,
            ], 409);
        }

        $session = $user->chat->sessionChats()->create([
            'name' => $request->name,
            'status' => 'waiting_agent',
        ]);

        $session->messages()->create([
            'sender' => 'user',
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
            'content' => $request->content,
            'session_chat_id' => $session->id,
        ]);

        return response()->json([
            'message' => 'تم إرسال الرسالة بنجاح',
            'data' => $message,
        ]);
    }
}
