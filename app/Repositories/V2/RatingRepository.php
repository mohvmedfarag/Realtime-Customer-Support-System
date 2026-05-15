<?php
namespace App\Repositories\V2;

use App\Models\Rating;
use App\Models\SessionAgent;
use Illuminate\Support\Facades\Auth;

class RatingRepository
{
    // Repository methods would go here

    public function getAgentSession($session){
        return SessionAgent::where('session_id', $session->id)->latest()->first();
    }

    public function submitRating($session, $request){
        $sessionAgent = $this->getAgentSession($session);
        $rating = Rating::create([
            'session_id' => $sessionAgent->session_id,
            'user_id' => Auth::id(),
            'agent_id' => $sessionAgent->agent_id,
            'helpful' => $request->input('rating'),
            'comment' => $request->input('comment') ?? null,
        ]);

        return $rating;
    }
}
