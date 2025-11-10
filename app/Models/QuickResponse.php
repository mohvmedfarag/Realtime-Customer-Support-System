<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickResponse extends Model
{
    protected $fillable = [ 'question', 'answer', 'agent_id' ];

    public function agent(){
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}
