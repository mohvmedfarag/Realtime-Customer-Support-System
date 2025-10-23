<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\SessionChat;

class RatingController extends Controller
{
    public function showRatingUser($session_id, $agent_id)
    {
        return view('User.rating', compact('session_id', 'agent_id'));
    }

    public function showRatingAgent($session_id, $user_id)
    {
        return view('Agent.rating', compact('session_id', 'user_id'));
    }

    public function storeUserFeedback(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:session_chats,id',
            'agent_id' => 'required|exists:agents,id',
            'helpful' => 'required|boolean',
            'comment' => 'nullable|string',
        ]);

        // Store feedback logic here (e.g., save to database)
        Feedback::create([
            'rater_id' => auth()->id(),
            'rater_type' => 'user',
            'rated_id' => $request->input('agent_id'),
            'rated_type' => 'agent',
            'session_id' => $request->input('session_id'),
            'helpful' => $request->input('helpful'),
            'comment' => $request->comment,
        ]);

        return redirect()->route('sessions')->with('success', 'Thank you for your feedback!');
    }

    public function storeAgentFeedback(Request $request)
    {
        $request->validate([
                'session_id' => 'required|exists:session_chats,id',
                'user_id' => 'required|exists:users,id',
                'helpful' => 'required|boolean',
                'comment' => 'nullable|string',
            ]);

        $id = auth()->guard('agent')->user()->id;
        // // Store feedback logic here (e.g., save to database)
        Feedback::create([
            'rater_id' => $id,
            'rater_type' => 'agent',
            'rated_id' => $request->input('user_id'),
            'rated_type' => 'user',
            'session_id' => $request->input('session_id'),
            'helpful' => $request->input('helpful'),
            'comment' => $request->comment,
        ]);

        return redirect()->route('agent')->with('success', 'Thank you for your feedback!');
    }
}
