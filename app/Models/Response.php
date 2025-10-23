<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $guarded = [];

    protected $casts = [ 'content' => 'array', ];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
