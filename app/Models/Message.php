<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status'      => 'boolean',
        'seen'        => 'boolean',
        'is_starred'  => 'boolean',
        'is_pinned'   => 'boolean',
        'is_long'     => 'boolean',
    ];

    public function sessionChat()
    {
        return $this->belongsTo(SessionChat::class, 'session_chat_id', 'id');
    }

    public function response()
    {
        return $this->hasOne(Response::class, 'message_id');
    }

    public function scopeUserUnansweredForChat($query, int $chatId)
    {
        return $query->where('sender', 'user')
            ->whereDoesntHave('response')
            ->whereHas('sessionChat', function ($q) use ($chatId) {
                $q->where('chat_id', $chatId);
            });
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'replay_to_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'replay_to_id');
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class, 'message_id', 'id');
    }
}
