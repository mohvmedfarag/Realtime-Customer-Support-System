<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\StarMessagesService;
use Illuminate\Http\Request;

class StarMessageController extends Controller
{
    protected $starMessagesService;

    public function __construct(StarMessagesService $starMessagesService)
    {
        $this->starMessagesService = $starMessagesService;
    }

    public function starMessages(Request $request)
    {
        $starredMessages = $this->starMessagesService->getStarredMessages();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('User.star_messages', compact('starredMessages'))->render()
            ]);
        }

        return view('User.chat', compact('starredMessages'));
    }
}
