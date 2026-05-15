<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAgent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo(SessionChat::class, 'session_id', 'id');
    }
}
