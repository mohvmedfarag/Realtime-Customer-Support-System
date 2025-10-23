<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilVolume extends Model
{
    protected $guarded = [];

    public function viscosity()
    {
        return $this->belongsTo(OilViscosity::class, 'oil_viscosity_id');
    }
}
