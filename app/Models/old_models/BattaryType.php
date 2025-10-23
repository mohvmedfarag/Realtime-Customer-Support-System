<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattaryType extends Model
{
    protected $guarded = [];

    public function battaryAmbers()
    {
        return $this->belongsToMany(BattaryAmber::class, 'battary_type_battary_amber', 'battary_type_id');
    }
}
