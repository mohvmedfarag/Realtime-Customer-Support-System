<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattaryBrand extends Model
{
    protected $guarded = [];

    public function battaryAmbers()
    {
        return $this->belongsToMany(BattaryAmber::class, 'battary_amber_battary_brand', 'battary_brand_id');
    }

}
