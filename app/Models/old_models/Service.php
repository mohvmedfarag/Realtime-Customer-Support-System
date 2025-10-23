<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    public function types()
    {
        return $this->hasMany(ServiceType::class);
    }
}
