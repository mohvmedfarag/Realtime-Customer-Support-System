<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function agents()
    {
        return $this->hasMany(Agent::class, 'department_id', 'id');
    }
}
