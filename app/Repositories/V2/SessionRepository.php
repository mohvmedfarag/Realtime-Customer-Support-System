<?php
namespace App\Repositories\V2;

use App\Traits\AuthUser;
use App\Models\ChatTopic;

class SessionRepository
{
    use AuthUser;
    public function getParentTopics(){
        return ChatTopic::where('parent_id', null)->get();
    }

    public function getUserSessions(){
        $user = $this->getAuthUser();
        return $user->chat->sessionChats()->get();
    }
}
