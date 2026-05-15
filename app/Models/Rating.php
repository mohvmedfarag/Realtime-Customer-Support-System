<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $guarded = [];

    protected $casts = [
        'helpful' => 'boolean'
    ];

    public function session(){
        return $this->belongsTo(SessionChat::class, 'session_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agent(){
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}
