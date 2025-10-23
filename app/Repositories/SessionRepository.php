<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\SessionChat;

class SessionRepository
{
    public function getAllSessionData()
    {
        return auth()->user()->chat->sessionChats ?? collect();
    }

    public function getSessionByUUID($session_uuid)
    {
        return SessionChat::where('uuid', $session_uuid)->firstOrFail();
    }

    public function getSessionFromRequest($request)
    {
        return SessionChat::where('uuid', $request->uuid)->first();
    }

    public function createSession($name)
    {
        $user = auth()->guard('web')->user();
        $chat = $user->chat ?: Chat::create(['user_id' => $user->id]);
        $session = $chat->sessionChats()->create([
            'status' => 'bot',
            'name' => $name,
        ]);

        return $session;
    }

    public function getUnSeenMessagesFromSession($session, $sender)
    {
        $unSeenMessages = $session->messages()
            ->where('sender', $sender)->where('seen', false)->get();

        return $unSeenMessages;
    }

    public function updateMessageSeen($message)
    {
        return $message->update(['seen' => true]);
    }
}
