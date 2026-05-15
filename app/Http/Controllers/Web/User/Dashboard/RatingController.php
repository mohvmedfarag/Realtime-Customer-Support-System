<?php

namespace App\Http\Controllers\Web\User\Dashboard;

use App\Models\Rating;
use App\Models\SessionChat;
use App\Models\SessionAgent;
use Illuminate\Http\Request;
use App\Services\V2\RatingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function submitRating(Request $request, SessionChat $session){
        $request->validate([
            'rating' => 'required|in:0,1',
            'comment' => 'nullable|string',
        ]);

        $rating = $this->ratingService->submitRating($session, $request);

        return response()->json([
            'message' => 'Thank you for your feedback! ❤️',
            'rating' => $rating,
        ]);
    }

}
