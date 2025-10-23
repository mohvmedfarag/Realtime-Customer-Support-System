<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $guarded = [];

    protected $casts = [
        'helpful' => 'boolean',
    ];

    public function rater()
    {
        return $this->morphTo();
    }

    public function rated()
    {
        return $this->morphTo();
    }
    
    public function session()
    {
        return $this->belongsTo(SessionChat::class);
    }
}
