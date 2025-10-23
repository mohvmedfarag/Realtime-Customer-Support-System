<?php

namespace App\Services;

use App\Models\Message;
use App\Models\SessionChat;

class StarMessagesService
{
    public function getStarredMessages($session_uuid)
    {
        $session = SessionChat::where('uuid', $session_uuid)->first();
        $messages = Message::where('session_chat_id', $session->id)
            ->where('is_starred', true)->get();
        return $messages;
    }

    public function getAllStarredMessages(){
        $user = auth()->guard('web')->user();

        $messages = $user->chat->sessionChats()
        ->with(['messages' => function ($q) {
            $q->where('is_starred', true)
              ->select('id', 'session_chat_id', 'firebase_id', 'content', 'media_path', 'type', 'created_at', 'is_starred', 'sender');
        }])
        ->get()
        ->pluck('messages')
        ->flatten();

        return $messages;
    }
}
