<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilBrand extends Model
{
    protected $guarded = [];

    public function viscosities()
    {
        return $this->hasMany(OilViscosity::class, 'oil_brand_id');
    }
}
