<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $guarded = [];

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
