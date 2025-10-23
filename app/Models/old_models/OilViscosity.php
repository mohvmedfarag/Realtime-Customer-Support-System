<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilViscosity extends Model
{
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(OilBrand::class, 'oil_brand_id');
    }

    public function volumes()
    {
        return $this->hasMany(OilVolume::class, 'oil_viscosity_id');
    }  
}
