<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    protected $guarded = [];

    public function defineable()
    {
        return $this->morphTo();
    }
}
