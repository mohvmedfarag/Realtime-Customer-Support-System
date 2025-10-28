<?php

namespace App\Http\View\Composers;

use App\Models\SessionChat;
use Illuminate\View\View;

class SessionsCountComposer
{
    public function compose(View $view)
    {
        $sessionsCount = SessionChat::where('status', 'waiting_agent')->count();
        $view->with('sessionsCount', $sessionsCount);
    }
}
