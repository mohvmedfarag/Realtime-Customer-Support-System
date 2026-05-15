<?php
namespace App\Services\V2;

use App\Repositories\V2\SessionRepository;

class SessionService
{
    protected $sessionRepository;
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getParentTopics(){
        return $this->getParentTopics();
    }

    public function getUserSessions(){
        return $this->getUserSessions();
    }
}
