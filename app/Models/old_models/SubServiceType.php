<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubServiceType extends Model
{
    protected $guarded = [];
    public function type()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
