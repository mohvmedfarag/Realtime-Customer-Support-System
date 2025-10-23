<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Response;
use App\Events\MessageSent;
use App\Models\SessionChat;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BotRequest;
use App\Http\Resources\SessionResource;
use App\Http\Requests\UserMessageRequest;
use App\Http\Resources\UnansweredMessageResource;

class ChatController extends Controller
{
    use ApiResponse;

    public function startNewChatSession()
    {
        $user = auth()->guard('api')->user();
        $chat = $user->chat ?: Chat::create(['user_id' => $user->id]);
        $session = $chat->sessionChats()->create();

        return response()->json([
            'status'  => true,
            'message' => 'مرحبا معاك مستر استبن تحب اساعدك ازاي ؟',
            'session' => new SessionResource($session),
        ]);
    }

    public function storeConversationFromAI(BotRequest $request)
    {
        $request->validated();

        $session = SessionChat::where('uuid', $request->session_uuid)->first();

        if (! $session || $session->status !== 'bot') {
            return $this->apiError('لا يمكن للبوت الرد في هذه الجلسة حالياً', 403);
        }

        $checkMessage = Message::where('id', $request->id)
            ->where('session_chat_id', $session->id)
            ->first();

        if (!$checkMessage) {
            return $this->apiError('لا يوجد رسالة سابقة بنفس المحتوى في هذه الجلسة', 400);
        }

        if ($request->hasFile('file')) {
            $path    = 'http://192.168.1.143/EstbnAI/public/storage/' . $request->file('file')->store('chat_files', 'public');
            $content = $path;
        }

        // change any JSON string in the response to an array
        $content = is_array($request->response)
            ? array_map(function ($item) {
                if (is_string($item) && $this->isJson($item)) {
                    return json_decode($item, true);
                }
                return $item;
            }, $request->response)
            : [$request->response];

        $type = $request->input('type', 'text');

        $response = Response::create([
            'message_id' => $checkMessage->id,
            'content'    => $content,
            'type'       => $type,
            'media_path' => $path ?? null,
        ]);

        $checkMessage->update([
            'status' => true,
        ]);

        return response()->json([
            'message'  => 'تم حفظ المحادثة بنجاح',
            'message'  => $checkMessage->content,
            'response' => $response,
        ]);
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public function sendMessage(UserMessageRequest $request)
    {
        $request->validated();
        $user    = auth()->guard('api')->user();
        $chat    = $user->chat;
        $session = SessionChat::where('uuid', $request->session_uuid)->first();

        if (! $chat || ! $session || $session->chat_id !== $chat->id) {
            return $this->apiError('لا توجد جلسة دردشة صالحة.', 400);
        }

        if ($request->hasFile('file')) {
            $path = 'http://192.168.1.143/EstbnAI/public/storage/' . $request->file('file')->store('chat_files', 'public');
            $content     = $path;
            $messageType = 'file';
        } else {
            $content     = $request->message;
            $messageType = 'text';
        }

        $session->touchActivity();
        $message = $session->messages()->create([
            'sender'     => 'user',
            'content'    => $content,
            'type'       => $messageType,
            'media_path' => $request->hasFile('file') ? $path : null,
        ]);

        // broadcast(new MessageSent($message, $chat->id, $request->session_uuid))->toOthers();

        if ($session->status == 'closed') {
            $session->update(['status' => 'bot']);
        } else if ($session->status == 'waiting_agent') {
            return response()->json(['message' => 'تم تحويل المحادثة الي خدمة العملاء برجاء الانتظار']);
        }

        return response()->json([
            'message' => 'تم ارسال الرسالة بنجاح',
            'data' => [
                'message_id'      => $message->id,
                'chat_id'         => $chat->id,
                'session_chat_id' => $message->session_chat_id,
                'session_uuid'    => $session->uuid,
                'session_status'  => $session->status,
                'sender'          => $message->sender,
                'content'         => $message->content,
                'media_path'      => $message->media_path,
                'type'            => $message->type,
                'status'          => (bool) $message->status,
            ],
        ]);
    }

    public function getLastUnansweredUserMessage()
    {
        $user = auth()->guard('api')->user();

        if (! $user) {
            return $this->apiError('المستخدم غير مسجل الدخول', 401);
        }

        $chat = $user->chat;
        if (! $chat) {
            return $this->apiError('لا يوجد شات لهذا المستخدم', 404);
        }

        // search for the last user message in any session of the chat,
        // which does not have a Response yet
        $lastUnanswered = Message::with('sessionChat')
            ->userUnansweredForChat($chat->id)->latest()->first();

        if (! $lastUnanswered) {
            return $this->apiError('لا توجد رسائل منتظرة للرد عليها', 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب آخر رسالة يوزر بلا ردّ',
            'data'    => new UnansweredMessageResource($lastUnanswered),
        ]);
    }

    public function getLastBotResponseForUser(Request $request)
    {
        $chatID = $request->input('chat-id');
        $sessionUUID = $request->input('session-uuid');

        $user = auth()->guard('api')->user();
        if (! $user) {
            return $this->apiError('المستخدم غير مسجل الدخول', 401);
        }

        $chat = $user->chat;
        if (! $chat || (int)$chatID !== (int)$chat->id) {
            return $this->apiError('الشات غير موجود أو غير مسموح بالوصول إليه', 403);
        }

        $session = SessionChat::where('uuid', $sessionUUID)
            ->where('chat_id', $chat->id)->first();

        if (! $session) {
            return $this->apiError('السيشن غير موجود او لا يتبع هذا الشات', 404);
        }

        $lastBotResponse = Response::whereHas('message', function ($q) use ($session) {
            $q->where('session_chat_id', $session->id);
        })
            ->with('message.sessionChat')
            ->latest()
            ->first();

        if (! $lastBotResponse) {
            return $this->apiError('لا يوجد ردود من البوت في هذه الجلسة', 404);
        }

        $message = $lastBotResponse->message;

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب رد البوت بنجاح',
            // 'response_id' => $lastBotResponse->id,
            'message_id'    => $message->id,
            'type'          => 'dropdown',
            'session_uuid'  => $session->uuid,
            'chat_id'       => $chat->id,
            'answer'       => $lastBotResponse->content,
            'media_path'    => $lastBotResponse->media_path ?? null,
        ]);
    }

    public function checkUserMessage(Request $request)
    {
        // validate input
        $request->validate([
            'session' => 'required|uuid'
        ]);

        $session_uuid = $request->session;

        $session = SessionChat::where('uuid', $session_uuid)->first();

        if (! $session) {
            return $this->apiError('السيشن غير موجود', 404);
        }

        // if you want to ensure it belongs to the authenticated user's chat:
        // $user = auth()->guard('api')->user();
        // if (!$user || $user->chat->id !== $session->chat_id) { ... 403 ... }

        $message = $session->messages()
            ->where('sender', 'user')
            ->latest('created_at')
            ->first();

        if (! $message) {
            return response()->json([
                'status'  => false,
                'message' => 'لا توجد رسائل في هذه الجلسة',
                'data'    => null,
            ], 404);
        }

        if ($message->status === true) {
            $m = 'تم جلب آخر رسالة للمستخدم في هذه الجلسة';
        } else {
            $m = 'لم يتم الرد بعد';
        }

        return response()->json([
            // 'status'  => true, // api success
            'message' => $m,
            'data'    => [
                'message_id'    => $message->id,
                'content'       => $message->content,
                'type'          => $message->type,
                'media_path'    => $message->media_path ?? null,
                'session_id'    => $session->id,
                'session_uuid'  => $session->uuid,
                'chat_id'       => $session->chat_id,
                'status'      => (bool) $message->status,
                'created_at'    => $message->created_at,
            ],
        ], 200);
    }

    public function hasAnyActive()
    {
        $minutes = 5;
        $threshold = now()->subMinutes($minutes);

        // إذا فيه أي جلسة آخر نشاطها خلال الـ 1 دقيقة الأخيرة => نعتبرها active
        $hasActive = SessionChat::where('last_activity', '>=', $threshold)->exists();

        return response()->json(['active' => $hasActive]);
    }

    public function lastSession()
    {
        $session = SessionChat::latest()->first();
        return response()->json([
            'session' => $session,
        ]);
    }

    public function checkAgentMessage(Request $request)
    {
        // validate input
        $request->validate([
            'session' => 'required|uuid'
        ]);

        $session_uuid = $request->session;

        $session = SessionChat::where('uuid', $session_uuid)->first();

        if (! $session) {
            return $this->apiError('السيشن غير موجود', 404);
        }

        $lastUserMessage = $session->messages()
            ->where('sender', 'user')
            ->latest('created_at')
            ->first();

        $lastAgentMessage = $session->messages()
            ->where('sender', 'agent')
            ->latest('created_at')
            ->first();

        if (! $lastUserMessage) {
            return $this->apiError('لا توجد رسائل من المستخدم', 404);
        }

        if (! $lastAgentMessage || $lastAgentMessage->created_at < $lastUserMessage->created_at) {
            return response()->json([
                'status'  => false,
                'message' => 'لم يتم الرد بعد',
                'data'    => null,
            ], 200);
        }

        return response()->json([
            'message' => 'تم جلب آخر رد من الـ agent',
            'data'    => $lastAgentMessage,
        ], 200);
    }
}
