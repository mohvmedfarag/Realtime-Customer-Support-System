<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattaryAmber extends Model
{
    protected $guarded = [];

    public function battaryTypes()
    {
        return $this->belongsToMany(BattaryType::class, 'battary_type_battary_amber', 'battary_amber_id');
    }

    public function battaryBrands()
    {
        return $this->belongsToMany(BattaryBrand::class, 'battary_amber_battary_brand', 'battary_amber_id');
    }
}
