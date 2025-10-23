<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatResponse extends Model
{
    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
