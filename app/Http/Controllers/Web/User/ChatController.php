<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageWebRequest;
use App\Models\SessionChat;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use App\Jobs\CheckAgentInactivityJob;

class ChatController extends Controller
{
    use ApiResponse;

    public function sendMessageByUser(StoreMessageWebRequest $request)
    {
        $request->validated();

        $session = SessionChat::where('uuid', $request->uuid)->first();

        if (! $session) {
            return $this->apiError('هذه الجلسة غير مرتبطة بك.', 400);
        }

        $type = 'text';
        $content = $request->message;
        $media_path = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('chat_files', 'public');
            $media_path = asset('storage/'.$storedPath);

            $fileType = $file->getClientOriginalExtension();
            $content = 'ملف مرفق ('.strtoupper($fileType).')';
            $type = 'file';
        }

        $msg = $session->messages()->create([
            'sender' => 'user',
            'session_chat_id' => $session->id,
            'content' => $content,
            'type' => $type,
            'media_path' => $media_path,
            'status' => true,
            'seen' => false,
            'replay_to_id' => $request->replay_to_id,
        ]);

        CheckAgentInactivityJob::dispatch($session)->delay(now()->addSeconds(60));

        $this->pushToFirebase($session, $msg);

        return response()->json([
            'firebase_id' => $msg->firebase_id,
            'message' => $msg->content,
            'time' => $msg->created_at->format('h:i A'),
            'type' => $msg->type,
            'media' => $media_path,
            'seen' => $msg->seen,
            'is_starred' => $msg->is_starred,
            'is_pinned' => $msg->is_pinned,
            'replay_to' => ($msg->replyTo && $msg->replyTo->exists) ? [
                'id' => $msg->replyTo->id,
                'message' => $msg->replyTo->content,
                'type' => $msg->replyTo->type,
            ] : null,
        ]);
    }

    public function showAgentForm()
    {
        $agent = auth()->guard('agent')->user();

        return view('Agent.agent', compact('agent'));
    }

    public function replayByAgent(StoreMessageWebRequest $request)
    {
        $request->validated();

        $agent = auth()->guard('agent')->user();
        $session = SessionChat::where('uuid', $request->uuid)->first();

        if (! $session || $session->agent_id !== $agent->id) {
            return $this->apiError('هذه الجلسة غير مرتبطة بك.', 403);
        }

        $type = 'text';
        $content = $request->message;
        $media_path = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('chat_files', 'public');
            $media_path = asset('storage/'.$storedPath);

            $fileType = $file->getClientOriginalExtension();
            $content = 'ملف مرفق ('.strtoupper($fileType).')';
            $type = 'file';
        }

        $msg = $session->messages()->create([
            'sender' => 'agent',
            'session_chat_id' => $session->id,
            'content' => $content,
            'type' => $type,
            'media_path' => $media_path,
            'status' => true,
            'seen' => false,
            'replay_to_id' => $request->replay_to_id,
        ]);

        $session->update(['last_agent_activity' => now()]);
        CheckAgentInactivityJob::dispatch($session)->delay(now()->addSeconds(60));

        $this->pushToFirebase($session, $msg);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح',
            'data' => $msg,
            'replay_to' => ($msg->replyTo && $msg->replyTo->exists) ? [
                'id' => $msg->replyTo->id,
                'message' => $msg->replyTo->content,
                'type' => $msg->replyTo->type,
            ] : null,
        ]);
    }

    public function endSession(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);

        $user = auth()->guard('web')->user();
        $chat = $user->chat;
        $session = SessionChat::where('uuid', $request->uuid)
            ->where('chat_id', $chat->id)
            ->first();

        if (! $session) {
            return $this->apiError('هذه الجلسة غير مرتبطة بك.', 400);
        }

        $agent = $session->agent;

        $session->update([
            'status' => 'closed',
            'agent_id' => null,
        ]);

        $agent->update(['status' => 'online']);

        // تحديث Firebase (نفس أسلوب الـ Job)
        Http::put(
            "https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app/sessions/{$session->uuid}.json",
            [
                'uuid' => $session->uuid,
                'status' => 'closed',
                'agent_id' => null,
            ]
        );

        return response()->json([
            'message' => 'تم إنهاء المحادثة بنجاح',
            'agent_id' => $agent->id,
            'session_id' => $session->id,
        ]);
    }

    // /////////// Helper Methods ////////////////
    protected function pushToFirebase($session, $msg)
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();

        $messageData = [
            'sender' => $msg->sender,
            'content' => $msg->content,
            'time' => $msg->created_at->format('h:i A'),
            'type' => $msg->type,
            'seen' => $msg->seen,
            'is_starred' => $msg->is_starred,
            'is_pinned' => $msg->is_pinned,
            'edited' => $msg->edited,
            'session_chat_id' => $msg->session_chat_id,
        ];

        if ($msg->media_path) {
            $messageData['media_path'] = $msg->media_path;
        }

        if ($msg->media_path) {
            $messageData['media_path'] = $msg->media_path;
        }

        if ($msg->replay_to_id) {
            $messageData['replay_to_id'] = $msg->replay_to_id;

            $originalMsg = $msg->sessionChat->messages()->find($msg->replay_to_id);
            if ($originalMsg) {
                $messageData['replay_content'] = $originalMsg->content ?? null;
            }
        }

        $ref = $firebase->getReference("chats/{$session->uuid}/messages/{$msg->id}")
            ->set($messageData);

        $firebaseId = $ref->getKey();
        $msg->update(['firebase_id' => $firebaseId]);

        return $ref;
    }

    protected function createMessage(SessionChat $session, string $sender, string $content, $type = 'text')
    {
        $msg = $session->messages()->create([
            'sender' => $sender,
            'session_chat_id' => $session->id,
            'content' => $content,
            'type' => $type,
            'status' => true,
            'seen' => false,
        ]);

        $this->pushToFirebase($session, $msg);

        return $msg;
    }
}
