<?php

namespace App\Services;

use App\Repositories\SessionRepository;

class SessionService
{
    protected $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getAllSessionData()
    {
        return $this->sessionRepository->getAllSessionData();
    }

    public function getSessionByUUID($session_uuid)
    {
        return $this->sessionRepository->getSessionByUUID($session_uuid);
    }

    public function getSessionFromRequest($request)
    {
        return $this->sessionRepository->getSessionFromRequest($request);
    }

    public function createSession($name)
    {
        return $this->sessionRepository->createSession($name);
    }

    public function getUnSeenMessagesFromSession($session, $sender){
        return $this->sessionRepository->getUnSeenMessagesFromSession($session, $sender);
    }

    public function markMessagesAsSeen($unSeenMessages, $firebase, $session){

        foreach ($unSeenMessages as $message) {
            $this->sessionRepository->updateMessageSeen($message);
            $firebase->getReference("chats/{$session->uuid}/messages/{$message->id}/seen")
                ->set(true);
        }

        return $unSeenMessages;
    }
}
