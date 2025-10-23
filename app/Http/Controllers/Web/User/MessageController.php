<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Message;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function deleteMessage(Request $request)
    {
        $firebaseId = $request->input('id');
        $message = Message::where('firebase_id', $firebaseId)->first();

        if ($message) {
            if ($message->sender === 'agent') {
                return response()->json(['error' => 'لا يمكن حزف رسائل خدمة العملاء']);
            }

            $sessionUUID = $message->sessionChat->uuid;
            if ($message->type === 'file' && $message->media_path) {
                $filePath = str_replace(asset('storage') . '/', '', $message->media_path);
                Storage::disk('public')->delete($filePath);
            }
            $message->delete();
        } else {
            $sessionUUID = $request->input('session_uuid');
        }

        try {
            $factory = (new Factory)->withServiceAccount(storage_path('app/firebase_credentials.json'))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

            $database = $factory->createDatabase();
            if ($firebaseId) {
                $database->getReference("chats/{$sessionUUID}/messages/{$firebaseId}")->remove();
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'تم حذف الرسالة من قاعدة البيانات لكن فشل الحذف من Firebase',
                'details' => $e->getMessage()
            ]);
        }
        return response()->json(['success' => 'تم حزف الرسالة بنجاح']);
    }

    public function updateSeen(Request $request)
    {
        $firebase_id = $request->input('firebase_id');
        $message = Message::where('firebase_id', $firebase_id)->first();

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => "Message not found",
            ], 404);
        }

        $message->update([
            'seen' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => "message seen successfully",
        ]);
    }

    public function updateMessage(Request $request)
    {
        $message = Message::where('firebase_id', $request->firebase_id)->first();

        if ($message && $message->sender === 'user') {
            $message->update([
                'content' => $request->content,
                'edited' => true
            ]);

            return response()->json(['success' => true, 'message' => 'Message updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Message not found or not allowed'], 404);
    }

    public function toggleStarMessage(Request $request)
    {
        $id = $request->input('id');
        $message = Message::where('id', $id)->first();
        $message->is_starred = !$message->is_starred;
        $message->save();

        return response()->json([
            'is_starred' => $message->is_starred,
        ]);
    }

    public function togglePinMessage(Request $request)
    {
        $id = $request->input('id');
        $message = Message::where('firebase_id', $id)->first();
        $sessionId = $message->session_chat_id;

        Message::where('session_chat_id', $sessionId)
            ->where('is_pinned', true)
            ->update(['is_pinned' => false]);

        $message->is_pinned = !$message->is_pinned;
        $message->save();

        return response()->json([
            'is_pinned' => $message->is_pinned,
            'content'   => $message->content,
        ]);
    }

    public function removePinMessage(Request $request)
    {
        $sessionId = $request->session_id;

        Message::where('session_chat_id', $sessionId)
            ->where('is_pinned', true)
            ->update(['is_pinned' => false]);

        return response()->json(['success' => true]);
    }









    public function clearAllMessages()
    {
        Message::query()->delete(); // delete all messages

        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/firebase_credentials.json'))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

            $database = $factory->createDatabase();

            $chatsRef = $database->getReference('chats');
            $chats = $chatsRef->getValue();

            if ($chats) {
                foreach ($chats as $sessionUUID => $chatData) {
                    $database->getReference("chats/{$sessionUUID}/messages")->remove();
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'فشل حذف الرسائل من Firebase',
                'details' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => 'تم حذف كل الرسائل من قاعدة البيانات و Firebase بنجاح'
        ]);
    }
}
