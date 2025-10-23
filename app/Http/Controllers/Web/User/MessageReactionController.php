<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\MessageReaction;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Factory;

class MessageReactionController extends Controller
{
    public function toggleReaction(Request $request)
    {
        $request->validate([
            'firebase_id' => 'required',
            'reaction' => 'required|string'
        ]);

        $userId = auth()->guard('web')->id();
        $message = Message::where('firebase_id', $request->firebase_id)->firstOrFail();

        $existingReaction = MessageReaction::where('message_id', $message->id)
            ->where('user_id', $userId)
            ->first();

        $status = '';
        $reaction = $request->reaction;

        if ($existingReaction) {
            if ($existingReaction->reaction === $reaction) {
                $existingReaction->delete();
                $status = 'removed';
                $reaction = null;
            } else {
                $existingReaction->update(['reaction' => $reaction]);
                $status = 'replaced';
            }
        } else {
            MessageReaction::create([
                'message_id' => $message->id,
                'user_id'    => $userId,
                'reaction'   => $reaction,
            ]);
            $status = 'added';
        }

        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();

        $reactionData = [
            'reaction' => $reaction,
            'user_id' => $userId,
            'timestamp' => time()
        ];

        if ($reaction === null) {
            $firebase->getReference("messages/{$message->firebase_id}/reactions/{$userId}")->remove();
        } else {
            $firebase->getReference("messages/{$message->firebase_id}/reactions/{$userId}")->set($reactionData);
        }

        return response()->json([
            'status' => $status,
            'reaction' => $reaction
        ]);
    }
}
