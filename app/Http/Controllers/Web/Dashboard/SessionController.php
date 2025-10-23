<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SessionChat;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(){
        $sessions = SessionChat::with('chat.user')->get()
        ->map(function($session){
            return (object) [
                'session_id' => $session->id,
                'session_name' => $session->name,
                'user_id' => $session->chat->user_id,
                'user_name' => $session->chat->user->name,
            ];
        });
        return view('Admin.sessions', compact('sessions'));
    }
}
